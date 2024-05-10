<?php

namespace App\Http\Controllers\Team;

use Illuminate\Http\Request;
use App\Models\Team\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Survey\Survey;

class TeamController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('team.index')->with(['user' => $user]);
    }

    public function archived()
    {
        $user = Auth::user();
        return view('team.archived')->with(['user' => $user]);
    }

    public function store(Request $request){
        $data = $request->validate([
            'team_name' => ['nullable', 'string', 'max:255'],
        ]);
        if($data['team_name']===null){$data['team_name'] = Auth::User()->name . "'s Team";}
        $data['user_id'] = Auth::id();
        $team = Team::create($data);

        $team->members()->attach(Auth::id(), ['status' => 'accepted']);
        return redirect()->back()->with('success', 'Team Created Successfully');
    }

    public function update(Request $request, Team $team){
        $this->authorize('isUserTeamLeader', $team);

        $data = $request->validate([
            'team_name' => ['nullable', 'string', 'max:255'],
        ]);

        if($data['team_name']===null){$data['team_name'] = Auth::User()->name . "'s Team";}

        $team->team_name = $data['team_name'];
        $team->save();
        return redirect()->back()->with('success', 'Team Details Updated Successfully');
    }

    public function giveawayteamleadership(Request $request, Team $team){

        $this->authorize('isUserTeamLeader', $team);

        $data = $request->validate([
            'new_teamleader_user_id' => ['required', 'string', Rule::in($team->members->pluck('id'))],
        ]);
        $new_owner = User::findOrFail($data['new_teamleader_user_id']);

        $team->user_id = $data['new_teamleader_user_id'];
        $team->save();

        return redirect()->back()->with('success', $team->team_name."'s Team Leader Changed Successfully to: " . $new_owner->name );
    }

    public function destroy(Team $team){

        $this->authorize('isUserTeamLeader', $team);

        $deleted = $team->delete();

        if (!$deleted) {
            return abort(500);
        }

        return redirect()->back()->with('success', "Team: '" . $team->team_name . "' successfully archived!");
    }

    public function restore(String $team){
        $team = Team::onlyTrashed()->findOrFail($team);
        $this->authorize('isUserTeamLeader', $team);

        $team->restore();

        return redirect()->back()->with('success', "Team: '" . $team->team_name . "' successfully restored!");
    }

    public function forcedelete(String $team){

        $team = Team::onlyTrashed()->findOrFail($team);

        $this->authorize('isUserTeamLeader', $team);

        $forcedelete = $team->forceDelete();

        if (!$forcedelete) {
            return abort(500);
        }

        return redirect()->back()->with('success', "Team: '" . $team->team_name . "' Permanently Deleted!");
    }
}
