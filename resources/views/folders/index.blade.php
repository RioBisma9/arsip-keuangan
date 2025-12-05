<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Folder Utama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Sukses dari Controller --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Tombol Tambah Folder --}}
            <div class="mb-4 flex justify-end">
                <a href="#" onclick="document.getElementById('create-folder-form').style.display = 'block';" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                    + Tambah Folder
                </a>
            </div>

            {{-- Form Tambah Folder (Modal Sederhana) --}}
            <div id="create-folder-form" class="bg-white p-6 rounded-lg shadow-xl mb-6 hidden">
                <h3 class="text-lg font-semibold mb-4">Buat Folder Baru</h3>
                <form action="{{ route('folders.store') }}" method="POST">
                    @csrf
                    {{-- Input Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Folder</label>
                        <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    {{-- Input parent_id (Hidden, untuk Level 1 biarkan NULL) --}}
                    {{-- Ini memastikan folder yang dibuat adalah folder utama --}}
                    <input type="hidden" name="parent_id" value=""> 

                    {{-- Tombol Simpan & Batal --}}
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('create-folder-form').style.display = 'none';" class="py-2 px-4 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
                            Simpan Folder
                        </button>
                    </div>
                </form>
            </div>

            {{-- Daftar Folder --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Folder Utama Anda</h3>
                
                @if ($folders->isEmpty())
                    <p class="text-gray-500 italic">Belum ada folder utama yang dibuat. Klik "+ Tambah Folder" untuk memulai.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- LOOPING DATA FOLDER DI SINI --}}
                        @foreach ($folders as $folder)
                            {{-- Tautan untuk masuk ke folder tersebut --}}
                            <a href="{{ route('folders.index', ['folder' => $folder->id]) }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-indigo-50 transition duration-150 ease-in-out">
                                <div class="flex items-center">
                                    {{-- Ikon Folder --}}
                                    <svg class="w-6 h-6 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                    </svg>
                                    {{-- Nama Folder --}}
                                    <span class="font-medium text-gray-900">{{ $folder->name }}</span>
                                </div>
                            </a>
                        @endforeach
                        {{-- AKHIR LOOPING --}}
                    </div>
                @endif
            </div>

            {{-- Daftar File (Saat ini di Level 1 tidak ada, jadi hanya placeholder) --}}
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                 <h3 class="text-lg font-semibold mb-4 border-b pb-2">File di Level Ini</h3>
                 <p class="text-gray-500 italic">Tidak ada file di level utama. Klik folder untuk melihat isinya.</p>
            </div>
            
        </div>
    </div>
</x-app-layout>