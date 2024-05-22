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

class QuestionTest extends TestCase
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

    public function test_question_index(){
        $response = $this->actingAs($this->other_team_member_user)->get('surveys/survey/' . $this->survey->id . '/questions/',);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get('surveys/survey/' . $this->survey->id . '/questions/',);
        $response->assertStatus(200);

        $this->survey->delete();
        $this->survey->refresh();
        $response = $this->actingAs($this->user)->get('surveys/survey/' . $this->survey->id . '/questions/',);
        $response->assertStatus(404);
    }

    public function test_question_store_update(){
        $response = $this->actingAs($this->other_team_member_user)->post('questions/store/' . $this->survey->id ,
            [
            'question_text' => 'text',
            'question_required' => "",
            'question_type' => "radio-button",
            ]);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->post('questions/store/' . $this->survey->id ,
            [
            'question_text' => 'text',
            'question_required' => "",
            'question_type' => "radio-button",
            'youtube_id' => '000',
            'imageOrVideoSwitch' => 'on',
            ]);
        $response->assertStatus(302);
        $response->assertSessionHas('errors');

        $response = $this->actingAs($this->user)->post('questions/store/' . $this->survey->id ,
            [
            'question_text' => 'text',
            'question_required' => "",
            'question_type' => "radio",
            ]);
        $response->assertStatus(302);
        $response->assertSessionHas('errors');

        $response = $this->actingAs($this->user)->post('questions/store/' . $this->survey->id ,
            [
            'question_text' => 'text',
            'question_required' => "",
            'question_type' => "radio-button",
            ]);
        $response->assertStatus(302);

        $this->survey->delete();
        $this->survey->refresh();
        $response = $this->actingAs($this->user)->post('questions/store/' . $this->survey->id ,
            [
            'question_text' => 'text',
            'question_required' => "",
            'question_type' => "radio-button",
            'youtube_id' => 'XCXQRIbZOtg',
            'imageOrVideoSwitch' => 'on',
            ]);
        $response->assertStatus(404);
    }
    
}