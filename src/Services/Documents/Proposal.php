<?php

namespace VmEngine\Pm\Services\Documents;

use Illuminate\Container\Attributes\Singleton;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;

#[Singleton]
class Proposal extends Base
{
    public function isActive(Project $project): bool
    {
        return true;
    }

    public function uploaded(Project $project, Documents $document): void
    {
        // TODO
    }

    public function deleting(Project $project, Documents $document): void
    {
        $toDelete = $project->documents()
            ->where('type', '>', Documents::PROPOSAL->value)
            ->pluck('id');

        ProjectDocument::destroy($toDelete);
    }
}
