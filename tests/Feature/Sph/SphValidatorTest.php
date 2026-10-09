<?php

declare(strict_types=1);

use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Services\Sph\SphValidator;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('a complete SPH has no errors', function () {
    expect(app(SphValidator::class)->errors(ProjectSph::factory()->create()))->toBe([]);
});

test('each rule reports on its tab', function (array $attributes, string $tab, string $key) {
    $errors = app(SphValidator::class)->errors(ProjectSph::factory()->create($attributes));

    expect($errors[$tab] ?? [])->toContain(__('pm::sph.'.$key));
})->with([
    'recipient' => [['recipient_name' => ' '], 'general', 'error_recipient'],
    'need' => [['need' => ''], 'general', 'error_need'],
    'issuer' => [['issuer_id' => null], 'general', 'error_issuer'],
    'signer' => [['signature_id' => null], 'general', 'error_signature'],
    'no items' => [['items' => []], 'items', 'error_items_empty'],
    'unnamed item' => [['items' => [['id' => 'a', 'name' => '', 'description' => '', 'duration' => '', 'price' => 5]]], 'items', 'error_item_name'],
    'negative price' => [['items' => [['id' => 'a', 'name' => 'A', 'description' => '', 'duration' => '', 'price' => -5], ['id' => 'b', 'name' => 'B', 'description' => '', 'duration' => '', 'price' => 10]]], 'items', 'error_price_negative'],
    'zero total' => [['items' => [['id' => 'a', 'name' => 'A', 'description' => '', 'duration' => '', 'price' => 0]]], 'items', 'error_total_zero'],
    'dp out of range' => [['payment_enabled' => true, 'dp_percentage' => 100], 'terms', 'error_dp'],
    'timeline needed' => [['show_timeplan' => true, 'timeline' => ['start_date' => null, 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => []]], 'timeline', 'error_timeline'],
]);

test('timeline is not checked when neither timeline section is shown', function () {
    $sph = ProjectSph::factory()->create(['show_timeplan' => false, 'show_timeline_summary' => false, 'timeline' => ['start_date' => null, 'tasks' => []]]);

    expect(app(SphValidator::class)->errors($sph))->not->toHaveKey('timeline');
});
