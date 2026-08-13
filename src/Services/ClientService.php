<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use VmEngine\Pm\Models\Client;
use VmEngine\SynAuth\Facades\SynAuth;

class ClientService
{
    public function __construct(
        private ClientUserService $userService,
        private FileUploadService $fileService
    ) {}

    /**
     * Create a new client with associated user account.
     */
    public function create(array $clientData, array $userData, ?TemporaryUploadedFile $npwpImage = null): Client
    {
        return DB::transaction(function () use ($clientData, $userData, $npwpImage) {
            // Create user account
            $user = $this->userService->createUser(
                $userData,
                $clientData['status'],
                $clientData['active']
            );

            // Handle NPWP image upload
            $npwpImagePath = $this->fileService->uploadNpwpImage($npwpImage);

            // Create client
            $client = Client::create([
                'user_id' => $user->id,
                'client_category_id' => $clientData['client_category_id'],
                'company' => $clientData['company'],
                'address' => $clientData['address'] ?? null,
                'city' => $clientData['city'] ?? null,
                'director' => $clientData['director'] ?? null,
                'pic' => $clientData['pic'] ?? null,
                'phone' => $clientData['phone'] ?? null,
                'services' => $clientData['services'] ?? [],
                'social_media' => $clientData['social_media'] ?? [],
                'npwp_image' => $npwpImagePath,
                'status' => $clientData['status'],
                'active' => $clientData['active'],
                'created_by' => Auth::id(),
            ]);

            // Log activity
            $this->logActivity('create', $client);

            return $client->fresh();
        });
    }

    /**
     * Update an existing client and associated user account.
     */
    public function update(Client $client, array $clientData, array $userData, ?TemporaryUploadedFile $npwpImage = null): Client
    {
        $oldData = $client->toArray();

        return DB::transaction(function () use ($client, $clientData, $userData, $npwpImage, $oldData) {
            // Update user account
            $this->userService->updateUser(
                $client->user,
                $userData,
                $clientData['status'],
                $clientData['active']
            );

            // Handle NPWP image upload
            $npwpImagePath = $this->fileService->uploadNpwpImage($npwpImage, $client->npwp_image);

            // Update client
            $client->update([
                'client_category_id' => $clientData['client_category_id'],
                'company' => $clientData['company'],
                'address' => $clientData['address'] ?? null,
                'city' => $clientData['city'] ?? null,
                'director' => $clientData['director'] ?? null,
                'pic' => $clientData['pic'] ?? null,
                'phone' => $clientData['phone'] ?? null,
                'services' => $clientData['services'] ?? [],
                'social_media' => $clientData['social_media'] ?? [],
                'npwp_image' => $npwpImagePath,
                'status' => $clientData['status'],
                'active' => $clientData['active'],
                'updated_by' => Auth::id(),
            ]);

            // Log activity
            $this->logActivity('update', $client, $oldData);

            return $client->fresh();
        });
    }

    /**
     * Delete a client and optionally delete the associated user.
     */
    public function delete(Client $client, bool $deleteUser = true): bool
    {
        $clientData = $client->toArray();

        return DB::transaction(function () use ($client, $deleteUser, $clientData) {
            $user = $client->user;

            // Delete associated user first
            if ($deleteUser && $user) {
                $user->delete();
            }

            // Delete NPWP image if exists
            if ($client->npwp_image) {
                $this->fileService->deleteNpwpImage($client->npwp_image);
            }

            // Delete client
            $deleted = $client->delete();

            // Log activity with the data we saved earlier
            SynAuth::logActivity(
                Auth::id(),
                'pm.client.delete',
                'User '.Auth::user()->name.' deleted client data. Data: '.json_encode($clientData),
                'client',
                'pm',
            );

            return $deleted;
        });
    }

    /**
     * Toggle the active status of a client.
     */
    public function toggleActive(Client $client): void
    {
        DB::transaction(function () use ($client) {
            // Toggle client active status
            $client->active = ! $client->active;
            $client->save();

            // Update related user status (only active if client is both active status AND active)
            $client->user->update([
                'status' => $client->status === Client::STATUS_ACTIVE && $client->active,
            ]);

            // Log activity
            SynAuth::logActivity(
                Auth::id(),
                'pm.client.toggle_active',
                'User '.Auth::user()->name.' toggled client active status. Client ID: '.$client->id.' Active: '.($client->active ? 'true' : 'false'),
                'client',
                'pm',
            );
        });
    }

    /**
     * Get paginated client list with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getClientList(array $filters): LengthAwarePaginator
    {
        $allowedSortColumns = ['company', 'status', 'active', 'created_at'];

        $categoryId = $filters['client_category_id'] ?? null;
        $status = isset($filters['status']) ? (string) $filters['status'] : null;
        $active = $filters['active'] ?? null;
        $services = $filters['services'] ?? [];
        $projectCategories = $filters['project_category'] ?? [];
        $q = isset($filters['q']) ? trim((string) $filters['q']) : null;
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDesc = $filters['sort_desc'] ?? true;
        $limit = min((int) ($filters['limit'] ?? 20), 100);

        if (! in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'created_at';
        }

        $query = Client::query();

        if ($categoryId !== null) {
            $query->where('client_category_id', (int) $categoryId);
        }

        if ($status !== null && $status !== '') {
            $query->where('status', (int) $status);
        }

        if ($active !== null && $active !== '') {
            $query->where('active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        if (! empty($services)) {
            $query->byServiceAny(array_map('intval', (array) $services));
        }

        if (! empty($projectCategories)) {
            $query->byProjectCategoryAny(array_map('intval', (array) $projectCategories));
        }

        if ($q !== null && $q !== '') {
            $query->search($q);
        }

        $query->with(['category:id,name'])
            ->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');

        return $query->paginate($limit);
    }

    /**
     * Parse social media textarea into array.
     */
    public function parseSocialMedia(?string $socialMedia): array
    {
        if (empty($socialMedia)) {
            return [];
        }

        return collect(explode("\n", $socialMedia))
            ->map('trim')
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * Log client activity.
     */
    private function logActivity(string $action, Client $client, ?array $oldData = null): void
    {
        $message = match ($action) {
            'create' => 'User '.Auth::user()->name.' created new client. Data: '.json_encode($client->toArray()),
            'update' => 'User '.Auth::user()->name.' updated client data. Old Data: '.json_encode($oldData).' New Data: '.json_encode($client->toArray()),
            'delete' => 'User '.Auth::user()->name.' deleted client. Data: '.json_encode($client->toArray()),
            default => 'User '.Auth::user()->name.' performed '.$action.' on client.',
        };

        SynAuth::logActivity(
            Auth::id(),
            'pm.client.'.$action,
            $message,
            'client',
            'pm',
        );
    }
}
