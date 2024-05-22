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
use Ramsey\Uuid\Uuid;

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
        $this->team->refresh();
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

        $response = $this->actingAs($this->other_team_member_user)->put('/surveys/update/' . $this->survey->id, [
            'team_id' => $this->team->id,
            'survey_title' => "title",
            'survey_description' => "",
            'team_message' => "",
        ]);

        $response->assertStatus(403);


        $response = $this->actingAs($this->new_user_2_invite)->put('/surveys/update/' . $this->survey->id, [
            'team_id' => $this->team->id,
            'survey_title' => "title",
            'survey_description' => "",
            'team_message' => "",
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->put('/surveys/update/' . $this->survey->id, [
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


        $new_team = Team::factory()->create(['team_name' => 'dummy', 'user_id' => $this->new_user_2_invite->id]);
        $response = $this->actingAs($this->user)->put('/surveys/update/', [
            'team_id' => $new_team->id,
            'survey_title' => "unique survey title",
            'survey_description' => "",
            'team_message' => "",
        ]);
        $response->assertStatus(405);


        $new_team->attachNewMemberWithStatusAccepted($this->user);
        $new_team->refresh();
        $response = $this->actingAs($this->user)->put('/surveys/update/' . $this->survey->id, [
            'team_id' => $new_team->id,
            'survey_title' => "unique survey title",
            'survey_description' => "",
            'team_message' => "",
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('survey_updated');

        $this->assertDatabaseMissing('surveys', [
            'survey_title' => "unique survey title",
            'team_id' =>$this->team->id,
        ]);

        $this->assertDatabaseHas('surveys', [
            'team_id' =>$new_team->id,
            'survey_title' => "unique survey title",
        ]);


        $this->survey->questionnaire_id = '1';
        $this->survey->save();
        $this->survey->refresh();
        
        $response = $this->actingAs($this->user)->put('/surveys/update/' . $this->survey->id, [
            'team_id' => $this->team->id,
            'survey_title' => "unique survey title",
            'survey_description' => "",
            'team_message' => "",
        ]);
        $response->assertStatus(403);

        $this->survey->delete();
        $this->survey->save();
        $this->survey->refresh();
        $response = $this->actingAs($this->user)->put('/surveys/update/' . $this->survey->id, [
            'team_id' => $this->team->id,
            'survey_title' => "unique survey title",
            'survey_description' => "",
            'team_message' => "",
        ]);
        $response->assertStatus(404);
    }

    public function test_survey_destroy_and_restore_and_forcedelete(){
        $response = $this->actingAs($this->other_team_member_user)->delete('/surveys/'. $this->survey->id);
        $response->assertStatus(403);


        $response = $this->actingAs($this->new_user_2_invite)->delete('/surveys/'. $this->survey->id);
        $response->assertStatus(403);

        $this->assertDatabaseHas('surveys', [
            'id' => $this->survey->id,
            'deleted_at' => null,
        ]);

        $response = $this->actingAs($this->user)->delete('/surveys/');
        $response->assertStatus(405);


        $this->survey->questionnaire_id = '1';
        $this->survey->save();
        $this->survey->refresh();
        $response = $this->actingAs($this->user)->delete('/surveys/'. $this->survey->id);
        $response->assertStatus(403);

        $this->survey->questionnaire_id = null;
        $this->survey->save();
        $this->survey->refresh();

        $response = $this->actingAs($this->user)->delete('/surveys/'. $this->survey->id);
        $response->assertStatus(302);
        $response->assertSessionHas('survey_deleted');

        $this->survey->refresh();
        $this->assertDatabaseHas('surveys', [
            'id' => $this->survey->id,
            'deleted_at' => $this->survey->deleted_at,
        ]);

        $response = $this->actingAs($this->user)->delete('/surveys/'. $this->survey->id);
        $response->assertStatus(404);


        // ----------------------------------------------------------------

        $response = $this->actingAs($this->other_team_member_user)->put('/surveys/restore/'. $this->survey->id);
        $response->assertStatus(404);


        $response = $this->actingAs($this->new_user_2_invite)->put('/surveys/restore/'. $this->survey->id);
        $response->assertStatus(404);

        $response = $this->actingAs($this->user)->put('/surveys/restore/');
        $response->assertStatus(405);

        $response = $this->actingAs($this->user)->put('/surveys/restore/'. $this->survey->id);
        $response->assertStatus(302);
        $response->assertSessionHas('survey_restored');

        $this->survey->refresh();
        $this->assertDatabaseHas('surveys', [
            'id' => $this->survey->id,
            'deleted_at' => null,
        ]);

        $response = $this->actingAs($this->user)->put('/surveys/restore/'. $this->survey->id);
        $response->assertStatus(404);

        // ----------------------------------------------------------------

        $response = $this->actingAs($this->other_team_member_user)->delete('/surveys/forcedelete/'. $this->survey->id);
        $response->assertStatus(404);


        $response = $this->actingAs($this->new_user_2_invite)->delete('/surveys/forcedelete/'. $this->survey->id);
        $response->assertStatus(404);

        $this->assertDatabaseHas('surveys', [
            'id' => $this->survey->id,
            'deleted_at' => null,
        ]);

        $this->survey->delete();
        $this->survey->refresh();

        $response = $this->actingAs($this->other_team_member_user)->delete('/surveys/forcedelete/'. $this->survey->id);
        $response->assertStatus(404);


        $response = $this->actingAs($this->new_user_2_invite)->delete('/surveys/forcedelete/'. $this->survey->id);
        $response->assertStatus(404);

        $this->assertDatabaseHas('surveys', [
            'id' => $this->survey->id,
            'deleted_at' => $this->survey->deleted_at,
        ]);

        $response = $this->actingAs($this->user)->delete('/surveys/forcedelete/');
        $response->assertStatus(404);

        $this_survey_id = $this->survey->id;

        $response = $this->actingAs($this->user)->delete('/surveys/forcedelete/'. $this->survey->id);
        $response->assertStatus(302);
        $response->assertSessionHas('survey_forcedeleted');

        $this->assertDatabaseMissing('surveys', [
            'id' => $this_survey_id,
        ]);

        $response = $this->actingAs($this->user)->delete('/surveys/forcedelete/'. $this->survey->id);
        $response->assertStatus(404);

    }


    public function test_survey_golive(){
        $this->assertDatabaseHas('surveys', [
            'id' => $this->survey->id,
            'questionnaire_id' => null,
        ]);

        $response = $this->actingAs($this->user)->put('/surveys/golive/'. $this->survey->id);
        $response->assertStatus(302);

        $this->survey->refresh();
        $questionnaire = Survey::where('survey_title',$this->survey->survey_title)->whereNotNull('questionnaire_id');$this->survey->survey_;
        assertNotNull($questionnaire);        
    }

    public function test_survey_clone(){
        $response = $this->actingAs($this->user)->post('/surveys/clone/'. $this->survey->id);
        $response->assertStatus(302);
        $response->assertSessionHas('survey_cloned');
        $survey = Survey::where('survey_title',$this->survey->survey_title)->where('id','!=',$this->survey->id);
        assertNotNull($survey);   
    }
}
