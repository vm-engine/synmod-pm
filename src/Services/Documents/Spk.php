<?php

namespace VmEngine\Pm\Services\Documents;

use Illuminate\Container\Attributes\Singleton;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;

#[Singleton]
class Spk extends Base
{
    public function isActive(Project $project): bool
    {
        return $project->hasSPHDocument();
    }

    public function uploaded(Project $project, Documents $document): void
    {
        if ($project->status == ProjectStatus::PROSPECT) {
            $project->status = ProjectStatus::PLANNING->value;
            $project->save();
        }

        if ($project->client->isProspect()) {
            $project->client->activate();
        }
    }

    public function deleting(Project $project, Documents $document): void
    {
        $toDelete = $project->documents()
            ->where('type', '>', Documents::SPK->value)
            ->pluck('id');

        ProjectDocument::destroy($toDelete);
    }
}
