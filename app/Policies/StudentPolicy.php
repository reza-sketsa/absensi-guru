<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->teacher;
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->teacher &&
            $student->classroom &&
            $student->classroom->walas_id === $user->teacher->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Student $student): bool
    {
        return $this->view($user, $student);
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->role === 'admin';
    }
}
