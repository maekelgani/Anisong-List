@extends('layouts.admin')

@section('content')
<div x-data="userManager()">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 drop-shadow-md">Kelola User</h1>
            <p class="text-gray-400 mt-1">Mengelola akses kredensial admin.</p>
        </div>
    </div>

    <!-- Desktop Table -->
    <div class="hidden md:block bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-300">
                <thead class="bg-black/50 text-xs uppercase font-bold text-gray-500 border-b border-gray-800">
                    <tr>
                        <th class="p-4 tracking-wider">ID</th>
                        <th class="p-4 tracking-wider">Nama</th>
                        <th class="p-4 tracking-wider">Email</th>
                        <th class="p-4 tracking-wider">Role</th>
                        <th class="p-4 text-right tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="p-4 font-bold text-gray-500">#{{ $user->id }}</td>
                        <td class="p-4 font-semibold text-gray-200">{{ $user->name }}</td>
                        <td class="p-4 text-gray-400">{{ $user->email }}</td>
                        <td class="p-4">
                            @if($user->is_admin)
                                <span class="bg-[#9D00FF]/10 text-[#9D00FF] px-3 py-1 rounded-full border border-[#9D00FF]/30 text-xs font-bold">Admin</span>
                            @else
                                <span class="bg-gray-700/50 text-gray-300 px-3 py-1 rounded-full border border-gray-600 text-xs font-bold">User</span>
                            @endif
                        </td>
                        <td class="p-4 flex gap-3 justify-end">
                            <button @click="openModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')" class="text-yellow-500 hover:text-yellow-400 bg-yellow-500/10 hover:bg-yellow-500/20 px-4 py-2 rounded-lg transition-colors border border-transparent hover:border-yellow-500/30 text-sm font-bold flex items-center gap-2" title="Edit Akun">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Akun
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500 font-bold">
                            Belum ada data user.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile List Container -->
    <div class="block md:hidden space-y-4 mb-6">
        @forelse($users as $user)
            <div class="bg-gray-900/60 backdrop-blur-md border border-gray-800 rounded-2xl shadow-lg p-4 relative overflow-hidden group">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#9D00FF]/20 text-[#9D00FF] flex items-center justify-center font-bold shadow-[0_0_10px_rgba(157,0,255,0.2)]">
                            #{{ $user->id }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-white drop-shadow-sm">{{ $user->name }}</h3>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    @if($user->is_admin)
                        <span class="bg-[#9D00FF]/10 text-[#9D00FF] px-3 py-1 rounded-full border border-[#9D00FF]/30 text-xs font-bold">Admin</span>
                    @else
                        <span class="bg-gray-700/50 text-gray-300 px-3 py-1 rounded-full border border-gray-600 text-xs font-bold">User</span>
                    @endif
                </div>
                <!-- Actions -->
                <div class="border-t border-gray-800 pt-4">
                    <button @click="openModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')" class="w-full bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500 py-2 rounded-xl flex items-center justify-center gap-2 transition-colors min-h-[44px] font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Akun
                    </button>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500 font-bold bg-gray-900/60 rounded-2xl border border-gray-800 shadow-lg">
                Belum ada data user.
            </div>
        @endforelse
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm px-4" x-transition.opacity>
        <div @click.away="closeModal()" class="bg-gray-900 border border-[#9D00FF]/50 rounded-2xl shadow-[0_0_30px_rgba(157,0,255,0.2)] w-full max-w-md overflow-hidden" x-transition.scale>
            <div class="p-6 border-b border-gray-800 bg-black/40">
                <h2 class="text-xl font-bold text-white">Edit Kredensial User</h2>
                <p class="text-xs text-gray-400 mt-1" x-text="'Mengedit akun: ' + formData.name"></p>
            </div>
            <form :action="formAction" method="POST" class="p-6">
                @csrf
                <!-- Dynamic Method Spoofer for PUT -->
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Email</label>
                    <input type="email" name="email" x-model="formData.email" required class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Password Baru <span class="text-gray-600 font-normal">(opsional)</span></label>
                    <input type="password" name="password" class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" placeholder="Kosongkan jika tidak ingin ganti">
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-400 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full bg-black border border-gray-700 text-white text-base rounded-lg px-4 py-3 focus:outline-none focus:border-[#9D00FF] focus:ring-1 focus:ring-[#9D00FF] transition-all shadow-[inset_0_2px_4px_rgba(0,0,0,0.6)]" placeholder="Kosongkan jika tidak ingin ganti">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="closeModal()" class="px-5 py-2 rounded-lg font-bold text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="bg-[#9D00FF] text-white px-5 py-2 rounded-lg font-bold shadow-[0_0_15px_rgba(157,0,255,0.4)] hover:bg-[#b033ff] hover:shadow-[0_0_25px_rgba(157,0,255,0.8)] transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function userManager() {
    return {
        showModal: false,
        formAction: '',
        formData: {
            id: null,
            name: '',
            email: ''
        },
        openModal(id, name, email) {
            this.formData.id = id;
            this.formData.name = name;
            this.formData.email = email;
            this.formAction = `/admin/users/${id}`;
            this.showModal = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.showModal = false;
            document.body.style.overflow = 'auto';
        }
    }
}
</script>
@endpush
