<?php

namespace Database\Factories\Survey;

use App\Models\Survey\Question;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Question::class;

    public function definition()
    {
        return [
            'id' => Uuid::uuid4(),
            'question_text' => $this->faker->sentence(),
            'cover_image_path' => null,
            'question_required' => false,
            'prefer_video' => false,
        ];
    }
}
