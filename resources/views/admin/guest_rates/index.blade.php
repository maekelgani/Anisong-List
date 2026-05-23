@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">List Guest Rates</h1>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-4 border-b">Nama Guest</th>
                <th class="p-4 border-b">Tipe Rate</th>
                <th class="p-4 border-b">Top</th>
                <th class="p-4 border-b">Rata-Rata Skor</th>
                <th class="p-4 border-b">Tanggal</th>
                <th class="p-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $session)
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-4">{{ $session->nama_guest }}</td>
                <td class="p-4 capitalize">{{ $session->tipe_rate }}</td>
                <td class="p-4">{{ $session->limit_top }}</td>
                <td class="p-4">{{ number_format($session->rata_rata_score, 2) }}</td>
                <td class="p-4">{{ $session->created_at->format('d M Y H:i') }}</td>
                <td class="p-4">
                    <a href="{{ route('admin.guest_rates.show', $session) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-4 text-center">Belum ada rating dari guest.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
