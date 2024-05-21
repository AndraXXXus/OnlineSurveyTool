<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team\Team;
use App\Models\Survey\Survey;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\assertTrue;

class TeamUserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use RefreshDatabase;


    protected $user;
    protected $other_team_member_user;
    protected $new_user_2_invite;
    protected $team;
    protected $survey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'example@test.com', 'name'=>'testUser']);
        $this->team = $this->user->teams->first();
        $this->survey = Survey::factory()->create(['user_id' => $this->user->id, 'team_id' => $this->team->id]);
        $this->other_team_member_user = User::factory()->create(['email' => 'new_example@test.com', 'name'=>'new_testUser']);
        $this->team->members()->attach($this->other_team_member_user->id, ['status' => 'accepted']);

        $this->new_user_2_invite = User::factory()->create(['email' => 'invite@test.com', 'name'=>'invite_testUser']);
    }

    public function test_team_invitation()
    {
        $team_id = $this->team->id;

        $response = $this->actingAs($this->user)->post('/teamuser/invitation/'.$team_id, [
            'email_'.$team_id => "not_a@valid.emial" ]);
        $response->assertStatus(302);
        $response->assertSessionHas('errors');

        $response = $this->actingAs($this->user)->post('/teamuser/invitation/'.$team_id, [
            'email_'.$team_id => "" ]);
        $response->assertStatus(302);
        $response->assertSessionHas('errors');

        $response = $this->actingAs($this->user)->post('/teamuser/invitation/'.$team_id, [
            'email_'.$team_id => 12 ]);
        $response->assertStatus(302);
        $response->assertSessionHas('errors');

        $response = $this->actingAs($this->other_team_member_user)->post('/teamuser/invitation/'.$team_id, [
            'email_'.$team_id => $this->new_user_2_invite->email ]);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->post('/teamuser/invitation/'.$team_id, [
            'email_'.$team_id => $this->new_user_2_invite->email ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Invitation Successfully Sent');

        $response = $this->actingAs($this->user)->post('/teamuser/invitation/'.$team_id, [
            'email_'.$team_id => $this->new_user_2_invite->email ]);
        $response->assertStatus(302);
        $response->assertSessionHas('warning', "User has already been invited");

        $response = $this->actingAs($this->user)->post('/teamuser/invitation/'.$team_id, [
            'email_'.$team_id => $this->other_team_member_user->email ]);
        $response->assertStatus(302);
        $response->assertSessionHas('warning', "User is already a member");

        assertTrue($this->team->invitations->pluck('id')->contains($this->new_user_2_invite->id));
    }

    public function test_team_cancel_invitation(){
        $team_id = $this->team->id;
        $response = $this->actingAs($this->user)->put('/teamuser/' . $team_id . "/cancel_invitation/" . $this->new_user_2_invite->id);
        $response->assertStatus(302);
        $response->assertSessionHas('warning', "User has not yet been invited");

        $this->team->invitations()->attach($this->new_user_2_invite->id);

        $response = $this->actingAs($this->other_team_member_user)->put('/teamuser/' . $team_id . "/cancel_invitation/" . $this->new_user_2_invite->id);
        $response->assertStatus(403);


        $response = $this->actingAs($this->user)->put('/teamuser/' . $team_id . "/cancel_invitation/" . $this->new_user_2_invite->id);
        $response->assertStatus(302);
        $response->assertSessionHas('success', "Invitation Successfully withdrawn");

        $response = $this->actingAs($this->user)->put('/teamuser/' . $team_id . "/cancel_invitation/" . $this->other_team_member_user->id);
        $response->assertStatus(302);
        $response->assertSessionHas('warning', "Invitation already accepted!");
    }

    // public function test_team_accept_invitation()
    // {
    //     $team_id = $this->team->id;
    //     $this->team->invitations()->attach($this->new_user_2_invite->id);
    //     $response = $this->actingAs($this->new_user_2_invite)->put('/teamuser/accept/' . $team_id);
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('warning', "Invitation already accepted!");
    // }

    // public function test_team_decline_invitation()
    // {

    // }

    // public function test_team_leaveTeam()
    // {

    // }

    // public function test_team_kick_user()
    // {

    // }

}
