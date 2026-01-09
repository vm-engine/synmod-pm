<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;
use VmEngine\Pm\Models\Client;
use VmEngine\SynAuth\Rules\UsernameRule;

class ClientFormObject extends Form
{
    public ?Client $client = null;

    // Client data
    public ?int $client_category_id = null;

    public string $company = '';

    public string $address = '';

    public string $city = '';

    public string $director = '';

    public string $pic = '';

    public string $phone = '';

    // public array $services = [];

    public string $social_media = '';  // Textarea input

    public int $status = 0;

    public bool $active = true;

    // User data
    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Load existing client data into the form.
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;

        // Load client fields
        $this->client_category_id = $client->client_category_id;
        $this->company = $client->company ?? '';
        $this->address = $client->address ?? '';
        $this->city = $client->city ?? '';
        $this->director = $client->director ?? '';
        $this->pic = $client->pic ?? '';
        $this->phone = $client->phone ?? '';
        // $this->services = $client->services ?? [];
        $this->social_media = implode("\n", $client->social_media ?? []);
        $this->status = $client->status;
        $this->active = $client->active;

        // Load user fields
        $user = $client->user;
        $this->name = $user->name;
        $this->username = $user->username ?? '';
        $this->email = $user->email;
        // Don't load password for security
    }

    /**
     * Get validation rules.
     */
    public function rules(): array
    {
        $userId = $this->client?->user_id;
        $mode = config('synapps.auth.login_credential', 'email');

        $rules = [
            // Client fields
            'company' => 'required|string|max:255',
            'client_category_id' => 'nullable|integer|exists:client_categories,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'director' => 'nullable|string|max:255',
            'pic' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            // 'services' => 'nullable|array',
            // 'services.*' => 'integer',
            'social_media' => 'nullable|string',
            'status' => 'required|integer|in:0,1',
            'active' => 'boolean',

            // User fields (same validation as synapps-auth)
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'.($userId ? ','.$userId : ''),
            ],
        ];

        // Add username validation if required by mode (same as synapps-auth)
        if (in_array($mode, ['username', 'both'], true)) {
            $rules['username'] = [
                'required',
                'string',
                new UsernameRule,
                'unique:users,username'.($userId ? ','.$userId : ''),
            ];
        }

        // Password validation (same as synapps-auth)
        if (! $this->client) {
            // Creating new client - password required
            $rules['password'] = [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        } else {
            // Editing existing client - password optional
            $rules['password'] = [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    protected function messages(): array
    {
        return [
            // User field messages (same as synapps-auth)
            'name.required' => __('synauth::users.validation.name_required'),
            'email.required' => __('synauth::users.validation.email_required'),
            'email.email' => __('synauth::users.validation.email_invalid'),
            'email.unique' => __('synauth::users.validation.email_unique'),
            'username.required' => __('synauth::users.validation.username_required'),
            'username.unique' => __('synauth::users.validation.username_unique'),
            'password.required' => __('synauth::users.validation.password_required'),
            'password.confirmed' => __('synauth::users.validation.password_confirmed'),
        ];
    }

    /**
     * Create a new client with associated user.
     */
    public function store(): void
    {
        $this->validate();

        DB::transaction(function () {
            $UserModel = config('auth.providers.users.model');

            // Create user
            $user = $UserModel::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'email_verified_at' => now(),
                'status' => $this->status === Client::STATUS_ACTIVE && $this->active,
            ]);

            // Assign client role
            $clientRoleId = dbconf('pm.client_role_id');
            if ($clientRoleId) {
                $user->roles()->attach($clientRoleId);
            }

            // Create client
            Client::create([
                'user_id' => $user->id,
                'client_category_id' => $this->client_category_id,
                'company' => $this->company,
                'address' => $this->address,
                'city' => $this->city,
                'director' => $this->director,
                'pic' => $this->pic,
                'phone' => $this->phone,
                'services' => [],
                'social_media' => $this->parseSocialMedia(),
                'status' => $this->status,
                'active' => $this->active,
                'created_by' => auth()->id(),
            ]);
        });

        $this->reset();
    }

    /**
     * Update existing client and associated user.
     */
    public function update(): void
    {
        $this->validate();

        DB::transaction(function () {
            // Update user
            $user = $this->client->user;
            $userData = [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'status' => $this->status === Client::STATUS_ACTIVE && $this->active,
            ];

            // Only update password if provided
            if (filled($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            $user->update($userData);

            // Update client
            $this->client->update([
                'client_category_id' => $this->client_category_id,
                'company' => $this->company,
                'address' => $this->address,
                'city' => $this->city,
                'director' => $this->director,
                'pic' => $this->pic,
                'phone' => $this->phone,
                'services' => [],
                'social_media' => $this->parseSocialMedia(),
                'status' => $this->status,
                'active' => $this->active,
                'updated_by' => auth()->id(),
            ]);
        });
    }

    /**
     * Parse social media textarea into array.
     */
    private function parseSocialMedia(): array
    {
        if (empty($this->social_media)) {
            return [];
        }

        return collect(explode("\n", $this->social_media))
            ->map('trim')
            ->filter()
            ->values()
            ->toArray();
    }
}
