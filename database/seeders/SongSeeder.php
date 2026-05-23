<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Song;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $songs = [
            [
                'franchise_id' => 1, // Naruto
                'anime_name' => 'Naruto Shippuden',
                'judul_lagu' => 'Blue Bird',
                'penyanyi' => 'Ikimonogakari',
                'tipe' => 'opening',
                'score' => 9.50,
                'link_video' => 'https://www.youtube.com/embed/o3ASICWeSLc',
                'tahun_rilis' => 2008,
                'peringkat' => 1,
            ],
            [
                'franchise_id' => 4, // Attack on Titan
                'anime_name' => 'Attack on Titan',
                'judul_lagu' => 'Guren no Yumiya',
                'penyanyi' => 'Linked Horizon',
                'tipe' => 'opening',
                'score' => 9.80,
                'link_video' => 'https://www.youtube.com/embed/8OkpRK2_gVs',
                'tahun_rilis' => 2013,
                'peringkat' => 2,
            ],
            [
                'franchise_id' => null, // Non franchise example
                'anime_name' => 'Oshi no Ko',
                'judul_lagu' => 'Idol',
                'penyanyi' => 'YOASOBI',
                'tipe' => 'opening',
                'score' => 9.90,
                'link_video' => 'https://www.youtube.com/embed/ZRtdQ81jPUQ',
                'tahun_rilis' => 2023,
                'peringkat' => 3,
            ]
        ];

        foreach ($songs as $song) {
            Song::create($song);
        }
    }
}
