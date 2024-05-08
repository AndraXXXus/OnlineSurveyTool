<?php

namespace App\Policies\Survey;

use App\Models\User;
use App\Models\Survey\Survey;
use Illuminate\Support\Facades\Auth;

class SurveyPolicy
{
    /**
     * Create a new policy instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    public function isLeagalSurvey(User $user, Survey $survey){
        return ($survey != null && $survey->questionnaire_id === null);
    }
    public function isLeagalQuestionnaire(User $user, Survey $survey){
        return ($survey != null && $survey->questionnaire_id != null);
    }


    public function surveyTeamAndUserMatch(User $user, Survey $survey){
        return $survey->team->members->pluck('id')->contains($user->id);
    }
    public function surveyAndUserMatch(User $user, Survey $survey){
        return ($this->isLeagalSurvey($user, $survey) && $survey->user_id === $user->id);
    }


    public function questionnaireTeamMembersAndUserMatch(User $user, Survey $survey){
        return $this->isLeagalQuestionnaire($user, $survey) && $this->surveyTeamAndUserMatch($user, $survey);
    }
    public function questionnaireTeamOwnerAndUserMatch(User $user, Survey $survey){
        return ($this->isLeagalQuestionnaire($user, $survey) && $survey->team->user_id === $user->id);
    }
}
