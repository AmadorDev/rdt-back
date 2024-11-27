<?php

namespace Database\Seeders;

use App\Models\Linea;
use Illuminate\Database\Seeder;

class LineaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lineas = [
            ["category_id" => 1, "name" => "FILLER COLOR", "description" => "Revolución del color formulada con su exclusiva tecnología HYALURON COMPLEX, brinda juventud, vida y COBERTURA ABOSLUTA de color a cada hebra capilar.
Cabello mas sano, fuerte, brilloso, hidratado, rejuvenecido y con un color vibrante por mucho mas tiempo.
", "slug" => "filler-color", "language" => 'es-ES'],
            ["category_id" => 1, "name" => "LIFE IN COLOR", "description" => "x", "slug" => "life-in-color", "language" => 'es-ES'],

            ["category_id" => 2, "name" => "PRO CLEAN & SOFT", "description" => "x", "slug" => "pro-clean-&-soft", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "PRO BALANCE", "description" => "x", "slug" => "pro-balance", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "PRO FILLER", "description" => "x", "slug" => "pro-filler", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "PRO LUMINOUS", "description" => "x", "slug" => "pro-luminous", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "PRO REPAIR", "description" => "x", "slug" => "pro-repair", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "PRO NUTRITIVE", "description" => "x", "slug" => "pro-nutritive", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "POST LISS", "description" => "x", "slug" => "post-liss", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "PRO RESIST", "description" => "x", "slug" => "pro-resist", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "PRO CURLY", "description" => "x", "slug" => "pro-curly", "language" => 'es-ES'],
            ["category_id" => 2, "name" => "PRO EFFECTIVE", "description" => "x", "slug" => "pro-efective", "language" => 'es-ES'],

        ];

        Linea::insert($lineas);
    }
}
