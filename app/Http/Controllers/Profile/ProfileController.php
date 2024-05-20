<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Survey\Survey;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::findOrFail(Auth::id());
        return view('profile.index')->with(['user' => $user]);
    }

    public function destroy()
    {
        $user = User::findOrFail(Auth::id());

        if ($user->teams_owned_withArchived()->count()>0){
            return redirect()->back()->with('danger', 'Deleting user only possible if you are no longer own any (archived) team(s)!');
        }

        $user->teams()->withTrashed()->each(function (Team $team) {
            $team->surveys()->withTrashed()->where('user_id', Auth::id())->each (function  (Survey $survey) use($team) {
                $survey->user_id = $team->teamleader()->id;
                $survey->save();
            });
                $team->members()->detach(Auth::id());
        });

        $user->invitations()->withTrashed()->each(function (Team $team) {
            $team->invitations()->detach(Auth::id());
        });

        $user->forceDelete();
        Auth::logout();
        return redirect('/login')->with('success', 'User Deleted Successfully!');
    }

    public function update(Request $request){
        $user = User::findOrFail(Auth::id());

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->name = $data['name'];
        $user->save();
        return redirect()->back()->with('success', 'User Name Updated Successfully');
    }

    public function changepassword(Request $request){
        $user = User::findOrFail(Auth::id());

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        Auth::logout();
        return redirect('/login');
    }
}
