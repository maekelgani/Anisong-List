<?php

namespace App\Exports;

use App\Models\Song;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SongsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $templateOnly;

    public function __construct($templateOnly = false)
    {
        $this->templateOnly = $templateOnly;
    }

    public function collection()
    {
        if ($this->templateOnly) {
            return collect([]);
        }

        return Song::with('franchise')
            ->orderBy('tipe')
            ->orderBy('peringkat')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Years',
            'Anime Title',
            'Song Title',
            'Singer/Band',
            'Score',
            'LINK',
            'Category',
            'Franchise',
        ];
    }

    public function map($song): array
    {
        return [
            $song->peringkat,
            $song->tahun_rilis,
            $song->anime_name,
            $song->judul_lagu,
            $song->penyanyi,
            $song->score,
            $song->link_video,
            $song->tipe,
            $song->franchise ? $song->franchise->nama : '',
        ];
    }
}
