<?php

namespace App\Imports;

use App\Models\Song;
use App\Models\Franchise;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SongsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                if (!isset($row['no']) || !isset($row['song_title']) || !isset($row['category'])) {
                    continue; 
                }

                $kategoriRaw = strtolower(trim($row['category']));
                $kategori = 'opening';
                if ($kategoriRaw === 'op' || $kategoriRaw === 'opening') $kategori = 'opening';
                elseif ($kategoriRaw === 'ed' || $kategoriRaw === 'ending') $kategori = 'ending';
                elseif ($kategoriRaw === 'movie') $kategori = 'movie';

                $rankingBaru = (int) $row['no'];
                $franchiseName = trim($row['franchise'] ?? '');
                
                $franchiseId = null;
                if (!empty($franchiseName)) {
                    $franchise = Franchise::firstOrCreate(['nama' => $franchiseName]);
                    $franchiseId = $franchise->id;
                }

                Song::where('tipe', $kategori)
                    ->where('peringkat', '>=', $rankingBaru)
                    ->increment('peringkat');

                Song::create([
                    'peringkat' => $rankingBaru,
                    'tahun_rilis' => $row['years'] ?? date('Y'),
                    'anime_name' => $row['anime_title'] ?? '',
                    'judul_lagu' => $row['song_title'],
                    'penyanyi' => $row['singerband'] ?? '',
                    'score' => $row['score'] ?? 0,
                    'link_video' => $row['link'] ?? '',
                    'tipe' => $kategori,
                    'franchise_id' => $franchiseId,
                ]);
            }
        });
    }
}
