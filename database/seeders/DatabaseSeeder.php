<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\Question;
use App\Models\Survey\Choice;
use App\Models\Team\Team;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('team_user')->truncate();
        DB::table('teams')->truncate();
        DB::table('surveys')->truncate();
        DB::table('questions')->truncate();
        DB::table('choices')->truncate();
        DB::table('answers')->truncate();

        $num_u = 4;
        $users = [];

        for ($i = 1; $i <= $num_u; $i++) {

            $current_user = User::factory()->create([
                'name' => 'user_'.$i,
                'email' => 'user'.$i.'@user.com',
            ]);
            $users[] = $current_user->id;
        }

        for ($i = 0; $i <$num_u; $i++) {
            $current_user_id = $users[$i];

            $current_team = Team::factory()->create([
                'team_name' => 'team_'.$i,
                'user_id' => $current_user_id,
            ]);
            $current_team->members()->attach($current_user_id, ['status' => 'accepted']);

            $next_user_id = $users[($i+1)%$num_u];
            $current_team->members()->attach($next_user_id, ['status' => 'accepted']);


            $next_user_id = $users[($i+2)%$num_u];
            $current_team->members()->attach($next_user_id, ['status' => 'pending']);

            $num_s = rand(3, 4);
            for ($j = 1; $j <= $num_s; $j++) {
                if($j<=2){

                    $current_survey = Survey::factory()->create([
                        'survey_title' => $j . '. survey for the ' . $i . '. user',
                        'user_id' => $current_user_id,
                        'team_id' => $current_team->id,

                    ]);
                }else{
                    $current_survey = Survey::factory()->create([
                        'survey_title' => $j . '. survey for the ' . $i . '. user',
                        'user_id' => $current_user_id,
                        'team_id' => $current_team->id,
                        'questionnaire_id' => Uuid::uuid4(),
                    ]);
                }

                $num_q = rand(5, 6);

                $allowedQuestionTypes = Question::getAllowedQuestionTypes();
                for ($k = 1; $k <= $num_q; $k++) {
                    $do_video = rand(0, 1);
                    $required = rand(0, 1);
                    $current_question = Question::factory()->create([
                        'survey_id' => $current_survey->id,
                        'question_position'=> $k,
                        'question_required' => $required == 1 ? true : false,
                        'question_text'=> $k . '. question of the ' . $j .'. survey ' . 'for the ' . $i.'. user',
                        "question_type"=> $allowedQuestionTypes[($k-1) % 4],
                        'youtube_id' => $do_video == 1 ? '1O0yazhqaxs' : null,
                        'prefer_video' => $do_video == 1 ? true : false,
                        'video_no_controlls' => $required == 1 ? true : false,
                    ]);



                    $num_o = rand(3, 4);
                    for ($l = 1; $l <= $num_o; $l++) {

                        Choice::factory()->create([
                            'question_id' => $current_question->id,
                            'choice_position'=> $l,
                            'choice_text'=> $l . '. choice of the ' . $k . '. question ' . 'of the ' . $j .'. survey ' . 'for the ' . $i.'. user',
                        ]);
                    }

                }
            }

        }

    }
}
