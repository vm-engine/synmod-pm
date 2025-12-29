<?php

namespace VmEngine\Pm\Services\Documents;

use Illuminate\Container\Attributes\Singleton;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Project;

#[Singleton]
class Sph extends Base
{
    public function isActive(Project $project): bool
    {
        return $project->hasProposalDocument();
    }

    public function uploaded(Project $project, Documents $document): void
    {
        // TODO
    }
}
