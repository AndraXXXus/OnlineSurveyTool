<?php

namespace Database\Factories\Survey;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;
use App\Models\Survey\Answer;

class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Answer::class;

    public function definition()
    {
        return [
            // 'id' => Uuid::uuid4(),
            'answer_text' => $this->faker->text(5),
        ];
    }
}
