<?php

declare(strict_types = 1);

use App\Brain\Chat\Tasks\CreateWorkspaceInviteTask;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    $this->owner       = User::factory()->create();
    $this->workspace   = Workspace::factory()->create(['owner_id' => $this->owner->id]);
    $this->invitedUser = User::factory()->create(['username' => 'janedoe']);
});

it('should create a Workspace Invite', function (): void {
    CreateWorkspaceInviteTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => $this->owner->id,
        'username'     => 'janedoe',
    ]);

    assertDatabaseHas('workspace_invites', [
        'workspace_id' => $this->workspace->id,
        'invited_by'   => $this->owner->id,
        'username'     => 'janedoe',
    ]);
});

// -----------------------------------------------------------------
// Validations

// 'workspace_id' => ['required', 'integer', 'exists:workspaces,id'],
// 'invited_by'   => ['required', 'integer', 'exists:users,id'],
// 'username'     => ['required', 'string', 'max:20', 'exists:users,username'],

test('workspace_id is required', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync([]))
        ->toThrow(
            ValidationException::class,
            __('validation.required', ['attribute' => 'workspace id'])
        );
});

test('workspace_id must be an integer', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync(['workspace_id' => 'abc']))
        ->toThrow(
            ValidationException::class,
            __('validation.integer', ['attribute' => 'workspace id'])
        );
});

test('workspace_id must exist in workspaces table', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync(['workspace_id' => 9999]))
        ->toThrow(
            ValidationException::class,
            __('validation.exists', ['attribute' => 'workspace id'])
        );
});

test('invited_by is required', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.required', ['attribute' => 'invited by'])
        );
});

test('invited_by must be an integer', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => 'abc',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.integer', ['attribute' => 'invited by'])
        );
});

test('invited_by must exist in users table', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => 9999,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.exists', ['attribute' => 'invited by'])
        );
});

test('username is required', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => $this->owner->id,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.required', ['attribute' => 'username'])
        );
});

test('username must be a string', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => $this->owner->id,
        'username'     => 12345,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.string', ['attribute' => 'username'])
        );
});

test('username must not exceed maximum length', function (): void {
    $longUsername = str_repeat('a', 21);

    expect(fn () => CreateWorkspaceInviteTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => $this->owner->id,
        'username'     => $longUsername,
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.max.string', ['attribute' => 'username', 'max' => 20])
        );
});

test('username must exist in users table', function (): void {
    expect(fn () => CreateWorkspaceInviteTask::dispatchSync([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => $this->owner->id,
        'username'     => 'nonexistentuser',
    ]))
        ->toThrow(
            ValidationException::class,
            __('validation.exists', ['attribute' => 'username'])
        );
});
