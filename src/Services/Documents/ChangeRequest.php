<?php

namespace VmEngine\Pm\Services\Documents;

use Illuminate\Container\Attributes\Singleton;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Project;

#[Singleton]
class ChangeRequest extends Base
{
    public function isActive(Project $project): bool
    {
        return $project->hasSPKDocument();
    }

    public function uploaded(Project $project, Documents $document): void
    {
        // TODO
    }

    public function deleting(Project $project, Documents $document): void
    {
        // TODO
    }
}
