<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Choice extends Model
{
    use HasUuids;
    use HasFactory, SoftDeletes;
    protected $touches = ['question'];
    // protected $keyType = 'string';
    // public $incrementing = false;

    protected $fillable = [
    'question_id',
    'choice_position',
    'choice_text',
    'cover_image_path',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    protected $casts = [
        'choice_position' => 'integer',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function positionCanDecrease(){
        return $this->choice_position>1;
    }

    public function positionCanIncrease(){
        return $this->choice_position < $this->question()->first()->choices()->get()->count();
    }

    public function replicateChoice(Question $new_question) {
        $new_choice = $this->replicate(['question_id']);
        $new_choice->question_id = $new_question->id;
        $new_choice->save();
    }

    public function parentQuestionIsSoftDeleted(){
        return $this->question->deleted_at === null;
    }

    public function grandparentSurveyIsSoftDeleted(){
        return $this->question->survey->deleted_at === null;
    }

    // protected static function booted(): void
    // {
    //     parent::boot();

    //     static::creating(function (Choice $choice) {
    //         $choice->id = Str::uuid()->toString();
    //     });

    // }


}
