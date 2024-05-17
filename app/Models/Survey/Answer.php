<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Answer extends Model
{
    use HasUuids;
    use HasFactory;
    // protected $keyType = 'string';
    // public $incrementing = false;

    protected $fillable = [
    'answer_text',
    'responder_id',
    'survey_id',
    'choice_id',
    'question_id',
    'question_started_at'
    ];

    // public function getRouteKeyName()
    // {
    //     return 'id';
    // }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }


    // protected static function booted(): void
    // {
    //     parent::boot();

    //     static::creating(function (Answer $answer) {
    //         $answer->id = Str::uuid()->toString();
    //     });
    // }


}
