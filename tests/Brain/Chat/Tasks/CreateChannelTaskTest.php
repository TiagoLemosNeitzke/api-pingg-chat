<?php

declare(strict_types = 1);

use App\Brain\Chat\Tasks\CreateChannelTask;
use App\Models\Channel;
use App\Models\Workspace;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->workspace = Workspace::factory()->create();
});

it('should be able to create a new channel', function (): void {
    CreateChannelTask::dispatch([
        'short_id'     => 'abc12345',
        'icon'         => '#',
        'name'         => 'valid-name',
        'description'  => 'Something to describe',
        'workspace_id' => $this->workspace->id,
        'is_private'   => false,
        'is_dm'        => false,
    ]);

    assertDatabaseHas('channels', [
        'short_id'     => 'abc12345',
        'icon'         => '#',
        'name'         => 'valid-name',
        'description'  => 'Something to describe',
        'workspace_id' => $this->workspace->id,
        'is_private'   => false,
        'is_dm'        => false,
    ]);
});

it('should return the created channel', function (): void {
    $task = CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'icon'         => '#',
        'name'         => 'valid-name',
        'description'  => 'Something to describe',
        'workspace_id' => $this->workspace->id,
    ]);

    expect($task->channel)->toBeInstanceOf(Channel::class);
});

// ------------------------------------------------------------------------------
// Validations

// --------------------------------------------------
// Short ID

test('short_id should be required', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
        'short_id'     => null,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.required', ['attribute' => 'short id'])
        );
});

test('short_id should be unique', function (): void {
    Channel::factory()->create(['short_id' => 'duplicate']);

    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'duplicate',
        'name'         => 'other-name',
        'workspace_id' => $this->workspace->id,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.unique', ['attribute' => 'short id'])
        );
});

// --------------------------------------------------
// Icon

test('icon max length is 1', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'icon'         => 'ab',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.max.string', ['attribute' => 'icon', 'max' => 1])
        );
});

test('icon can be null', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'icon'         => '',
        'name'         => 'valid-name',
        'description'  => 'Something to describe',
    ]))
        ->not
        ->toThrow(ValidationException::class);
});

// --------------------------------------------------
// Name

test('name should be required', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'icon'         => '',
        'name'         => null,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.required', ['attribute' => 'name'])
        );
});

test('name min length is 3', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'icon'         => '',
        'name'         => 'ab',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.min.string', ['attribute' => 'name', 'min' => 3])
        );
});

test('name max length is 15', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'icon'         => '',
        'name'         => str_repeat('a', 16),
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.max.string', ['attribute' => 'name', 'max' => 15])
        );
});

test('name should be alpha dash', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'icon'         => '',
        'name'         => 'invalid name',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.alpha_dash', ['attribute' => 'name'])
        );
});

test('name should be unique', function (): void {
    Channel::factory()->create(['name' => 'duplicate-name']);

    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'name'         => 'duplicate-name',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.unique', ['attribute' => 'name'])
        );
});

// --------------------------------------------------
// Description

test('description should not be greater than 255', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'icon'         => '',
        'name'         => str_repeat('a', 15),
        'description'  => str_repeat('a', 256),
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.max.string', ['attribute' => 'description', 'max' => 255])
        );
});

// --------------------------------------------------
// Workspace ID

test('workspace_id should be required', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => null,
        'name'         => 'valid-name',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.required', ['attribute' => 'workspace id'])
        );
});

test('workspace_id should exist in workspaces table', function (): void {
    expect(fn () => CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => 99999,
        'name'         => 'valid-name',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.exists', ['attribute' => 'workspace id'])
        );
});

// --------------------------------------------------
// Boolean Fields

test('is_private defaults to false', function (): void {
    $task = CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'name'         => 'valid-name',
    ]);

    expect($task->channel->is_private)->toBeFalse();
});

test('is_dm defaults to false', function (): void {
    $task = CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'name'         => 'valid-name',
    ]);

    expect($task->channel->is_dm)->toBeFalse();
});

test('is_private can be set to true', function (): void {
    $task = CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'name'         => 'valid-name',
        'is_private'   => true,
    ]);

    expect($task->channel->is_private)->toBeTrue();
});

test('is_dm can be set to true', function (): void {
    $task = CreateChannelTask::dispatchSync([
        'short_id'     => 'abc12345',
        'workspace_id' => $this->workspace->id,
        'name'         => 'valid-name',
        'is_dm'        => true,
    ]);

    expect($task->channel->is_dm)->toBeTrue();
});
