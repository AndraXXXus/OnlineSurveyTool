<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Answer extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = [
    'answer_text',
    'responder_id',
    'survey_id',
    'choice_id',
    'question_id',
    'question_started_at'
    ];

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

}
