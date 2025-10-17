<?php

declare(strict_types = 1);

use App\Brain\Chat\Processes\SendWorkspaceInvitationProcess;
use App\Brain\Chat\Tasks\CheckIfUserCanISendInviteToWorkspaceTask;
use App\Brain\Chat\Tasks\CreateWorkspaceInviteTask;
use App\Brain\Chat\Tasks\SendEmailWithTheWorkspaceInvitationTask;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseHas;

test('check list of tasks', function (): void {
    $process = new SendWorkspaceInvitationProcess();

    expect($process->getTasks())
        ->toBe([
            CheckIfUserCanISendInviteToWorkspaceTask::class,
            CreateWorkspaceInviteTask::class,
            SendEmailWithTheWorkspaceInvitationTask::class,
        ]);
});

test('check if it works', function (): void {
    $owner = User::factory()->create();
    Workspace::factory()->create(['owner_id' => $owner->id]);
    $johnDoe = User::factory()->create(['username' => 'johndoe']);

    Notification::fake();

    SendWorkspaceInvitationProcess::dispatchSync([
        'workspace_id' => 1,
        'invited_by'   => 1,
        'username'     => 'johndoe',
    ]);

    assertDatabaseHas('workspace_invites', [
        'workspace_id' => 1,
        'invited_by'   => 1,
        'username'     => 'johndoe',
    ]);

    Notification::assertSentTo(
        $johnDoe,
        App\Notifications\WorkspaceInvitationNotification::class
    );
});
