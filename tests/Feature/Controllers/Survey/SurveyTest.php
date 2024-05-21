<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team\Team;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

class SurveyTest extends TestCase
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

    public function test_survey_store(){
        $response = $this->actingAs($this->user)->post('/surveys/', [
            'team_id' => $this->team->id,
            'survey_title' => "",
            'survey_description' => "",
            'team_message' => "",
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('errors');

        $this->assertDatabaseMissing('surveys', [
            'survey_title' => "unique survey title",
        ]);

        $response = $this->actingAs($this->user)->post('/surveys/', [
            'team_id' => $this->team->id,
            'survey_title' => "unique survey title",
            'survey_description' => "",
            'team_message' => "",
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('survey_created');

        $this->assertDatabaseHas('surveys', [
            'survey_title' => "unique survey title",
        ]);
    }

    public function test_survey_update(){

        // $response = $this->actingAs($this->other_team_member_user)->put('/surveys/update/' . $this->survey, [
        //     'team_id' => $this->team->id,
        //     'survey_title' => "",
        //     'survey_description' => "",
        //     'team_message' => "",
        //     // 'remove_cover_image' => "0",
        // ]);

        // $response->assertStatus(403);


        // $response = $this->actingAs($this->new_user_2_invite)->put('/surveys/update/' . $this->survey, [
        //     'team_id' => $this->team->id,
        //     'survey_title' => "",
        //     'survey_description' => "",
        //     'team_message' => "",
        // ]);
        // $response->assertStatus(403);

        // $response = $this->actingAs($this->user)->put('/surveys/update/' . $this->survey, [
        //     'team_id' => $this->team->id,
        //     'survey_title' => "",
        //     'survey_description' => "",
        //     'team_message' => "",
        // ]);
        // $response->assertStatus(302);
        // $response->assertSessionHas('errors');

        // $this->assertDatabaseMissing('surveys', [
        //     'survey_title' => "unique survey title",
        // ]);


        // $new_team = Team::factory()->create(['team_name' => 'dummy']);
        // $response = $this->actingAs($this->user)->post('/surveys/', [
        //     'team_id' => $new_team->id,
        //     'survey_title' => "unique survey title",
        //     'survey_description' => "",
        //     'team_message' => "",
        // ]);
        // $response->assertStatus(403);



        $response = $this->actingAs($this->user)->put('/surveys/update/' . $this->survey, [
            'team_id' => $this->team->id,
            'survey_title' => "unique survey title",
            'survey_description' => "",
            'team_message' => "",
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('survey_updated');

        $this->assertDatabaseHas('surveys', [
            'survey_title' => "unique survey title",
        ]);
    }

    public function test_survey_destroy(){
        $response = $this->actingAs($this->user)->delete('/surveys/'. $this->survey);
        $response->assertStatus(302);
        $response->assertSessionHas('survey_deleted');

        $this->assertDatabaseMissing('surveys', [
            'survey_title' => "unique survey title",
        ]);
    }
}
