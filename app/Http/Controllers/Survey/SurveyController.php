<?php

namespace App\Http\Controllers\Survey;

use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use App\Models\Survey\Option;
use App\Models\Team\Team;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;
use App\Http\Requests\CreateOrUpdateSurveyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SurveyController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::findOrFail(Auth::id());

        return view('surveys.surveys.index');
    }

    public function archive()
    {
        $user = User::findOrFail(Auth::id());

        $archive = true;
        return view('surveys.surveys.index')->with(['archive' => $archive]);
    }



    public function create()
    {
        return view('surveys.surveys.create');
    }

    public function store(CreateOrUpdateSurveyRequest $request)
    {
        $data['team_id'] = $request->input('team_id');
        $data['survey_title'] = $request->input('survey_title');
        $data['survey_description'] = $request->input('survey_description');

        $data['deleted_at'] = null;
        $data['user_id'] = Auth::id();
        $data['cover_image_path'] = null;

        $team = Team::findOrFail($data['team_id']);
        $this->authorize('userIsTeamMember', $team);

        $survey = Survey::create($data);

        if($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $cover_image_path = $survey->id . '.'.$file->getClientOriginalExtension();
            Storage::disk('public')->put(
                $cover_image_path,$file->get()
            );
            $survey->cover_image_path = $cover_image_path;
            $survey->save();
        }
        Session::flash('survey_created', $survey);
        return redirect()->route('questions.show', ['survey' => $survey]);
    }

    public function edit(Survey $survey)
    {
        $this->authorize('surveyAndUserMatch', $survey);

        return view('surveys.surveys.create')->with(['survey' => $survey]);
    }

    public function update(CreateOrUpdateSurveyRequest $request, Survey $survey)
    {
        $this->authorize('surveyAndUserMatch', $survey);

        $cover_image_path = $survey->cover_image_path;

        if($request->hasFile('cover_image')) {
            if($survey->cover_image_path != null) {
                Storage::disk('public')->delete($survey->cover_image_path);
            }
            $file = $request->file('cover_image');
            $cover_image_path = $survey->id . '.'.$file->getClientOriginalExtension();
            Storage::disk('public')->put(
                $cover_image_path,$file->get()
            );
        }

        if ( $request->input('remove_cover_image') === "1" ){
            $cover_image_path = null;
            Storage::disk('public')->delete($survey->cover_image_path);
        }

        $survey->team_id = $request->input('team_id');
        $survey->cover_image_path = $cover_image_path;
        $survey->survey_title =  $request->input('survey_title');
        $survey->survey_description = $request->input('survey_description');
        $survey->deleted_at = null;

        $team = Team::findOrFail($survey->team_id);
        $this->authorize('userIsTeamMember', $team);

        $survey->save();

        Session::flash('survey_updated', $survey);

        return redirect()->route('questions.show', ['survey' => $survey]);
    }


    public function restore(String $survey)
    {
        $survey = Survey::onlyTrashed()->where('user_id',Auth::id())->findOrFail($survey);
        
        $this->authorize('surveyAndUserMatch', $survey);

        $survey->restore();

        Session::flash('survey_restored', $survey);

        return redirect()->route('questions.show', ['survey' => $survey]);
    }

    public function destroy(Survey $survey)
    {
        $this->authorize('surveyAndUserMatch', $survey);

        $deleted = $survey->delete();
        if (!$deleted) {
            return abort(500);
        }

        Session::flash('survey_deleted', $survey);
        return redirect()->route('surveys.index');
    }

    public function golive(Survey $survey){
        $this->authorize('surveyAndUserMatch', $survey);

        $survey->user_id = $survey->team->user_id;
        $survey->questionnaire_id = Uuid::uuid4();
        $survey->deepCopySurvey();

        $survey->team->touch();
        return redirect()->route('questionnaires.index' );
    }

    public function clone(Survey $survey){
        $this->authorize('surveyAndUserMatch', $survey);

        $survey->deepCopySurvey();
        Session::flash('survey_cloned', $survey);
        return redirect()->route('surveys.index');
    }

    public function forcedelete(String $survey)
    {
        $survey = Survey::withTrashed()->where('user_id',Auth::user()->id)->findOrFail($survey);

        $this->authorize('surveyAndUserMatch', $survey);

        $cover_image_path = $survey->cover_image_path;
        $deleted = $survey->forcedelete();

        if (!$deleted) {
            return abort(500);
        }

        if($cover_image_path != null) {
            Storage::disk('public')->delete($cover_image_path);
        }

        Session::flash('survey_forcedeleted', $survey);
        return redirect()->route('surveys.index');
    }
}

