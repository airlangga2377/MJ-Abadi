<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\JanjiTemu;
use Faker\Factory as Faker;

class JanjiTemuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\JanjiTemu::factory()->count(5)->create();
    }
}
