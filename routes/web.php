<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Survey\SurveyController;
use App\Http\Controllers\Survey\QuestionController;
use App\Http\Controllers\Survey\ChoiceController;
use App\Http\Controllers\Questionnaire\QuestionnaireController;
use App\Http\Controllers\Questionnaire\QuestionnaireStoreController;
use App\Http\Controllers\Questionnaire\QuestionnaireShowController;
use App\Http\Controllers\Stats\StatsController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Team\TeamUserController;
use App\Http\Controllers\Team\TeamController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/questionnaire/start/{survey_questionnaire_id}', [QuestionnaireShowController::class, 'startQuestionnaire'])->name('questionnaires.start');
Route::get('/questionnaire/end/{survey_questionnaire_id}', [QuestionnaireShowController::class, 'endQuestionnaire'])->name('questionnaires.end');
Route::post('/questionnaire/store/{survey_questionnaire_id}', [QuestionnaireStoreController::class, 'store'])->name('questionnaires.store');
Route::get('/questionnaire/show/{survey_questionnaire_id}', [QuestionnaireShowController::class, 'show'])->name('questionnaires.show');
Route::get('/questionnaire/show_previous_question/{survey_questionnaire_id}', [QuestionnaireShowController::class, 'show_previous_question'])->name('questionnaires.show_previous_question');


// Route::get('/questionnaire/{survey}/{question}/{choices}', [QuestionnaireController::class, 'show'])->name('questionnaires.show');
Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function () {
        return redirect()->route('surveys.index');
    });

    //archive
    Route::get('surveys/archive', [SurveyController::class, 'archive'])->name('surveys.archive');
    Route::get('questionnaires/archive', [QuestionnaireController::class, 'archive'])->name('questionnaires.archive');
    Route::get('surveys/questions/archive/{survey}', [QuestionController::class, 'archive'])->name('questions.archive');
    Route::get('surveys/choices/archive/{question}', [ChoiceController::class, 'archive'])->name('choices.archive',);

    //show
    Route::get('surveys/survey/{survey}/questions/{question}', [ChoiceController::class, 'index'])->name('survey.question.index');
    Route::get('surveys/survey/{survey}/questions/', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/stats/show/{survey}', [StatsController::class, 'show'])->name('stats.show');

    //create
    Route::get('surveys/create/', [SurveyController::class, 'create'])->name('surveys.create');
    Route::get('questions/create/{survey}', [QuestionController::class, 'create'])->name('questions.create');
    Route::get('choices/create/{question}', [ChoiceController::class, 'create'])->name('choices.create');

    //destroy
    Route::delete('/questionnaire/destroy/{survey}', [QuestionnaireController::class, 'destroy'])->name('questionnaires.destroy');
    Route::delete('/questions/forcedelete/{question}', [QuestionController::class, 'forcedelete'])->name('questions.forcedelete');
    Route::delete('/surveys/forcedelete/{survey}', [SurveyController::class, 'forcedelete'])->name('surveys.forcedelete');
    Route::delete('/choices/forcedelete/{choice}', [ChoiceController::class, 'forcedelete'])->name('choices.forcedelete');
    Route::delete('/questionnaire/forcedelete/{survey}', [QuestionnaireController::class, 'forcedelete'])->name('questionnaires.forcedelete');

    //store
    Route::post('/questions/store/{survey}', [QuestionController::class, 'store'])->name('questions.store');
    Route::post('/choices/store/{question}', [ChoiceController::class, 'store'])->name('choices.store');
    Route::post('/surveys/clone/{survey}', [SurveyController::class, 'clone'])->name('surveys.clone');
    Route::post('/questions/clone/{question}', [QuestionController::class, 'clone'])->name('questions.clone');

    //update
    Route::put('/surveys/update/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::put('/questions/update/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::put('/choices/update/{choice}', [ChoiceController::class, 'update'])->name('choices.update');
    Route::put('/surveys/golive/{survey}', [SurveyController::class, 'golive'])->name('surveys.golive');
    Route::put('/questionnaire/backtoedit/{survey}', [QuestionnaireController::class, 'back2theDrawingBoard'])->name('questionnaires.backtoedit');
    Route::put('/questions/moveup/{question}', [QuestionController::class, 'moveup'])->name('questions.moveup');
    Route::put('/questions/movedown/{question}', [QuestionController::class, 'movedown'])->name('questions.movedown');
    Route::put('/questions/movequestion/{question}', [QuestionController::class, 'movequestion'])->name('questions.movequestion');
    Route::put('/choices/moveup/{choice}', [ChoiceController::class, 'moveup'])->name('choices.moveup');
    Route::put('/choices/movedown/{choice}', [ChoiceController::class, 'movedown'])->name('choices.movedown');
    Route::put('/choices/movechoice/{choice}', [ChoiceController::class, 'movechoice'])->name('choices.movechoice');


    //restore
    Route::put('surveys/restore/{survey}', [SurveyController::class, 'restore'])->name('surveys.restore');
    Route::put('questions/restore/{question}', [QuestionController::class, 'restore'])->name('questions.restore');
    Route::put('choices/restore/{choice}', [ChoiceController::class, 'restore'])->name('choices.restore');
    Route::put('questionnaire/restore/{survey}', [QuestionnaireController::class, 'restore'])->name('questionnaires.restore');
    //getreplica
    Route::post('questionnaire/getreplica/{survey}', [QuestionnaireController::class, 'getreplica'])->name('questionnaire.getreplica');

    //download
    Route::get('/stats/download_csv/{survey}', [StatsController::class, 'download_csv'])->name('stats.download_csv');
    //clustering
    Route::get('/stats/clustering/{survey}', [StatsController::class, 'clustering'])->name('stats.clustering');
    Route::post('stats/download_clusters/{survey}', [StatsController::class, 'download_clusters'])->name('stats.download_clusters');

    //teamuser
    Route::put('teamuser/accept/{team}', [TeamUserController::class, 'accept'])->name('teamuser.accept_invitation');
    Route::delete('teamuser/decline/{team}', [TeamUserController::class, 'decline'])->name('teamuser.decline_invitation');
    Route::delete('teamuser/leave_team/{team}', [TeamUserController::class, 'leaveTeam'])->name('teamuser.leave_team');
    Route::post('teamuser/invitation/{team}', [TeamUserController::class, 'invitation'])->name('teamuser.invitation');
    Route::put('teamuser/{team}/cancel_invitation/{user}', [TeamUserController::class, 'cancel_invitation'])->name('teamuser.cancel_invitation');
    Route::put('teamuser/{team}/kick/{user}', [TeamUserController::class, 'kick'])->name('teamuser.kick');

    //user
    Route::put('profile/update/', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/changepassword/', [ProfileController::class, 'changepassword'])->name('profile.changepassword');
    Route::delete('profile/destroy/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //teamowned

    Route::put('team/giveawayteamleadership/{team}', [TeamController::class, 'giveawayteamleadership'])->name('team.giveawayteamleadership');
    Route::delete('team/destroy/{team}', [TeamController::class, 'destroy'])->name('team.destroy');
    Route::put('team/update/{team}', [TeamController::class, 'update'])->name('team.update');
    Route::post('team/store/', [TeamController::class, 'store'])->name('team.store');
    Route::get('team/archived/', [TeamController::class, 'archived'])->name('team.archived');
    Route::put('team/restore/{team}', [TeamController::class, 'restore'])->name('team.restore');
    Route::delete('team/forcedelete/{team}', [TeamController::class, 'forcedelete'])->name('team.forcedelete');

    //rest
    Route::resource('surveys', SurveyController::class)->except(['restore','update','create','golive']);
    Route::resource('questions', QuestionController::class)->except(['index','show','create','store','update']);
    Route::resource('choices', ChoiceController::class)->except(['index','show','create','store','update']);
    Route::resource('questionnaires', QuestionnaireController::class)->except(['show','store','edit','create','destroy','update','back2theDrawingBoard','startQuestionnaire']);
    Route::resource('profile', ProfileController::class)->except(['update','changepassword','destroy']);
    Route::resource('teamuser', TeamUserController::class);
    Route::resource('team', TeamController::class)->except(['update','destroy','store']);

});


// require __DIR__.'/auth.php';
