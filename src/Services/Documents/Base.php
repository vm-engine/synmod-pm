<?php

namespace VmEngine\Pm\Services\Documents;

use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;

abstract class Base
{
    /**
     * Generate document number for the given project and document type.
     */
    public function generateDocumentNumber(Project $project, Documents $document): string
    {
        // Ensure relationships are loaded
        $project->loadMissing(['client', 'projectCategory']);

        $format = dbconf('pm.documents.'.$document->code(true).'.number_format');
        $cnc = $project->client->status == Client::STATUS_PROSPECT && $document->hasNCIndex() ? 'NC' : 'C';
        $index = dbconf('pm.documents.'.$project->category->code.'.'.$document->code().'.next_'.strtolower($cnc).'_index', 1);

        return $this->replaceFormat($format, $cnc, $index, $project, $document);
    }

    /**
     * Replace format placeholders with actual values.
     */
    private function replaceFormat(
        string $format,
        string $cnc,
        int $index,
        Project $project,
        Documents $document
    ): string {
        $replacements = [
            '{YYYY}' => date('Y'),
            '{YY}' => date('y'),
            '{MM}' => date('m'),
            '{MR}' => int2roman(date('n')),
            '{CNC}' => $cnc,
            '{PROJECT_CODE}' => $project->projectCategory->code,
            '{DIVISION_CODE}' => $project->division->code,
            '{DOC_CODE}' => $document->code(),
            '{CLIENT_ID}' => sprintf('%03d', $project->client_id),
            '{INDEX}' => sprintf('%03d', $index),
        ];

        return strtr($format, $replacements);
    }

    /**
     * Increment the document index for the given project and document type.
     */
    public function incrementIndex(Project $project, Documents $document): void
    {
        // Ensure relationships are loaded
        $project->loadMissing(['client']);

        $cnc = $project->client->status == Client::STATUS_PROSPECT && $document->hasNCIndex() ? 'nc' : 'c';
        $key = 'pm.documents.'.$project->category->code.'.'.$document->code().'.next_'.$cnc.'_index';
        $currentIndex = dbconf($key);
        dbconf([$key => $currentIndex + 1]);
    }

    /**
     * Check if this document type is active for the given project.
     */
    abstract public function isActive(Project $project): bool;

    /**
     * Hook called after a document is uploaded.
     */
    abstract public function uploaded(Project $project, Documents $document): void;

    abstract public function deleting(Project $project, Documents $document): void;
}
