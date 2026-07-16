<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\ClassroomMember;
use App\Models\Mission;
use App\Models\MissionAssignment;
use App\Models\User;

class MissionPolicy
{
    public function view(User $user, Mission $mission): bool
    {
        return $this->canPlay($user);
    }

    public function play(User $user, Mission $mission): bool
    {
        return $this->canPlay($user);
    }

    public function evaluate(User $user, Mission $mission): bool
    {
        return $this->canPlay($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->access, ['guru', 'admin', 'superadmin'], true);
    }

    public function manage(User $user, Mission $mission): bool
    {
        if (! in_array($user->access, ['guru', 'admin', 'superadmin'], true)) {
            return false;
        }

        return in_array($user->access, ['admin', 'superadmin'], true)
            || $mission->created_by === $user->uuid;
    }

    public function viewInClassroom(User $user, Mission $mission, Classroom $classroom): bool
    {
        if (! app(ClassroomPolicy::class)->view($user, $classroom)) {
            return false;
        }

        if (! $mission->isPublished()) {
            return $this->manageInClassroom($user, $classroom);
        }

        return $mission->assignmentFor($classroom) !== null
            || $this->manageInClassroom($user, $classroom);
    }

    public function manageInClassroom(User $user, Classroom $classroom): bool
    {
        return app(ClassroomPolicy::class)->manage($user, $classroom);
    }

    public function playInClassroom(User $user, Mission $mission, Classroom $classroom): bool
    {
        if ($user->access !== 'siswa') {
            return $this->manageInClassroom($user, $classroom);
        }

        if (! $mission->isPublished()) {
            return false;
        }

        $assignment = $mission->assignmentFor($classroom);
        if (! $assignment || ! $assignment->isOpen()) {
            return false;
        }

        return ClassroomMember::where('classroom_id', $classroom->uuid)
            ->where('user_id', $user->uuid)
            ->where('role_in_class', 'siswa')
            ->exists();
    }

    public function viewAnalytics(User $user): bool
    {
        return in_array($user->access, ['guru', 'admin', 'superadmin', 'walikelas'], true);
    }

    public function viewDebriefPanel(User $user): bool
    {
        return in_array($user->access, ['guru', 'admin', 'superadmin', 'walikelas'], true);
    }

    public function reviewReflection(User $user): bool
    {
        return $this->viewDebriefPanel($user);
    }

    private function canPlay(User $user): bool
    {
        return in_array($user->access, ['siswa', 'guru', 'admin', 'superadmin'], true);
    }
}
