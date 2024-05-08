<?php

namespace App\Http\Controllers\Team;

use Illuminate\Http\Request;
use App\Models\Team\Team;
use App\Models\Survey\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class TeamUserController extends Controller
{
    // public function index(Team $team)
    // {
    // }

    public function invitation(Request $request, Team $team){
        if($team===null){
            return redirect()->back()->with('warning', "Team has been delelted");
        }

        $this->authorize('isUserTeamLeader', $team);

        $data = $request->validate([
            'email_'.$team->id => ['required', 'string', 'email', Rule::in(User::all()->pluck('email'))],
        ]);

        $user = User::where('email', $data['email_'.$team->id])->first();

        if($team->members->pluck('id')->contains($user->id)){
            return redirect()->back()->with('warning', "User is already a member");
        }
        if($team->invitations->pluck('id')->contains($user->id)){
            return redirect()->back()->with('warning', "User has already been invited");
        }

        $team->members()->attach($user->id);

        return redirect()->back()->with('success', 'Invitation Successfully Sent');
    }

    public function cancel_invitation(Team $team, String $user){
        if($team===null){
            return redirect()->back()->with('warning', "Team has been delelted");
        }
        $this->authorize('isUserTeamLeader', $team);

        $user = User::findOrFail($user);


        if(!($team->invitations->pluck('id')->contains($user->id))){
            redirect()->back()->with('warning', "User has not yet been invited");
        }
        if(($team->members->pluck('id')->contains($user->id))){
            redirect()->back()->with('warning', "Invitation already accepted!");
        }

        $team->invitations()->detach($user->id);

        return redirect()->back()->with('success', 'Invitation Successfully withdrawn');

    }

    public function accept(Team $team)
    {
        $user_id = Auth::id();
        if($team===null){
            return redirect()->back()->with('warning', "Team has been delelted");
        }

        if( $this->can('invitationBelongsToUser', $team)){
            $team->invitations()->detach($user_id);
            $team->members()->attach($user_id, ['status' => 'accepted']);

            return redirect()->back()->with('success', 'Team invitation successfully accepted: ' . $team->team_name);
        }else{
            return redirect()->back()->with('warning', 'Team invitation has been withdrawn: ' . $team->team_name);
        }

    }


    public function decline(Team $team)
    {
        if($team===null){
            return redirect()->back()->with('warning', "Team has been delelted");
        }

        $user_id = Auth::id();
        $this->authorize('invitationBelongsToUser', $team);

        if(!($team->invitations->pluck('id')->contains($user_id))){
            redirect()->back()->with('warning', "Team invitation has been withdrawn");
        }
        if(($team->members->pluck('id')->contains($user_id))){
            redirect()->back()->with('warning', "You are already a team member!");
        }

        $team->invitations()->detach($user_id);

        return redirect()->back()->with('success', 'Team invitation successfully declined ' . $team->team_name);
    }

    public function leaveTeam(Team $team)
    {
        $user = User::findOrFail(Auth::id()) ;
        $this->authorize('userIsTeamMember', $team);

        //Team Leader can't leave
        if($team->user_id === $user->id){
            return abort(403);
        }

        $user->surveys()->withTrashed()->where('team_id', $team->id)->each (function  (Survey $survey) use($team) {
            $survey->user_id = $team->user_id;
            $survey->save();
        });

        $team->members()->detach($user->id);

        return redirect()->back()->with('success', 'Team: ' . $team->team_name . ' left successfully');
    }

    public function kick(Team $team, User $user){

        $this->authorize('isUserTeamLeader', $team);

        if($team->user_id != Auth::id()){
            return abort(403, "Only a team's owner can preform this action");
        }

        if(!($team->members->pluck('id')->contains($user->id))){
            return abort(403, 'The User about to be kicked is not even a member of the team');
        }

        $user->surveys()->withTrashed()->where('team_id', $team->id)->each (function  (Survey $survey) use($team) {
            $survey->user_id = $team->user_id;
            $survey->save();
        });

        $team->members()->detach($user->id);

        return redirect()->back()->with('success', $team->team_name . "'s User Kicked Successfully: ". $user->name);
    }
}
