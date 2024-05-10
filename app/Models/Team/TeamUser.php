<?php

namespace App\Models\Team;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Team\Team;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = ['user_id', 'team_id'];
    public $incrementing = false;

    protected $fillable = ['team_id', 'user_id', 'status'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // protected static function booted(): void
    // {
    //     parent::boot();

    //     function broadcastUpdate(TeamUser $teamuser)
    //     {
    //         $team = Team::findOrFail($teamuser->team_id);
    //         $user = User::findOrFail($teamuser->user_id);
    //         dd($team, $user);
    //         return;
    //     };

    //     static::creating(function (TeamUser $teamuser) {
    //         $this->broadcastUpdate($teamuser);
    //     });

    //     static::deleted(function (TeamUser $teamuser) {
    //         $this->broadcastUpdate($teamuser);
    //     });

    //     static::updated(function (TeamUser $teamuser) {
    //         $this->broadcastUpdate($teamuser);
    //     });

    //     static::saved(function (TeamUser $teamuser) {
    //         $this->broadcastUpdate($teamuser);
    //     });

    // }
}
