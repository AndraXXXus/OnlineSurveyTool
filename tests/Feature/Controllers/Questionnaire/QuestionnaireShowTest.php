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

class QuestionnaireShowTest extends TestCase
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
    protected $questionnaire;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'example@test.com', 'name'=>'testUser']);
        $this->team = $this->user->teams->first();

        $this->other_team_member_user = User::factory()->create(['email' => 'new_example@test.com', 'name'=>'new_testUser']);
        $this->team->members()->attach($this->other_team_member_user->id, ['status' => 'accepted']);
        $this->team->refresh();

        $this->survey = Survey::factory()->create(['user_id' => $this->other_team_member_user->id, 'team_id' => $this->team->id]);

        $this->questionnaire = Survey::factory()->create(['user_id' => $this->other_team_member_user->id, 'team_id' => $this->team->id, 'questionnaire_id' => '1']);
        
        $this->new_user_2_invite = User::factory()->create(['email' => 'invite@test.com', 'name'=>'invite_testUser']);
    }

    // public function test_questionnaire_delete_restore_forcedelete(){
    //     // delete('/questionnaire/destroy/{survey}
    //     $response = $this->actingAs($this->user)->delete('questionnaire/destroy/' . $this->survey->id );
    //     $response->assertStatus(403);

    //     $response = $this->actingAs($this->new_user_2_invite)->delete('questionnaire/destroy/' . $this->questionnaire->id );
    //     $response->assertStatus(403);

    //     $response = $this->actingAs($this->other_team_member_user)->delete('questionnaire/destroy/' . $this->questionnaire->id );
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('questionnaire_deleted');

    //     $response = $this->actingAs($this->user)->put('questionnaire/restore/' . $this->questionnaire->id );
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('questionnaire_restored');

    //     $response = $this->actingAs($this->user)->delete('questionnaire/destroy/' . $this->questionnaire->id );
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('questionnaire_deleted');

    //     $response = $this->actingAs($this->user)->delete('questionnaire/destroy/' . $this->questionnaire->id );
    //     $response->assertStatus(404);

    //     $response = $this->actingAs($this->user)->delete('questionnaire/forcedelete/' . $this->questionnaire->id );
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('questionnaire_forcedeleted');

    //     $response = $this->actingAs($this->user)->delete('questionnaire/forcedelete/' . $this->questionnaire->id );
    //     $response->assertStatus(404);
    // }

    // public function test_questionnaire_getreplica(){
    //     $this->assertDatabaseMissing('surveys', [
    //         'survey_title' => $this->questionnaire->survey_title,
    //         'questionnaire_id' => null,
    //     ]);

    //     $response = $this->actingAs($this->new_user_2_invite)->post('questionnaire/getreplica/' . $this->questionnaire->id );
    //     $response->assertStatus(403);

    //     $response = $this->actingAs($this->user)->post('questionnaire/getreplica/' . $this->questionnaire->id );
    //     $response->assertStatus(302);
        
    //     $this->assertDatabaseHas('surveys', [
    //         'survey_title' => $this->questionnaire->survey_title,
    //         'questionnaire_id' => null,
    //     ]);
    // }
    
}