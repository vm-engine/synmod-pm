<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Exceptions;

use RuntimeException;

final class ProposalValidationException extends RuntimeException
{
    /**
     * @param  array<string, list<string>>  $errors  tab => messages
     */
    public function __construct(public readonly array $errors)
    {
        parent::__construct(__('pm::proposal.validation_failed'));
    }
}
