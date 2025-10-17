<?php

declare(strict_types = 1);

use App\Brain\Chat\Tasks\CheckIfUserHasAccessToTheWorkspaceTask;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Validation\ValidationException;

it('should not throw an error if the user is in the workspace', function (): void {
    $user      = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id);

    expect(fn () => CheckIfUserHasAccessToTheWorkspaceTask::dispatch([
        'user'         => $user,
        'workspace_id' => $workspace->id,
    ]))->not->toThrow(ValidationException::class);
});

it('should throw an error if the user is not in the workspace', function (): void {
    $user      = User::factory()->create();
    $workspace = Workspace::factory()->create();

    expect(fn () => CheckIfUserHasAccessToTheWorkspaceTask::dispatch([
        'user'         => $user,
        'workspace_id' => $workspace->id,
    ]))->toThrow(ValidationException::class);
});
