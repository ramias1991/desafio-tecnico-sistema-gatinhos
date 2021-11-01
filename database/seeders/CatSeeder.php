<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cat;

class CatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cat::create([
            'name' => 'Gatinho Teste',
            'description' => 'Alguma descrição de teste',
            'image' => 'https://www.google.com/google.jpg'
        ]);
    }
}
