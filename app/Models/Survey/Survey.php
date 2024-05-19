<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Team\Team;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Survey extends Model
{
    use HasFactory, SoftDeletes;
    use HasUuids;

    protected $fillable = [
    'user_id',
    'survey_title',
    'survey_description',
    'cover_image_path',
    'questionnaire_id',
    'user_id',
    'team_id',
    'team_message',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // protected static function booted(): void
    // {
    //     parent::boot();

    //     // static::deleted(function (Survey $survey) {
    //     //     $survey->questions->each->delete();
    //     // });


    //     // static::restoring(function(Survey $survey) {
    //     //     $deleted_at = $survey->deleted_at;

    //     //     $survey->questions()->onlyTrashed()
    //     //     ->where('deleted_at', '>=', $deleted_at)
    //     //     ->get()
    //     //     ->each(function ($question) {
    //     //         $question->restore();
    //     //     });
    //     // });

    // }
    public function deepCopySurvey() {
        $new_survey = $this->replicate();
        $new_survey->save();

        if($this->cover_image_path!=null){
            $originalFileExtension = pathinfo($this->cover_image_path, PATHINFO_EXTENSION);
            $new_cover_image_path = $new_survey->id . '.' .  $originalFileExtension;
            Storage::disk('public')->copy($this->cover_image_path, $new_cover_image_path);
            $new_survey->cover_image_path = $new_cover_image_path;
            $new_survey->save();
        }

        $this->deepCopyQuestions($new_survey);
    }

    public function deepCopyQuestions(Survey $new_survey) {
        $this->questions()->orderBy('question_position')->each(function($question) use ($new_survey) {
            $question->deepCopyQuestion($new_survey);
        });
    }

}
