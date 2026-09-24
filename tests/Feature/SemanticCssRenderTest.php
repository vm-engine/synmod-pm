<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\Signature;
use VmEngine\SynAuth\Models\UserActivity;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

it('has no scaffold index page that dumps config', function () {
    expect(Route::has('backend.pm.index'))->toBeFalse()
        ->and(view()->exists('pm::livewire.index'))->toBeFalse();
});

dataset('pm phase 1 views', [
    'client-list' => ['pm::client-list', [],
        ['syn-panel', 'syn-search-box', 'syn-per-page-selector', 'syn-badge', 'datatable-col-actions', 'syn-modal-header'],
        ['rounded-2xl border border-gray-200', 'shadow-theme-sm rounded-sm p-1', 'mb-2 block text-sm font-medium']],
    'invoice-list' => ['pm::project.invoice-list', [],
        ['syn-panel', 'syn-search-box', 'syn-per-page-selector', 'syn-field-caption', 'syn-icon-dropdown', 'datatable-col-actions'],
        ['class="icon-dropdown', 'text-[10px] font-medium uppercase', 'rounded-2xl border border-gray-200']],
    'project-list (empty)' => ['pm::project.project-list', ['q' => 'no-such-project'],
        ['syn-panel', 'syn-search-box', 'syn-per-page-select', 'syn-empty-state'],
        ['rounded-2xl border border-gray-200', 'shadow-theme-sm rounded-sm p-1']],
    'division (empty)' => ['pm::master.division', [],
        ['syn-panel', 'syn-panel-body', 'syn-row-muted', 'syn-empty-state', 'datatable-col-actions'],
        ['!bg-gray-50/50', 'rounded-2xl border border-gray-200'], false],
    'project-category (empty)' => ['pm::master.project-category', [],
        ['syn-panel', 'syn-row-muted', 'syn-empty-state', 'syn-modal-header', 'syn-modal-footer'],
        ['!bg-gray-50/50', 'btn-outline'], false],
    'client-category (empty)' => ['pm::master.client-category', [],
        ['syn-panel', 'syn-row-muted', 'syn-empty-state'],
        ['!bg-gray-50/50', 'rounded-2xl border border-gray-200'], false],
    'issuer-list' => ['pm::master.issuer-list', [],
        ['syn-panel', 'syn-search-box', 'syn-media-card', 'syn-media-card-actions'],
        ['btn-sm', 'rounded-2xl border border-gray-200']],
    'signature-list' => ['pm::master.signature-list', [],
        ['syn-panel', 'syn-search-box', 'syn-media-card', 'syn-media-card-actions'],
        ['btn-sm', 'rounded-2xl border border-gray-200']],
    'bank-account-list' => ['pm::master.bank-account-list', [],
        ['syn-panel', 'syn-search-box', 'syn-media-card', 'syn-media-card-actions'],
        ['btn-sm', 'rounded-2xl border border-gray-200']],
    'issuer-form' => ['pm::master.issuer-form', [],
        ['syn-modal-header', 'syn-modal-body', 'syn-modal-footer', 'form-help'],
        ['border-b border-gray-200 p-5', 'mt-1 text-xs text-gray-500']],
    'bank-account-form' => ['pm::master.bank-account-form', [],
        ['syn-modal-header', 'syn-modal-body', 'syn-modal-footer'],
        ['border-b border-gray-200 p-5']],
    'signature-form' => ['pm::master.signature-form', [],
        ['syn-modal-header', 'syn-modal-body', 'syn-modal-footer'],
        ['border-b border-gray-200 p-5']],
    'invoice-form' => ['pm::project.invoice-form', [],
        ['syn-modal-header', 'syn-modal-body', 'syn-modal-footer', 'form-help'],
        ['border-b border-gray-200 p-5', 'mt-1 text-xs text-gray-500']],
    'client-form' => ['pm::client-form', [],
        ['form-help', 'syn-alert'],
        ['mt-1 text-xs text-gray-500 dark:text-gray-400', 'rounded-md bg-yellow-50 p-4']],
    'project-form' => ['pm::project.project-form', [],
        ['form-help'],
        ['<small class="text-gray-500">']],
    'report project-document (empty)' => ['pm::report.project-document', [],
        ['syn-panel', 'syn-panel-body', 'syn-empty-state'],
        ['rounded-2xl border border-gray-200'], false],
]);

it('renders phase 1 views with semantic markup', function (string $component, array $params, array $mustSee, array $mustNotSee, bool $seed = true) {
    if ($seed) {
        Client::factory()->create(['status' => Client::STATUS_PROSPECT]);
        ProjectInvoice::factory()->create();
        DocumentIssuer::factory()->create();
        Signature::factory()->create();
        BankAccount::factory()->create();
    }

    $test = Livewire::test($component)->assertOk();
    foreach ($params as $property => $value) {
        $test->set($property, $value);
    }

    foreach ($mustSee as $html) {
        $test->assertSeeHtml($html);
    }
    foreach ($mustNotSee as $html) {
        $test->assertDontSeeHtml($html);
    }
})->with('pm phase 1 views');

it('renders the activity empty state', function () {
    $project = Project::factory()->create();

    Livewire::test('pm::project.activity', ['projectId' => $project->id])
        ->assertOk()
        ->assertSeeHtml('syn-empty-state');
});

it('renders activity with the synapse per-page selector and detail modal chrome', function () {
    $project = Project::factory()->create();
    $activities = UserActivity::factory()->count(11)->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
    ]);

    Livewire::test('pm::project.activity', ['projectId' => $project->id])
        ->call('showDetail', $activities->first()->id)
        ->assertSeeHtml('syn-per-page-selector')
        ->assertSeeHtml('syn-modal-header')
        ->assertSeeHtml('syn-modal-footer')
        ->assertDontSeeHtml('shadow-theme-sm rounded-sm p-1');
});

it('renders document components with synapse panel and modal chrome', function (string $component, Documents $type) {
    $project = Project::factory()->create();

    Livewire::test($component, ['project' => $project, 'documentType' => $type, 'title' => 'Docs'])
        ->assertOk()
        ->assertSeeHtml('syn-panel')
        ->assertSeeHtml('syn-panel-body')
        ->assertSeeHtml('syn-empty-state')
        ->assertSeeHtml('syn-modal-header')
        ->assertSeeHtml('syn-modal-footer')
        ->assertDontSeeHtml('btn-outline')
        ->assertDontSeeHtml('btn-sm')
        ->assertDontSeeHtml('rounded-2xl border border-gray-200');
})->with([
    'versioned' => ['pm::project.versioned-document', Documents::SPK],
    'multiple' => ['pm::project.multiple-document', Documents::INVOICE],
]);

it('renders the document manager with the synapse search box', function () {
    $project = Project::factory()->create();

    Livewire::test('pm::project.document-manager', ['project' => $project])
        ->assertOk()
        ->assertSeeHtml('syn-search-box');
});

it('renders the project document report when a document creator no longer exists', function () {
    $project = Project::factory()->create(['year' => (int) date('Y')]);
    $document = ProjectDocument::factory()->create(['project_id' => $project->id]);
    // Mirrors ON DELETE SET NULL after the uploader is deleted (HasCreator refills created_by on create).
    ProjectDocument::whereKey($document->id)->update(['created_by' => null]);

    Livewire::test('pm::report.project-document')
        ->assertOk()
        ->assertSee(__('pm::project_document.unknown'));
});
