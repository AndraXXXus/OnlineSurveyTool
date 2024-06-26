<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\Team\Team;
use App\Models\Survey\Survey;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cover_image_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->wherePivot('status', 'accepted')->withTimestamps();
    }

    public function invitations()
    {
        return $this->belongsToMany(Team::class)->withPivot('status')->wherePivot('status', 'pending')->withTimestamps();
    }

    public function teams_owned(){
        return $this->teams->where('user_id', $this->id);
    }

    public function teams_owned_withArchived(){
        return $this->teams()->withTrashed()->where('teams.user_id', $this->id);
    }

    public function questionnaires(){
        return $this->hasMany(Survey::class)->whereNotNull('questionnaire_id');
    }


    protected static function booted(): void
    {
        parent::boot();

        static::created(function (User $user) {
            $team = Team::factory()->create([
                'user_id' => $user->id,
                'team_name' =>  $user->name . "'s team",
            ]);
            $team->members()->attach($user->id, ['status' => 'accepted']);
        });


        // static::deleting(function (User $user) {
        //     //no todo here, discontinued
        //     $user->surveys()->withTrashed->each->delete();
        // });

    }


}
