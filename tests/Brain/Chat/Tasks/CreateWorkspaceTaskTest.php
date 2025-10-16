<?php

declare(strict_types = 1);

use App\Brain\Chat\Tasks\CreateWorkspaceTask;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('should be able to create a new workspace', function (): void {
    CreateWorkspaceTask::dispatch([
        'owner_id'    => $this->user->id,
        'icon'        => '',
        'name'        => 'valid-name',
        'description' => 'Something to describe',
    ]);

    assertDatabaseHas('workspaces', [
        'owner_id'    => $this->user->id,
        'icon'        => '',
        'name'        => 'valid-name',
        'description' => 'Something to describe',
    ]);
});

it('should return the created workspace', function (): void {
    $task = CreateWorkspaceTask::dispatchSync([
        'owner_id'    => $this->user->id,
        'icon'        => '',
        'name'        => 'valid-name',
        'description' => 'Something to describe',
    ]);

    expect($task->workspace)->toBeInstanceOf(Workspace::class);
});

// ------------------------------------------------------------------------------
// Validations

// --------------------------------------------------
// Icon

test('icon max length is 1', function (): void {
    expect(fn () => CreateWorkspaceTask::dispatchSync([
        'owner_id' => $this->user->id,
        'icon'     => 'ab',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.max.string', ['attribute' => 'icon', 'max' => 1])
        );
});

test('icon can be null', function (): void {
    expect(fn () => CreateWorkspaceTask::dispatchSync([
        'owner_id'    => $this->user->id,
        'icon'        => '',
        'name'        => 'valid-name',
        'description' => 'Something to describe',
    ]))
        ->not
        ->toThrow(ValidationException::class);
});

// --------------------------------------------------
// Name

test('name should be required', function (): void {
    expect(fn () => CreateWorkspaceTask::dispatchSync([
        'owner_id' => $this->user->id,
        'icon'     => '',
        'name'     => null,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.required', ['attribute' => 'name'])
        );
});

test('name min length is 3', function (): void {
    expect(fn () => CreateWorkspaceTask::dispatchSync([
        'owner_id' => $this->user->id,
        'icon'     => '',
        'name'     => 'ab',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.min.string', ['attribute' => 'name', 'min' => 3])
        );
});

test('name max length is 15', function (): void {
    expect(fn () => CreateWorkspaceTask::dispatchSync([
        'owner_id' => $this->user->id,
        'icon'     => '',
        'name'     => str_repeat('a', 16),
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.max.string', ['attribute' => 'name', 'max' => 15])
        );
});

test('name should be alpha dash', function (): void {
    expect(fn () => CreateWorkspaceTask::dispatchSync([
        'owner_id' => $this->user->id,
        'icon'     => '',
        'name'     => 'invalid name',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.alpha_dash', ['attribute' => 'name'])
        );
});

test('description should not be greater than 255', function (): void {
    expect(fn () => CreateWorkspaceTask::dispatchSync([
        'owner_id'    => $this->user->id,
        'icon'        => '',
        'name'        => str_repeat('a', 15),
        'description' => str_repeat('a', 256),
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.max.string', ['attribute' => 'description', 'max' => 255])
        );
});
