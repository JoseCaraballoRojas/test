<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'taste' => 'Chocolate',
                'letter' => 'C',
                'value' => 1.20,
            ],
            [
                'taste' => 'Dulce de leche',
                'letter' => 'D',
                'value' => 1.10,
            ],
            [
                'taste' => 'Frutilla',
                'letter' => 'F',
                'value' => 0.80,
            ],
            [
                'taste' => 'Limón',
                'letter' => 'L',
                'value' => 0.70,
            ],
            [
                'taste' => 'Merengue',
                'letter' => 'M',
                'value' => 2.05,
            ],
            [
                'taste' => 'Nueves',
                'letter' => 'N',
                'value' => 2.85,
            ],
            
        ]);
    }
}
