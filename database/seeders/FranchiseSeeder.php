<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Franchise;

class FranchiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $franchises = [
            ['nama' => 'Naruto'],
            ['nama' => 'Bleach'],
            ['nama' => 'One Piece'],
            ['nama' => 'Attack on Titan'],
            ['nama' => 'Fullmetal Alchemist: Brotherhood'],
        ];

        foreach ($franchises as $franchise) {
            Franchise::create($franchise);
        }
    }
}
