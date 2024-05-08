<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class Question extends Model
{
    use HasFactory, SoftDeletes;
    protected $keyType = 'string';
    public $incrementing = false;

    protected static $allowedQuestionTypes = ["radio-button","mutiple-choice","dropp-down","open"];

    protected $touches = ['survey'];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function setQuestionTypeAttribute($value)
    {
        // Check if the provided value is in the allowed roles list
        if (in_array($value, Question::$allowedQuestionTypes)) {
            $this->attributes['question_type'] = $value;
        } else {
            throw new \InvalidArgumentException("Invalid question type: $value");
        }
    }

    public function previousQuestion()
    {
        $previous_question = Question::where('survey_id',$this->survey_id)->where('question_position',$this->question_position-1)->first();
        return $previous_question;
    }

    public function nextQuestion()
    {
        $next_question = Question::where('survey_id',$this->survey_id)->where('question_position',$this->question_position+1)->first();
        return $next_question;
    }

    static function getAllowedQuestionTypes()
    {
        return Question::$allowedQuestionTypes;
    }

    protected $fillable = [
    'survey_id',
    'question_position',
    'question_text',
    'question_type',
    'question_required',
    'cover_image_path',
    'youtube_id',
    'video_no_controlls',
    'prefer_video',
    ];

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function positionCanDecrease(){
        return $this->question_position>1;
    }

    public function positionCanIncrease(){
        return $this->question_position < $this->survey()->first()->questions()->get()->count();
    }

    protected $casts = [
        'question_position' => 'integer',
        'question_type'=> 'string',
    ];

    protected static function booted(): void
    {
        parent::boot();

        static::creating(function (Question $question) {
            $question->id = Str::uuid()->toString();
        });

        static::deleted(function (Question $question) {
            $question->choices->each->delete();
        });

        static::restoring(function(Question $question) {
            $deleted_at = $question->deleted_at;

            $question->choices()
                ->onlyTrashed()->where('deleted_at', '>=', $deleted_at)
                ->get()
                ->each(function ($choice) {
                    $choice->restore();
                });
        });
    }

    public function deepCopyQuestion(Survey $new_survey) {
        $new_question = $this->replicate(['survey_id', 'question_position']);
        $new_question->survey_id = $new_survey->id;
        $new_question->question_position = $new_survey->questions()->count()+1;
        $new_question->save();
        if($new_question->cover_image_path!=null){
            $originalFileExtension = pathinfo($new_question->cover_image_path, PATHINFO_EXTENSION);
            $new_cover_image_path = $new_question->id . '.' .  $originalFileExtension;
            Storage::disk('public')->copy($this->cover_image_path, $new_cover_image_path);
            $new_question->cover_image_path = $new_cover_image_path;
            $new_question->save();
        }
        $this->replicateChoices($new_question);
    }

    public function replicateChoices(Question $new_question) {
        $this->choices()->orderBy('choice_position')->each(function($choice) use ($new_question) {
            $choice->replicateChoice($new_question);
        });
    }
}
