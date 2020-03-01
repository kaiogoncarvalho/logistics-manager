<?php

use Illuminate\Database\Seeder;
use App\Models\Truck;

class TripsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Truck::updateOrCreate(
            ['id' => 1],
            [
                'name'     => 'Caminhão 3/4'
            ]
        );
    
        Truck::updateOrCreate(
            ['id' => 2],
            [
                'name'     => 'Caminhão Troco'
            ]
        );
    
        Truck::updateOrCreate(
            ['id' => 3],
            [
                'name'     => 'Caminhão Truck'
            ]
        );
    
        Truck::updateOrCreate(
            ['id' => 4],
            [
                'name'     => 'Caminhão Simples'
            ]
        );
    
        Truck::updateOrCreate(
            ['id' => 5],
            [
                'name'     => 'Caminhão Eixo Estendido'
            ]
        );
    }
}
