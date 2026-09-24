<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Services\Concerns\RecordsActivity;

class ProjectService
{
    use RecordsActivity;

    /**
     * Create a new project.
     *
     * @param  array<string, mixed>  $projectData
     */
    public function create(array $projectData): Project
    {
        return DB::transaction(function () use ($projectData) {
            // Get client user_id if client_id provided
            $userId = isset($projectData['client_id'])
                ? $this->getClientUserId($projectData['client_id'])
                : Auth::id();

            $project = Project::create([
                ...$projectData,
                'user_id' => $userId,
                'created_by' => Auth::id(),
            ]);

            // Log activity
            $this->logActivity('create', $project, $projectData);

            return $project->fresh();
        });
    }

    /**
     * Update an existing project.
     *
     * @param  array<string, mixed>  $projectData
     */
    public function update(Project $project, array $projectData): Project
    {
        $oldData = $project->toArray();

        return DB::transaction(function () use ($project, $projectData, $oldData) {
            // Get client user_id if client_id provided, otherwise keep existing user_id
            $userId = isset($projectData['client_id'])
                ? $this->getClientUserId($projectData['client_id'])
                : $project->user_id;

            $project->update([
                ...$projectData,
                'user_id' => $userId,
                'updated_by' => Auth::id(),
            ]);

            // Log activity
            $this->logActivity('update', $project, null, $oldData);

            return $project->fresh();
        });
    }

    /**
     * Delete a project.
     */
    public function delete(Project $project): bool
    {
        $projectData = $project->toArray();

        return DB::transaction(function () use ($project, $projectData) {
            $deleted = $project->delete();

            // Log activity
            $this->recordActivity(
                'pm.project.'.$project->id.'.delete',
                'User '.Auth::user()->name.' deleted project. Data: '.json_encode($projectData),
                'project.'.$project->id,
            );

            return $deleted;
        });
    }

    /**
     * Find a project by id without failing.
     */
    public function findProject(int $id): ?Project
    {
        return Project::find($id);
    }

    /**
     * Get projects that already have at least one SPK document.
     *
     * Used by the standalone invoices module so that its picker only
     * surfaces projects that are ready for invoice creation.
     *
     * @return Collection<int, Project>
     */
    public function getSpkReadyProjects(): Collection
    {
        return Project::query()
            ->whereHas('documents', fn ($q) => $q->where('type', Documents::SPK->value))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Parse server info JSON string into array.
     *
     * @return array<string, mixed>
     */
    public function parseServerInfo(?string $serverInfo): array
    {
        if (empty($serverInfo)) {
            return [];
        }

        $decoded = json_decode($serverInfo, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Get user_id from selected client.
     */
    private function getClientUserId(?int $clientId): ?int
    {
        if (! $clientId) {
            return null;
        }

        $client = Client::find($clientId);

        return $client?->user_id;
    }

    /**
     * Log project activity.
     *
     * @param  array<string, mixed>|null  $newData
     * @param  array<string, mixed>|null  $oldData
     */
    private function logActivity(string $action, Project $project, ?array $newData = null, ?array $oldData = null): void
    {
        $message = match ($action) {
            'create' => 'User '.Auth::user()->name.' created new project. Data: '.json_encode($newData),
            'update' => 'User '.Auth::user()->name.' updated project data. Old Data: '.json_encode($oldData).' New Data: '.json_encode($project->toArray()),
            'delete' => 'User '.Auth::user()->name.' deleted project. Data: '.json_encode($project->toArray()),
            default => 'User '.Auth::user()->name.' performed '.$action.' on project.',
        };

        $this->recordActivity(
            'pm.project.'.$project->id.'.'.$action,
            $message,
            'project.'.$project->id,
        );
    }
}
