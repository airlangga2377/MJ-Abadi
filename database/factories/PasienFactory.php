<?php
namespace Database\Factories;

use App\Models\Pasien;
use App\Models\JanjiTemu;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasienFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pasien::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'diagnosa' => $this->faker->sentence,
            'penanganan' => $this->faker->sentence,
            'keterangan' => $this->faker->paragraph,
            'janji_temu_id' => JanjiTemu::inRandomOrder()->first()->id,
        ];
    }
}
