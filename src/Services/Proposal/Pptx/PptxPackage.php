<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Pptx;

use DOMDocument;
use DOMElement;
use InvalidArgumentException;
use RuntimeException;
use ZipArchive;

/**
 * Part-level editing of a .pptx (OOXML zip). Changes are buffered and written on save().
 */
final class PptxPackage
{
    private const CONTENT_TYPES = '[Content_Types].xml';

    private const PRESENTATION = 'ppt/presentation.xml';

    private const PRESENTATION_RELS = 'ppt/_rels/presentation.xml.rels';

    private const NS_P = 'http://schemas.openxmlformats.org/presentationml/2006/main';

    private const NS_R = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

    private const NS_REL = 'http://schemas.openxmlformats.org/package/2006/relationships';

    private const NS_CT = 'http://schemas.openxmlformats.org/package/2006/content-types';

    private const REL_SLIDE = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/slide';

    private const REL_IMAGE = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image';

    private const REL_NOTES = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/notesSlide';

    private const SLIDE_CONTENT_TYPE = 'application/vnd.openxmlformats-officedocument.presentationml.slide+xml';

    private ZipArchive $zip;

    /** @var array<string, string> */
    private array $changed = [];

    /** @var array<string, true> */
    private array $deleted = [];

    private function __construct(string $path)
    {
        $this->zip = new ZipArchive;
        if ($this->zip->open($path) !== true) {
            throw new RuntimeException("Cannot open PPTX: {$path}");
        }
    }

    public static function fromTemplate(string $template, string $target): self
    {
        if (! is_file($template) || ! copy($template, $target)) {
            throw new RuntimeException("Cannot copy PPTX template {$template} to {$target}");
        }

        return new self($target);
    }

    public function read(string $part): string
    {
        if (isset($this->deleted[$part])) {
            throw new RuntimeException("Part was deleted: {$part}");
        }
        if (array_key_exists($part, $this->changed)) {
            return $this->changed[$part];
        }
        $content = $this->zip->getFromName($part);
        if ($content === false) {
            throw new RuntimeException("Missing part: {$part}");
        }

        return $content;
    }

    public function has(string $part): bool
    {
        if (isset($this->deleted[$part])) {
            return false;
        }

        return array_key_exists($part, $this->changed) || $this->zip->locateName($part) !== false;
    }

    public function write(string $part, string $content): void
    {
        unset($this->deleted[$part]);
        $this->changed[$part] = $content;
    }

    public function dom(string $part): DOMDocument
    {
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = true;
        if (! $dom->loadXML($this->read($part))) {
            throw new RuntimeException("Invalid XML in {$part}");
        }

        return $dom;
    }

    public function writeDom(string $part, DOMDocument $dom): void
    {
        $this->write($part, (string) $dom->saveXML());
    }

    public function slidePart(int $number): string
    {
        return "ppt/slides/slide{$number}.xml";
    }

    /**
     * @return list<int> slide file numbers in presentation order
     */
    public function slideOrder(): array
    {
        $targets = $this->relationshipTargets(self::PRESENTATION_RELS);
        $order = [];
        foreach ($this->dom(self::PRESENTATION)->getElementsByTagNameNS(self::NS_P, 'sldId') as $sldId) {
            /** @var DOMElement $sldId */
            $target = $targets[$sldId->getAttributeNS(self::NS_R, 'id')] ?? '';
            if (preg_match('#slides/slide(\d+)\.xml$#', $target, $m) === 1) {
                $order[] = (int) $m[1];
            }
        }

        return $order;
    }

    public function removeSlide(int $number): void
    {
        $target = "slides/slide{$number}.xml";

        $rels = $this->dom(self::PRESENTATION_RELS);
        $rId = null;
        foreach ($this->elements($rels, self::NS_REL, 'Relationship') as $rel) {
            if ($rel->getAttribute('Target') === $target) {
                $rId = $rel->getAttribute('Id');
                $rel->parentNode?->removeChild($rel);
            }
        }
        if ($rId === null) {
            throw new InvalidArgumentException("Slide {$number} is not in the presentation");
        }
        $this->writeDom(self::PRESENTATION_RELS, $rels);

        $presentation = $this->dom(self::PRESENTATION);
        foreach ($this->elements($presentation, self::NS_P, 'sldId') as $sldId) {
            if ($sldId->getAttributeNS(self::NS_R, 'id') === $rId) {
                $sldId->parentNode?->removeChild($sldId);
            }
        }
        $this->writeDom(self::PRESENTATION, $presentation);

        $slideRels = $this->slideRelsPart($number);
        if ($this->has($slideRels)) {
            foreach ($this->relationshipTargets($slideRels, self::REL_NOTES) as $notesTarget) {
                $notesPart = 'ppt/'.ltrim(str_replace('../', '', $notesTarget), '/');
                $this->deletePart($notesPart);
                $this->deletePart(dirname($notesPart).'/_rels/'.basename($notesPart).'.rels');
            }
        }
        $this->deletePart($this->slidePart($number));
        $this->deletePart($slideRels);
    }

    public function cloneSlide(int $number, int $after): int
    {
        $new = $this->nextNumber('#^ppt/slides/slide(\d+)\.xml$#');
        $this->write($this->slidePart($new), $this->read($this->slidePart($number)));

        if ($this->has($this->slideRelsPart($number))) {
            $rels = $this->dom($this->slideRelsPart($number));
            foreach ($this->elements($rels, self::NS_REL, 'Relationship') as $rel) {
                if ($rel->getAttribute('Type') === self::REL_NOTES) {
                    $rel->parentNode?->removeChild($rel);
                }
            }
            $this->writeDom($this->slideRelsPart($new), $rels);
        }

        $types = $this->dom(self::CONTENT_TYPES);
        $override = $types->createElementNS(self::NS_CT, 'Override');
        $override->setAttribute('PartName', '/'.$this->slidePart($new));
        $override->setAttribute('ContentType', self::SLIDE_CONTENT_TYPE);
        $types->documentElement?->appendChild($override);
        $this->writeDom(self::CONTENT_TYPES, $types);

        $presentationRels = $this->dom(self::PRESENTATION_RELS);
        $rId = $this->nextRelationshipId($presentationRels);
        $rel = $presentationRels->createElementNS(self::NS_REL, 'Relationship');
        $rel->setAttribute('Id', $rId);
        $rel->setAttribute('Type', self::REL_SLIDE);
        $rel->setAttribute('Target', "slides/slide{$new}.xml");
        $presentationRels->documentElement?->appendChild($rel);
        $this->writeDom(self::PRESENTATION_RELS, $presentationRels);

        $afterRId = array_search("slides/slide{$after}.xml", $this->relationshipTargets(self::PRESENTATION_RELS), true);
        $presentation = $this->dom(self::PRESENTATION);
        $maxId = 255;
        $anchor = null;
        foreach ($this->elements($presentation, self::NS_P, 'sldId') as $sldId) {
            $maxId = max($maxId, (int) $sldId->getAttribute('id'));
            if ($sldId->getAttributeNS(self::NS_R, 'id') === $afterRId) {
                $anchor = $sldId;
            }
        }
        if ($anchor === null) {
            throw new InvalidArgumentException("Slide {$after} is not in the presentation");
        }
        $sldId = $presentation->createElementNS(self::NS_P, 'p:sldId');
        $sldId->setAttribute('id', (string) ($maxId + 1));
        $sldId->setAttributeNS(self::NS_R, 'r:id', $rId);
        $anchor->parentNode?->insertBefore($sldId, $anchor->nextSibling);
        $this->writeDom(self::PRESENTATION, $presentation);

        return $new;
    }

    public function addImage(int $slide, string $sourcePath): string
    {
        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        if (! in_array($extension, ['png', 'jpg', 'jpeg'], true)) {
            throw new InvalidArgumentException("Unsupported image type: {$extension}");
        }
        $contents = file_get_contents($sourcePath);
        if ($contents === false) {
            throw new RuntimeException("Cannot read image {$sourcePath}");
        }
        $media = 'proposal'.$this->nextNumber('#^ppt/media/proposal(\d+)\.\w+$#').'.'.$extension;
        $this->write('ppt/media/'.$media, $contents);

        $relsPart = $this->slideRelsPart($slide);
        $rels = $this->dom($relsPart);
        $rId = $this->nextRelationshipId($rels);
        $rel = $rels->createElementNS(self::NS_REL, 'Relationship');
        $rel->setAttribute('Id', $rId);
        $rel->setAttribute('Type', self::REL_IMAGE);
        $rel->setAttribute('Target', '../media/'.$media);
        $rels->documentElement?->appendChild($rel);
        $this->writeDom($relsPart, $rels);

        return $rId;
    }

    public function save(): void
    {
        foreach (array_keys($this->deleted) as $part) {
            if ($this->zip->locateName($part) !== false) {
                $this->zip->deleteName($part);
            }
        }
        foreach ($this->changed as $part => $content) {
            $this->zip->addFromString($part, $content);
        }
        if ($this->zip->close() !== true) {
            throw new RuntimeException('Cannot write PPTX');
        }
    }

    private function slideRelsPart(int $number): string
    {
        return "ppt/slides/_rels/slide{$number}.xml.rels";
    }

    private function deletePart(string $part): void
    {
        if (! $this->has($part)) {
            return;
        }
        unset($this->changed[$part]);
        $this->deleted[$part] = true;

        $types = $this->dom(self::CONTENT_TYPES);
        foreach ($this->elements($types, self::NS_CT, 'Override') as $override) {
            if ($override->getAttribute('PartName') === '/'.$part) {
                $override->parentNode?->removeChild($override);
            }
        }
        $this->writeDom(self::CONTENT_TYPES, $types);
    }

    /**
     * @return array<string, string> rId => Target (optionally only one relationship type)
     */
    private function relationshipTargets(string $relsPart, ?string $type = null): array
    {
        $targets = [];
        foreach ($this->elements($this->dom($relsPart), self::NS_REL, 'Relationship') as $rel) {
            if ($type === null || $rel->getAttribute('Type') === $type) {
                $targets[$rel->getAttribute('Id')] = $rel->getAttribute('Target');
            }
        }

        return $targets;
    }

    private function nextRelationshipId(DOMDocument $rels): string
    {
        $max = 0;
        foreach ($this->elements($rels, self::NS_REL, 'Relationship') as $rel) {
            if (preg_match('/^rId(\d+)$/', $rel->getAttribute('Id'), $m) === 1) {
                $max = max($max, (int) $m[1]);
            }
        }

        return 'rId'.($max + 1);
    }

    private function nextNumber(string $pattern): int
    {
        $max = 0;
        $names = array_keys($this->changed);
        for ($i = 0; $i < $this->zip->numFiles; $i++) {
            $names[] = (string) $this->zip->getNameIndex($i);
        }
        foreach ($names as $name) {
            if (! isset($this->deleted[$name]) && preg_match($pattern, $name, $m) === 1) {
                $max = max($max, (int) $m[1]);
            }
        }

        return $max + 1;
    }

    /**
     * @return list<DOMElement> (a static copy, safe to remove from while iterating)
     */
    private function elements(DOMDocument $dom, string $namespace, string $localName): array
    {
        return array_values(iterator_to_array($dom->getElementsByTagNameNS($namespace, $localName)));
    }
}
