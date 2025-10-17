<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Tasks;

use App\Models\User;
use Brain\Task;
use Illuminate\Validation\ValidationException;

/**
 * Task CheckIfUserCanISendInviteToWorkspaceTask
 *
 * @property-read int $workspace_id
 * @property-read int $invited_by
 */
class CheckIfUserCanISendInviteToWorkspaceTask extends Task
{
    public function handle(): self
    {
        if (User::find($this->invited_by)
            ->myWorkspaces()
            ->where('id', $this->workspace_id)
            ->doesntExist()) {
            throw ValidationException::withMessages([
                'invited_by' => ['You are not the owner of this workspace.'],
            ]);
        }

        return $this;
    }
}
