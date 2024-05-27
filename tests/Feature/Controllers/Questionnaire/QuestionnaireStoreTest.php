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

class QuestionnaireStoreTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use RefreshDatabase;


    protected $user;
    protected $team;
    protected $questionnaire;
    protected $question1;
    protected $question2;
    protected $question3;
    protected $question1_choice1;
    protected $question1_choice2;
    protected $question2_choice1;
    protected $question2_choice2;
    protected $question3_choice1;
    protected $question3_choice2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'example@test.com', 'name'=>'testUser']);
        $this->team = $this->user->teams->first();

        $this->questionnaire = Survey::factory()->create(['user_id' => $this->user->id, 'team_id' => $this->team->id, 'questionnaire_id' => '1']);

        $this->question1 = Question::factory()->create(['survey_id' => $this->questionnaire->id, 'question_text'=>'1st Question', 'question_type'=>"dropp-down", 'question_position'=>1]);
        $this->question1_choice1 = Choice::factory()->create(['question_id' => $this->question1->id, 'choice_text'=>'1st Choice for 1st Question', 'choice_position' => 1]);
        $this->question1_choice2 = Choice::factory()->create(['question_id' => $this->question1->id, 'choice_text'=>'2nd Choice for 1st Question', 'choice_position' => 2]);

        $this->question2 = Question::factory()->create(['survey_id' => $this->questionnaire->id, 'question_text'=>'2nd Question', 'question_type'=>"mutiple-choice", 'question_position'=>2]);
        $this->question2_choice1 = Choice::factory()->create(['question_id' => $this->question2->id, 'choice_text'=>'1st Choice for 2st Question', 'choice_position' => 1]);
        $this->question2_choice2 = Choice::factory()->create(['question_id' => $this->question2->id, 'choice_text'=>'2nd Choice for 2st Question', 'choice_position' => 2]);

        $this->question3 = Question::factory()->create(['survey_id' => $this->questionnaire->id, 'question_text'=>'2nd Question', 'question_type'=>"open", 'question_position'=>3]);
        $this->question3_choice1 = Choice::factory()->create(['question_id' => $this->question3->id, 'choice_text'=>'1st Choice for 2st Question', 'choice_position' => 1]);
        $this->question3_choice2 = Choice::factory()->create(['question_id' => $this->question3->id, 'choice_text'=>'2nd Choice for 2st Question', 'choice_position' => 2]);

        $this->questionnaire->refresh();
        $this->question1->refresh();
        $this->question2->refresh();
    }

    public function test_storeAnswer_dropp_down(){
        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['choice_id'=>$this->question2_choice1] );
        $response->assertStatus(419);

        $this->assertDatabaseMissing('answers', [
            'responder_id' => 'alma', 'choice_id' => $this->question1_choice1,  ]);
        $this->withSession(['responder_id' => 'alma']);
        $this->withSession(['question' => $this->question1]);
        $this->withSession(['question_started_at' => "2020.02.02 14:20:00"]);

        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['choice_id'=>$this->question2_choice1] );
        $response->assertStatus(302);
        $response->assertSessionHas('errors');

        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['choice_id'=>$this->question1_choice1->id] );
        $response->assertStatus(302);
        $this->assertDatabaseHas('answers', [
            'responder_id' => 'alma', 'choice_id' => $this->question1_choice1->id, ]);

    }

    public function test_storeAnswer_mutiple_choice(){
        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['choice_id'=>$this->question2_choice1] );
        $response->assertStatus(419);

        #--------------------
        $this->assertDatabaseMissing('answers', [
            'responder_id' => 'alma', 'choice_id' => $this->question2_choice2, ]);
        $this->withSession(['responder_id' => 'alma']);
        $this->withSession(['question' => $this->question2]);
        $this->withSession(['question_started_at' => "2020.02.02 14:20:00"]);

        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['choice_ids'=>[$this->question3_choice1->id]]);
        $response->assertStatus(302);
        $response->assertSessionHas('errors');

        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['choice_ids'=>[$this->question2_choice2->id]] );
        $response->assertStatus(302);
        $this->assertDatabaseHas('answers', [
            'responder_id' => 'alma', 'choice_id' => $this->question2_choice2->id, 'answer_text' => $this->question2_choice2->choice_text]);
    }

    public function test_storeAnswer_open(){
        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['choice_id'=>$this->question2_choice1] );
        $response->assertStatus(419);

        #--------------------
        $this->assertDatabaseMissing('answers', [
            'responder_id' => 'alma', 'choice_id' => $this->question3_choice2, 'answer_text' => 'alma' ]);
        $this->withSession(['responder_id' => 'alma']);
        $this->withSession(['question' => $this->question3]);
        $this->withSession(['question_started_at' => "2020.02.02 14:20:00"]);

        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['open_ids_texts'=>[$this->question1_choice1->id => "alma", $this->question3_choice1->id => ""]]);
        $response->assertStatus(302);
        $response->assertSessionHas('errors');
        $this->assertDatabaseMissing('answers', [
            'responder_id' => 'alma', 'choice_id' => $this->question3_choice2->id, 'answer_text' => 'alma']);

        $response = $this->post('/questionnaire/store/' . $this->questionnaire->questionnaire_id,
        ['open_ids_texts'=>[$this->question3_choice2->id => "alma", $this->question3_choice1->id => ""]] );
        $response->assertStatus(302);
        $this->assertDatabaseHas('answers', [
            'responder_id' => 'alma', 'choice_id' => $this->question3_choice2->id, 'answer_text' => 'alma']);
    }
}
