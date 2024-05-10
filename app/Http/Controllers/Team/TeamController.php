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

        if($team->members->pluck('id')->contains($new_owner->id)){
            return redirect()->back()->with('danger', 'User is not a member of this team');
        }

        $team->user_id = $data['new_teamleader_user_id'];
        $team->save();

        return redirect()->back()->with('success', $team->team_name."'s Team Leader Changed Successfully to: " . $new_owner->name );
    }

    public function destroy(Team $team){

        $this->authorize('isUserTeamLeader', $team);

        // if($team->members->count()>1){
        //     return redirect()->back()->with('warning', "Team still has members");
        // }
        // if($team->surveys()->withTrashed()->count()>0){
        //     return redirect()->back()->with('warning', "Team still has surveys left");
        // }

        // $team->members()->detach(Auth::id());
        // $team->invitations()->detach();

        $deleted = $team->delete();
        $team->surveys()->withTrashed()->each (function  (Survey $survey){
            $survey->delete();
        });


        if (!$deleted) {
            return abort(500);
        }

        return redirect()->back()->with('success', "Team: '" . $team->team_name . "' Successfully Deleted!");
    }
}
