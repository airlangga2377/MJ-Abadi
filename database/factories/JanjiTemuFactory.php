<?php
namespace Database\Factories;

use App\Models\JanjiTemu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JanjiTemuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JanjiTemu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tanggal = now()->addHours(rand(1, 100));
        return [
            'keluhan' => $this->faker->sentence,
            'tanggal' => $tanggal->format('Y-m-d H') . ':00',
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
