<?php

namespace Database\Factories\Survey;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;
use App\Models\Survey\Survey;

class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Survey::class;

    public function definition()
    {
        return [
            // 'id' => Uuid::uuid4(),
            'survey_title' => $this->faker->sentence(),
            'survey_description' => $this->faker->paragraphs(1, true),
            'cover_image_path' => null,
            'questionnaire_id' => null,
        ];
    }
}
