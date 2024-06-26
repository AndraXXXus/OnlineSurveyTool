<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team\Team;
use App\Models\Survey\Survey;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;
use Illuminate\Support\Facades\DB;

class TeamTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use RefreshDatabase;


    protected $user;
    protected $other_team_member_user;
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
    }

    public function test_new_team_store(){
        $response = $this->actingAs($this->user)->post('/team/store', [
            'team_name' => "my_new_team" ]);
        $response->assertStatus(302);

        $this->assertDatabaseHas('teams', [
            'team_name' => "my_new_team",
        ]);

        $new_team = Team::where('team_name' , "my_new_team")->first();

        assertTrue($new_team->members->pluck('id')->contains($this->user->id));
        assertTrue($new_team->user_id === $this->user->id);
    }

    public function test_team_name_update(){
        assertTrue($this->team->user_id === $this->user->id);

        $response = $this->actingAs($this->other_team_member_user)->put('/team/update/'.$this->team->id, [
            'team_name' => "my_new_teams_new_name" ]);

        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->put('/team/update/'.$this->team->id, [
            'team_name' => "my_new_teams_new_name" ]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('teams', [
            'team_name' => $this->team->team_name,
        ]);

        $this->assertDatabaseHas('teams', [
            'team_name' => "my_new_teams_new_name",
        ]);

        $new_team = Team::where('team_name' , $this->team->team_name)->first();
        assertNull($new_team);
        $new_team = Team::where('team_name' , "my_new_teams_new_name")->first();
        assertNotNull($new_team);
    }

    public function test_team_giveawayteamleadership(){
        $independent_other_user = User::factory()->create(['email' => 'independent_example@test.com',
        'name'=>'independent_testUser']);

        assertTrue($this->team->members->pluck('id')->contains($this->user->id));
        assertTrue($this->team->members->pluck('id')->contains($this->other_team_member_user->id));
        assertFalse($this->team->members->pluck('id')->contains($independent_other_user->id));

        $this->assertDatabaseHas('teams', [
            'id' => $this->team->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('team_user', [
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
        ]);
        $this->assertDatabaseHas('team_user', [
            'team_id' => $this->team->id,
            'user_id' => $this->other_team_member_user->id,
        ]);
        $this->assertDatabaseMissing('team_user', [
            'team_id' => $this->team->id,
            'user_id' => $independent_other_user->id,
        ]);


        assertTrue($this->team->user_id === $this->user->id);
        $this->assertDatabaseHas('teams', [
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($independent_other_user)->put('/team/giveawayteamleadership/'.$this->team->id, [
            'new_teamleader_user_id' => $independent_other_user->id ]);

        $response->assertStatus(403);

        $response = $this->actingAs($this->other_team_member_user)->put('/team/giveawayteamleadership/'.$this->team->id, [
            'new_teamleader_user_id' => $this->other_team_member_user->id ]);

        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->put('/team/giveawayteamleadership/'.$this->team->id, [
            'new_teamleader_user_id' => $this->other_team_member_user->id ]);
        $response->assertStatus(302);

        $this->team->refresh();

        assertTrue($this->team->user_id===$this->other_team_member_user->id);
        $this->assertDatabaseHas('teams', [
            'id' => $this->team->id,
            'user_id' => $this->other_team_member_user->id,
        ]);
    }

    //php artisan test --coverage --migrate-configuration

    public function test_team_archive_and_restore()
    {
        $this->assertDatabaseHas('team_user', [
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
        ]);
        $this->assertDatabaseHas('team_user', [
            'team_id' => $this->team->id,
            'user_id' => $this->other_team_member_user->id,
        ]);

        assertTrue($this->team->user_id === $this->user->id);
        $team_id = $this->team->id;
        $survey_id = $this->survey->id;

        $this->assertDatabaseHas('teams', [
            'id' => $this->team->id,
        ]);
        $this->assertDatabaseHas('surveys', [
            'id' => $survey_id,
        ]);

        assertTrue(Team::findOrFail($team_id)->deleted_at === null);
        assertTrue(Survey::findOrFail($survey_id)->deleted_at === null);

        $response = $this->actingAs($this->other_team_member_user)->delete('/team/destroy/'.$this->team->id);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->delete('/team/destroy/'.$this->team->id);
        $response->assertStatus(302);

        $response = $this->actingAs($this->user)->delete('/team/destroy/'.$this->team->id);
        $response->assertStatus(404);

        assertTrue(Team::onlyTrashed()->findOrFail($team_id)->deleted_at != null);
        assertTrue(Survey::onlyTrashed()->findOrFail($survey_id)->deleted_at != null);

        $this->assertDatabaseHas('teams', [
            'id' => $team_id,
        ]);
        $this->assertDatabaseHas('surveys', [
            'id' => $survey_id,
        ]);

        $response = $this->actingAs($this->other_team_member_user)->put('/team/restore/'.$this->team->id);
        $response->assertStatus(403);

        assertTrue(Team::onlyTrashed()->findOrFail($team_id)->deleted_at != null);
        assertTrue(Survey::onlyTrashed()->findOrFail($survey_id)->deleted_at != null);

        $response = $this->actingAs($this->user)->put('/team/restore/'.$this->team->id);
        $response->assertStatus(302);

        assertTrue(Team::findOrFail($team_id)->deleted_at === null);
        assertTrue(Survey::findOrFail($survey_id)->deleted_at === null);

        $this->assertDatabaseHas('teams', [
            'id' => $team_id,
        ]);
        $this->assertDatabaseHas('surveys', [
            'id' => $survey_id,
        ]);
    }

    public function test_team_forcedelete()
    {
        $this->assertDatabaseHas('team_user', [
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
        ]);
        $this->assertDatabaseHas('team_user', [
            'team_id' => $this->team->id,
            'user_id' => $this->other_team_member_user->id,
        ]);

        assertTrue($this->team->user_id === $this->user->id);
        $team_id = $this->team->id;
        $survey_id = $this->survey->id;

        $this->assertDatabaseHas('teams', [
            'id' => $this->team->id,
        ]);
        $this->assertDatabaseHas('surveys', [
            'id' => $survey_id,
        ]);

        assertTrue(Team::findOrFail($team_id)->deleted_at === null);
        assertTrue(Survey::findOrFail($survey_id)->deleted_at === null);

        Team::findOrFail($this->team->id)->delete();

        $response = $this->actingAs($this->other_team_member_user)->delete('/team/forcedelete/'.$this->team->id);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->delete('/team/forcedelete/'.$this->team->id);
        $response->assertStatus(302);

        $response = $this->actingAs($this->user)->delete('/team/forcedelete/'.$this->team->id);
        $response->assertStatus(404);

        assertNull(Team::onlyTrashed()->find($team_id));
        assertNull(Survey::onlyTrashed()->find($survey_id));

        $this->assertDatabaseMissing('teams', [
            'id' => $team_id,
        ]);
        $this->assertDatabaseMissing('surveys', [
            'id' => $survey_id,
        ]);
    }
}
