<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Evaluation;

class EvaluationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->teacher;
    }

    public function view(User $user, Evaluation $evaluation): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->teacher &&
            $evaluation->teacher_id === $user->teacher->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->teacher;
    }

    public function update(User $user, Evaluation $evaluation): bool
    {
        return $this->view($user, $evaluation);
    }

    public function delete(User $user, Evaluation $evaluation): bool
    {
        return $this->view($user, $evaluation);
    }
}
