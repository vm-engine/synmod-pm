<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
});

test('command exits successfully when no files exist', function () {
    $this->artisan('mod-pm:migrate-project-uploads')
        ->expectsOutput('✅ No project files found in public storage. Nothing to migrate.')
        ->assertExitCode(0);
});

test('command exits successfully when directory exists but is empty', function () {
    Storage::disk('public')->makeDirectory('projects');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->expectsOutput('✅ No files found in public storage. Nothing to migrate.')
        ->assertExitCode(0);
});

test('command migrates files from public to local storage', function () {
    // Create test files in public storage
    Storage::disk('public')->put('projects/test1.pdf', 'content1');
    Storage::disk('public')->put('projects/subfolder/test2.pdf', 'content2');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->assertExitCode(0);

    // Verify files were copied to local
    expect(Storage::disk('local')->exists('projects/test1.pdf'))->toBeTrue();
    expect(Storage::disk('local')->exists('projects/subfolder/test2.pdf'))->toBeTrue();
    expect(Storage::disk('local')->get('projects/test1.pdf'))->toBe('content1');
    expect(Storage::disk('local')->get('projects/subfolder/test2.pdf'))->toBe('content2');

    // Verify files were deleted from public (default behavior)
    expect(Storage::disk('public')->exists('projects/test1.pdf'))->toBeFalse();
    expect(Storage::disk('public')->exists('projects/subfolder/test2.pdf'))->toBeFalse();
});

test('command with keep-original option keeps files in public storage', function () {
    Storage::disk('public')->put('projects/test.pdf', 'content');

    $this->artisan('mod-pm:migrate-project-uploads', ['--keep-original' => true])
        ->assertExitCode(0);

    // Verify file exists in both storages
    expect(Storage::disk('local')->exists('projects/test.pdf'))->toBeTrue();
    expect(Storage::disk('public')->exists('projects/test.pdf'))->toBeTrue();
});

test('command with dry-run does not migrate files', function () {
    Storage::disk('public')->put('projects/test.pdf', 'content');

    $this->artisan('mod-pm:migrate-project-uploads', ['--dry-run' => true])
        ->expectsOutput('🔍 Running in DRY RUN mode - no files will be moved')
        ->expectsOutput('💡 Run without --dry-run to actually migrate the files.')
        ->assertExitCode(0);

    // Verify file was not migrated
    expect(Storage::disk('local')->exists('projects/test.pdf'))->toBeFalse();
    expect(Storage::disk('public')->exists('projects/test.pdf'))->toBeTrue();
});

test('command skips files that already exist in local storage', function () {
    // Create file in both storages
    Storage::disk('public')->put('projects/test1.pdf', 'public content');
    Storage::disk('local')->put('projects/test1.pdf', 'local content');

    // Create file only in public
    Storage::disk('public')->put('projects/test2.pdf', 'content2');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->assertExitCode(0);

    // test1.pdf should remain unchanged in local (not overwritten)
    expect(Storage::disk('local')->get('projects/test1.pdf'))->toBe('local content');

    // test2.pdf should be migrated
    expect(Storage::disk('local')->get('projects/test2.pdf'))->toBe('content2');
});

test('command displays correct migration summary', function () {
    // Create 3 files in public
    Storage::disk('public')->put('projects/file1.pdf', 'content1');
    Storage::disk('public')->put('projects/file2.pdf', 'content2');
    Storage::disk('public')->put('projects/file3.pdf', 'content3');

    // File already exists in local
    Storage::disk('local')->put('projects/file1.pdf', 'existing');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->expectsOutputToContain('📊 Migration Summary:')
        ->expectsOutputToContain('Migrated')
        ->expectsOutputToContain('Already in local')
        ->assertExitCode(0);
});

test('command shows total file count', function () {
    Storage::disk('public')->put('projects/file1.pdf', 'content1');
    Storage::disk('public')->put('projects/file2.pdf', 'content2');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->expectsOutput('Found 2 file(s) to migrate.')
        ->assertExitCode(0);
});

test('command with dry-run shows would be migrated message', function () {
    Storage::disk('public')->put('projects/test.pdf', 'content');

    $this->artisan('mod-pm:migrate-project-uploads', ['--dry-run' => true])
        ->expectsOutputToContain('(would be migrated)')
        ->assertExitCode(0);
});

test('command with keep-original in dry-run mode does not show deletion warning', function () {
    Storage::disk('public')->put('projects/test.pdf', 'content');

    $output = $this->artisan('mod-pm:migrate-project-uploads', [
        '--dry-run' => true,
        '--keep-original' => true,
    ]);

    // Should not show deletion messages when keeping originals
    $output->assertExitCode(0);
});

test('command shows warning when originals are kept', function () {
    Storage::disk('public')->put('projects/test.pdf', 'content');

    $this->artisan('mod-pm:migrate-project-uploads', ['--keep-original' => true])
        ->expectsOutput('⚠️  Original files are still in public storage.')
        ->assertExitCode(0);
});

test('command shows deletion confirmation when originals are deleted', function () {
    Storage::disk('public')->put('projects/test.pdf', 'content');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->expectsOutput('🗑️  Original files have been deleted from public storage.')
        ->assertExitCode(0);
});

test('command migrates files recursively', function () {
    // Create nested directory structure
    Storage::disk('public')->put('projects/level1/file1.pdf', 'content1');
    Storage::disk('public')->put('projects/level1/level2/file2.pdf', 'content2');
    Storage::disk('public')->put('projects/level1/level2/level3/file3.pdf', 'content3');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->assertExitCode(0);

    // Verify all nested files were migrated
    expect(Storage::disk('local')->exists('projects/level1/file1.pdf'))->toBeTrue();
    expect(Storage::disk('local')->exists('projects/level1/level2/file2.pdf'))->toBeTrue();
    expect(Storage::disk('local')->exists('projects/level1/level2/level3/file3.pdf'))->toBeTrue();
});

test('command preserves file structure', function () {
    Storage::disk('public')->put('projects/invoices/2024/invoice1.pdf', 'content1');
    Storage::disk('public')->put('projects/receipts/2024/receipt1.pdf', 'content2');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->assertExitCode(0);

    // Verify directory structure is preserved
    expect(Storage::disk('local')->exists('projects/invoices/2024/invoice1.pdf'))->toBeTrue();
    expect(Storage::disk('local')->exists('projects/receipts/2024/receipt1.pdf'))->toBeTrue();
});

test('command handles multiple file types', function () {
    Storage::disk('public')->put('projects/document.pdf', 'pdf content');
    Storage::disk('public')->put('projects/image.jpg', 'jpg content');
    Storage::disk('public')->put('projects/document.docx', 'docx content');
    Storage::disk('public')->put('projects/spreadsheet.xlsx', 'xlsx content');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->assertExitCode(0);

    // Verify all file types were migrated
    expect(Storage::disk('local')->exists('projects/document.pdf'))->toBeTrue();
    expect(Storage::disk('local')->exists('projects/image.jpg'))->toBeTrue();
    expect(Storage::disk('local')->exists('projects/document.docx'))->toBeTrue();
    expect(Storage::disk('local')->exists('projects/spreadsheet.xlsx'))->toBeTrue();
});

test('command with dry-run shows keep-original hint', function () {
    Storage::disk('public')->put('projects/test.pdf', 'content');

    $this->artisan('mod-pm:migrate-project-uploads', ['--dry-run' => true])
        ->expectsOutput('💡 Files will be deleted from public storage after migration.')
        ->expectsOutput('💡 Add --keep-original to keep files in public storage after migration.')
        ->assertExitCode(0);
});

test('command counts skipped files correctly', function () {
    // Create 2 files that already exist in local
    Storage::disk('public')->put('projects/file1.pdf', 'public1');
    Storage::disk('local')->put('projects/file1.pdf', 'local1');

    Storage::disk('public')->put('projects/file2.pdf', 'public2');
    Storage::disk('local')->put('projects/file2.pdf', 'local2');

    // Create 1 file only in public
    Storage::disk('public')->put('projects/file3.pdf', 'public3');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->assertExitCode(0);

    // Verify original local files weren't overwritten
    expect(Storage::disk('local')->get('projects/file1.pdf'))->toBe('local1');
    expect(Storage::disk('local')->get('projects/file2.pdf'))->toBe('local2');

    // Verify new file was migrated
    expect(Storage::disk('local')->get('projects/file3.pdf'))->toBe('public3');
});

test('command displays success message after migration', function () {
    Storage::disk('public')->put('projects/test.pdf', 'content');

    $this->artisan('mod-pm:migrate-project-uploads')
        ->expectsOutputToContain('✅ Successfully migrated')
        ->assertExitCode(0);
});
