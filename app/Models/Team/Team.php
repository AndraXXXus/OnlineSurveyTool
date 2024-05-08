<?php

namespace App\Models\Team;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Survey\Survey;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Team extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'team_name',
        'user_id',
    ];

    public function members(){
        return $this->belongsToMany(User::class)->wherePivot('status', 'accepted')->withTimestamps();
    }

    public function surveys(){
        return $this->hasMany(Survey::class);
    }

    public function unpublished_surveys(){
        return $this->hasMany(Survey::class)->whereNull('questionnaire_id');
    }

    public function questionarries(){
        return $this->hasMany(Survey::class)->whereNotNull('questionnaire_id');
    }

    public function invitations(){
        return $this->belongsToMany(User::class)->wherePivot('status', 'pending')->withTimestamps();
    }

    public function teamleader(){
        return User::findOrFail($this->user_id);
    }

    protected static function booted(): void
    {
        parent::boot();

        static::creating(function (Team $team) {
            $team->id = Str::uuid()->toString();
        });
    }
}
