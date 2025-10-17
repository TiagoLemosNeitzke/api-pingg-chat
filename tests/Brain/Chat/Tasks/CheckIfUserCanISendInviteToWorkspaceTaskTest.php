<?php

declare(strict_types = 1);

use App\Brain\Chat\Tasks\CheckIfUserCanISendInviteToWorkspaceTask;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Validation\ValidationException;

test('check task', function (): void {
    $user      = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);

    expect(fn () => CheckIfUserCanISendInviteToWorkspaceTask::dispatchSync([
        'workspace_id' => $workspace->id,
        'invited_by'   => $user->id,
    ]))->not->toThrow(ValidationException::class);
});

test('check task fails when user is not owner', function (): void {
    $owner     = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $owner->id]);
    $otherUser = User::factory()->create();

    expect(fn () => CheckIfUserCanISendInviteToWorkspaceTask::dispatchSync([
        'workspace_id' => $workspace->id,
        'invited_by'   => $otherUser->id,
    ]))->toThrow(ValidationException::class, 'You are not the owner of this workspace.');
});
