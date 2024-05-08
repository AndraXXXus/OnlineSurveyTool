<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\Team\Team;
use App\Models\Survey\Survey;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasFactory;


    protected $keyType = 'string';
    public $incrementing = false;
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

    public function getRouteKeyName()
    {
        return 'id';
    }

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


    protected static function booted(): void
    {
        parent::boot();

        static::creating(function (User $user) {
            $user->id = Str::uuid()->toString();
        });

        static::created(function (User $user) {
            $team = Team::factory()->create([
                'user_id' => $user->id,
                'team_name' =>  $user->name . "'s team",
            ]);
            $team->members()->attach($user->id, ['status' => 'accepted']);
        });


        // static::deleting(function (User $user) {
        //     //todo
        //     $user->surveys()->withTrashed->each->delete();
        // });

    }


}
