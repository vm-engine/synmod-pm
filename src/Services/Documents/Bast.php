<?php

namespace VmEngine\Pm\Services\Documents;

use Illuminate\Container\Attributes\Singleton;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Models\Project;

#[Singleton]
class Bast extends Base
{
    public function isActive(Project $project): bool
    {
        return $project->hasSPKDocument();
    }

    public function uploaded(Project $project, Documents $document): void
    {
        if ($project->status !== ProjectStatus::COMPLETED) {
            $project->status = ProjectStatus::COMPLETED;
            $project->save();
        }
    }

    public function deleting(Project $project, Documents $document): void
    {
        //
    }
}
