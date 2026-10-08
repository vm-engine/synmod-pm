<?php

declare(strict_types=1);

use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Services\Proposal\ProposalDefaults;

/** PT ABS timeline (application defaults): 19/01/26 → 20/05/26, 88 working days. */
function absTasks(): array
{
    return ProposalDefaults::for(ProposalType::Application)['timeline_tasks'];
}

/** Think timeline (website defaults): 01/07/25 → 31/07/25, 23 working days. */
function thinkTasks(): array
{
    return ProposalDefaults::for(ProposalType::Website)['timeline_tasks'];
}
