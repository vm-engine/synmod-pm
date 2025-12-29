<?php

namespace VmEngine\Pm\Services\Documents;

use Illuminate\Container\Attributes\Singleton;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Models\Project;

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
}
