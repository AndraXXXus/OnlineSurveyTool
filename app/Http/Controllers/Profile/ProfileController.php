<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('profile.index')->with(['user' => $user]);
    }

    public function destroy()
    {
        $user = User::findOrFail(Auth::id());

        if ($user->teams->count()>0){
            return redirect()->back()->with('warning', 'Delete only possible if you no longer own teams!');
        }
        if($user->surveys()->withTrashed()->count()>0){
            return redirect()->back()->with('warning', 'Delete only possible if you no longer own any (archived) surveys!');
        }

        $user->forcedelete();
        Auth::logout();
        return redirect('/login')->with('success', 'User Deleted Successfully!');
    }

    public function update(Request $request, User $user){
        if($user->id != Auth::id()){
            return abort(403);
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->name = $data['name'];
        $user->save();
        return redirect()->back()->with('success', 'User Name Updated Successfully');
    }

    public function changepassword(Request $request, User $user){
        if($user->id != Auth::id()){
            return abort(403);
        }
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($data['password']);
        $user->save();

        Auth::logout();
        return redirect('/login');
    }
}
