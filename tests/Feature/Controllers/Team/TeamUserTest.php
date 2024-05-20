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
    protected $team;
    protected $survey;
    protected $survey_trashed;

    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     $this->user = User::factory()->create(['email' => 'example@test.com', 'name'=>'testUser']);
    //     $this->team = $this->user->teams->first();
    //     $this->survey = Survey::factory()->create(['user_id' => $this->user->id, 'team_id' => $this->team->id]);
    // }

    // public function test_team_is_archive()
    // {
    //     $team_id = $this->team->id;
    //     $survey_id = $this->survey->id;

    //     assertTrue(Team::findOrFail($team_id)->deleted_at === null);
    //     assertTrue(Survey::findOrFail($survey_id)->deleted_at === null);

    //     $response = $this->actingAs($this->user)->delete('/team/destroy', [
    //         'team' => $this->team ]);
    //     $response->assertStatus(302);
    //     dump($response);
    //     assertTrue(Team::findOrFail($team_id)->deleted_at != null);
    //     assertTrue(Survey::findOrFail($survey_id)->deleted_at != null);

    //     $this->team->restore();
    //     assertTrue(Team::findOrFail($team_id)->deleted_at === null);
    //     assertTrue(Survey::findOrFail($survey_id)->deleted_at === null);
    // }

    // public function test_team_if_archived_forcedelete()
    // {

    // }


    // public function test_profile_changepassword()
    // {
    //     assertTrue(Hash::check('password', $this->user->password));

    //     $hashed_password = Hash::make('alma');
    //     $response = $this->actingAs($this->user)->put('/profile/changepassword', [
    //         'password' => $hashed_password ]);

    //     $response->assertStatus(302,'User Name Updated Successfully');

    //     $user = User::findOrFail($this->user->id);

    //     assertTrue(Hash::check('password', $user->password));
    // }

    // public function test_profile_destroy_has_team()
    // {
    //     $response = $this->actingAs($this->user)->delete('/profile/destroy');
    //     $response->assertStatus(302, 'Deleting user only possible if you are no longer own any (archived) team(s)!');
    //     assertTrue($this->user->teams_owned_withArchived()->count()>0);

    //     $this->assertDatabaseHas('users', [
    //         'id' => $this->user->id,
    //     ]);

    // }

    // public function test_profile_destroy_has_no_team()
    // {
    //     $user_id = $this->user->id;

    //     $team = $this->user->teams->first();
    //     $team->delete();
    //     $team->forceDelete();

    //     $response = $this->actingAs($this->user)->delete('/profile/destroy');
    //     $response->assertStatus(302);

    //     assertTrue(User::find($user_id) === null);
    // }


}
