<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Tasks;

use App\Models\WorkspaceInvite;
use Brain\Task;

/**
 * Task CreateWorkspaceInviteTask
 *
 * @property-read string $workspace_id
 * @property-read string $invited_by
 * @property-read string $username
 *
 * @property WorkspaceInvite $invite
 */
class CreateWorkspaceInviteTask extends Task
{
    public function rules(): array
    {
        return [
            'workspace_id' => ['required', 'integer', 'exists:workspaces,id'],
            'invited_by'   => ['required', 'integer', 'exists:users,id'],
            'username'     => ['required', 'string', 'max:20', 'exists:users,username'],
        ];
    }

    public function handle(): self
    {
        $this->invite = WorkspaceInvite::query()
            ->create([
                'workspace_id' => $this->workspace_id,
                'invited_by'   => $this->invited_by,
                'username'     => $this->username,
            ]);

        return $this;
    }
}
