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
use function PHPUnit\Framework\assertNan;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;
use Ramsey\Uuid\Uuid;

class QuestionModelTest extends TestCase
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
    protected $question1_choice1;
    protected $question1_choice2;
    protected $question2_choice1;
    protected $question2_choice2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'example@test.com', 'name'=>'testUser']);
        $this->team = $this->user->teams->first();

        $this->questionnaire = Survey::factory()->create(['user_id' => $this->user->id, 'team_id' => $this->team->id, 'questionnaire_id' => '1']);

        $this->question1 = Question::factory()->create(['survey_id' => $this->questionnaire->id, 'question_text'=>'1st Question', 'question_type'=>"dropp-down", 'question_position'=>"1"]);

        $this->question2 = Question::factory()->create(['survey_id' => $this->questionnaire->id, 'question_text'=>'2nd Question', 'question_type'=>"dropp-down", 'question_position'=>"2"]);
        $this->questionnaire->refresh();
        $this->question1->refresh();
        $this->question2->refresh();
    }

    public function test_question_next_prev(){
        assertNull($this->question1->previousQuestion());
        assertTrue($this->question1->nextQuestion()->id === $this->question2->id);
        assertNull($this->question2->nextQuestion());
    }
}
