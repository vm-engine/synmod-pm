<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form;

use Illuminate\Validation\Rules\Password;
use Livewire\Form;
use Livewire\WithFileUploads;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Services\ClientService;
use VmEngine\SynAuth\Rules\UsernameRule;

class ClientFormObject extends Form
{
    use WithFileUploads;

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

    public $npwp_image; // Uploaded file

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
        $oldUser = $this->client?->user;
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
            'npwp_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:512',
        ];

        // Add username validation if required by mode (same as synapps-auth)
        if (in_array($mode, ['username', 'both'], true)) {
            if (! $this->client) {
                // Creating new client - username required
                $rules['username'] = ['required', 'string', new UsernameRule, 'unique:users,username'];
            } elseif ($this->username !== '') {
                // Updating existing client with non-empty username
                $rules['username'] = ['string', new UsernameRule];
                if ($oldUser && $oldUser->username !== $this->username) {
                    // Updating existing client with username change
                    $rules['username'][] = 'unique:users,username';
                }
            }
            // else: updating with empty username - don't validate
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
        } elseif ($this->password !== '') {
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

        $clientService = app(ClientService::class);

        $clientData = [
            'client_category_id' => $this->client_category_id,
            'company' => $this->company,
            'address' => $this->address,
            'city' => $this->city,
            'director' => $this->director,
            'pic' => $this->pic,
            'phone' => $this->phone,
            'services' => [],
            'social_media' => $clientService->parseSocialMedia($this->social_media),
            'status' => $this->status,
            'active' => $this->active,
        ];

        $userData = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
        ];

        $clientService->create($clientData, $userData, $this->npwp_image);

        $this->reset();
    }

    /**
     * Update existing client and associated user.
     */
    public function update(): void
    {
        $this->validate();

        $clientService = app(ClientService::class);

        $clientData = [
            'client_category_id' => $this->client_category_id,
            'company' => $this->company,
            'address' => $this->address,
            'city' => $this->city,
            'director' => $this->director,
            'pic' => $this->pic,
            'phone' => $this->phone,
            'services' => [],
            'social_media' => $clientService->parseSocialMedia($this->social_media),
            'status' => $this->status,
            'active' => $this->active,
        ];

        $userData = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
        ];

        $this->client = $clientService->update($this->client, $clientData, $userData, $this->npwp_image);
    }
}
