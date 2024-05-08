<?php

namespace App\Policies\Team;

use App\Models\User;
use App\Models\Team\Team;
use Illuminate\Support\Facades\Auth;

class TeamPolicy
{
    /**
     * Create a new policy instance.
     */
    // public function __construct()
    // {
    //     //
    // }


    public function isUserTeamLeader(User $user, Team $team){
        return $user->id === $team->user_id;
    }

    public function userIsTeamMember(User $user, Team $team){
        return $team->members->pluck('id')->contains($user->id);
    }

    public function invitationBelongsToUser(User $user, Team $team){
        return $team->invitations->pluck('id')->contains($user->id);
    }

}
