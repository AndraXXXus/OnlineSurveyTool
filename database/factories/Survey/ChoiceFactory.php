<?php

namespace Database\Factories\Survey;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;
use App\Models\Survey\Choice;

class ChoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Choice::class;

    public function definition()
    {
        return [
            // 'id' => Uuid::uuid4(),
            'choice_text' => $this->faker->sentence(),
            'cover_image_path' => null,
        ];
    }
}
