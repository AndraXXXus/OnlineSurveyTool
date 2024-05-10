<?php

namespace App\Http\Controllers\Questionnaire;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use App\Models\Survey\Option;
use App\Models\Survey\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class QuestionnaireController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        return view('questionnaires.index')->with(['teams' => Auth::user()->teams->sortByDesc('updated_at')]);
    }



    public function archive()
    {
        $archive = true;
        $teams = Auth::User()->teams->sortByDesc('updated_at');
        return view('questionnaires.index')->with(['teams' => $teams,'archive' => $archive]);
    }

    public function destroy(Survey $survey)
    {
        $this->authorize('questionnaireTeamOwnerAndUserMatch',$survey);

        $deleted = $survey->delete();
        if (!$deleted) {
            return abort(500);
        }

        Session::flash('questionnaire_deleted', $survey);
        return redirect()->route('questionnaires.index');
    }

    public function restore(String $survey)
    {
        $user = User::findOrFail(Auth::id());
        $survey = $user->questionarries()->onlyTrashed()->findOrFail($survey);

        $this->authorize('questionnaireTeamMembersAndUserMatch',$survey);

        $survey->restore();

        Session::flash('questionnaire_restored', $survey);

        return redirect()->route('questionnaires.index');
    }

    public function getreplica(Survey $survey){
        $this->authorize('questionnaireTeamMembersAndUserMatch',$survey);

        $survey->questionnaire_id = null;
        $survey->user_id = Auth::user()->id;
        $survey->deepCopySurvey();

        return redirect()->route('surveys.index');
    }

    public function forcedelete(String $survey)
    {
        $survey = Survey::withTrashed()->where('user_id',Auth::user()->id)->findOrFail($survey);
        $this->authorize('questionnaireTeamOwnerAndUserMatch', $survey);

        //$this->authorize('delete', $survey);

        $deleted = $survey->forceDelete();
        if (!$deleted) {
            return abort(500);
        }

        Session::flash('questionnaire_forcedeleted', $survey);
        return redirect()->route('questionnaires.index');
    }

}

