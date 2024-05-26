<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team\Team;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use App\Models\Survey\Choice;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;
use Ramsey\Uuid\Uuid;

class ChoiceTest extends TestCase
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
    protected $other_survey;
    protected $question;
    protected $choice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'example@test.com', 'name'=>'testUser']);
        $this->team = $this->user->teams->first();
        $this->survey = Survey::factory()->create(['user_id' => $this->user->id, 'team_id' => $this->team->id]);
        $this->question = Question::factory()->create(['survey_id' => $this->survey->id, 'question_text'=>'1st Question', 'question_type'=>"dropp-down"]);
        $this->choice = Choice::factory()->create(['question_id' => $this->question->id, 'choice_text'=>'1st Choice', 'choice_position' => 1]);
        $this->other_team_member_user = User::factory()->create(['email' => 'new_example@test.com', 'name'=>'new_testUser']);
        $this->team->members()->attach($this->other_team_member_user->id, ['status' => 'accepted']);

        $this->team->refresh();
        $this->survey->refresh();
        $this->new_user_2_invite = User::factory()->create(['email' => 'invite@test.com', 'name'=>'invite_testUser']);

        $this->other_survey = Survey::factory()->create(['user_id' => $this->user->id, 'team_id' => $this->team->id]);
    }

    public function test_question_index(){
        $response = $this->actingAs($this->other_team_member_user)->get('surveys/survey/' . $this->survey->id . '/questions/' . $this->question->id);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get('surveys/survey/' . $this->survey->id . '/questions/' . $this->question->id);
        $response->assertStatus(200);

        $response = $this->actingAs($this->user)->get('surveys/survey/' . $this->other_survey->id . '/questions/' . $this->question->id);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get('surveys/survey/' . '1' . '/questions/' . $this->question->id);
        $response->assertStatus(404);

        $response = $this->actingAs($this->user)->get('surveys/survey/' . $this->survey->id . '/questions/' . 1);
        $response->assertStatus(404);

        $this->question->delete();
        $this->question->refresh();
        assertNotNull($this->question->deleted_at);

        $response = $this->actingAs($this->user)->get('surveys/survey/' . $this->survey->id . '/questions/' . $this->question->id);
        $response->assertStatus(404);


        $this->question->restore();
        $this->question->refresh();
        assertNull($this->question->deleted_at);
        $this->survey->delete();
        $this->survey->refresh();
        assertNotNull($this->survey->deleted_at);

        $response = $this->actingAs($this->user)->get('surveys/survey/' . $this->survey->id . '/questions/' . $this->question->id);
        $response->assertStatus(404);

    }

    // public function test_question_moveing(){




    // }

}
