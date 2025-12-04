    @extends('layouts.app')

    @section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- A. Notifikasi (Untuk pesan success dari Controller) --}}
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        {{-- A. Header dan Breadcrumb --}}
        <div class="mb-6 bg-white p-4 rounded-lg shadow-md border-t-4 border-blue-600">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                @if($currentFolder)
                {{ $currentFolder->name }}
                @else
                Arsip Induk (Level 1)
                @endif
            </h1>

            {{-- LOGIKA BREADCRUMB DINAMIS --}}
            <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex">
                    {{-- Link Home (Root) --}}
                    <li class="flex items-center">
                        <a href="{{ route('folders.index') }}" class="text-blue-600 hover:text-blue-800">Home</a>
                        @if($currentFolder)
                        <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M7 16l-4-4 4-4 1.5 1.5L5.83 12H16v2H5.83l2.67 2.67z" />
                        </svg>
                        @endif
                    </li>

                    {{-- Loop untuk folder INDUK --}}
                    @foreach($breadcrumbPath as $parentFolder)
                    <li class="flex items-center">
                        <a href="{{ route('folders.index', $parentFolder->id) }}" class="text-blue-600 hover:text-blue-800">{{ $parentFolder->name }}</a>
                        <svg class="fill-current w-3 h-3 mx-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M7 16l-4-4 4-4 1.5 1.5L5.83 12H16v2H5.83l2.67 2.67z" />
                        </svg>
                    </li>
                    @endforeach

                    {{-- Folder Saat Ini (Aktif/Terakhir) --}}
                    @if($currentFolder)
                    <li class="flex items-center">
                        <span class="text-gray-700">{{ $currentFolder->name }}</span>
                    </li>
                    @endif
                </ol>
            </nav>
        </div>

        {{-- B. Tombol Aksi (Tombol Tambah Folder SELALU muncul) --}}
        <div class="flex justify-end mb-6 space-x-3">
            {{-- Tombol Tambah Folder --}}
            <button
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out shadow-lg"
                onclick="document.getElementById('modal-tambah-folder').classList.remove('hidden')">
                + Tambah Folder
            </button>

            {{-- Tombol Upload File (Hanya muncul jika berada di folder anak DAN folder itu tidak punya sub-folder) --}}
            @if ($currentFolder && $folders->isEmpty())
            <button
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out shadow-lg"
                onclick="document.getElementById('modal-upload-file').classList.remove('hidden')">
                + Upload Dokumen
            </button>
            @endif


            {{-- C. Daftar Folder Anak --}}
            <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Folder ({{ $folders->count() }})</h3>
                </div>

                @if($folders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Nama Folder</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Sub-Folder</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($folders as $folderItem)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('folders.index', $folderItem->id) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                        </svg>
                                        {{ $folderItem->name }}
                                    </a>
                                </td>
                                {{-- Menggunakan 'children_count' yang cepat dari Controller --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $folderItem->children_count }} Sub-Folder
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <!-- Aksi Folder (Edit/Delete) jika ada -->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="px-6 py-4 text-center text-sm text-gray-500">
                    @if($currentFolder)
                    Folder ini belum memiliki sub-folder.
                    @else
                    Belum ada folder utama yang dibuat. Gunakan tombol 'Tambah Folder' di atas.
                    @endif
                </div>
                @endif


            </div>

            {{-- D. Daftar File (Hanya ditampilkan jika ada file) --}}
            @if($files->isNotEmpty())
            <h2 class="text-xl font-semibold text-gray-700 mb-3 mt-8">Dokumen Tersimpan ({{ $files->count() }})</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diunggah</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($files as $file)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{-- Logika Icon Berdasarkan Tipe File --}}
                                @php
                                $iconClass = 'fa-file text-gray-500'; // Default
                                if (str_contains($file->file_mime_type, 'pdf')) {
                                $iconClass = 'fa-file-pdf text-red-600';
                                } elseif (str_contains($file->file_mime_type, 'image')) {
                                $iconClass = 'fa-file-image text-blue-600';
                                } elseif (str_contains($file->file_mime_type, 'word') || str_contains($file->file_mime_type, 'document')) {
                                $iconClass = 'fa-file-word text-blue-800';
                                } elseif (str_contains($file->file_mime_type, 'spreadsheet')) {
                                $iconClass = 'fa-file-excel text-green-600';
                                }
                                @endphp
                                <i class="fas {{ $iconClass }} fa-lg mr-2"></i>
                                {{ $file->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ strtoupper(pathinfo($file->file_path, PATHINFO_EXTENSION)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $file->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                {{-- Link Download yang SUDAH BENAR --}}
                                <a href="{{ route('files.download', $file->id) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-900 font-semibold"
                                    title="Download Dokumen">
                                    Lihat/Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @elseif ($currentFolder && $files->isEmpty())
            <p class="text-gray-500 italic mt-4">Belum ada dokumen yang diupload di folder ini.</p>
            @endif

        </div>

        {{-- E. Form Modal Tambah Folder (Diletakkan di luar kontainer utama agar pop-up) --}}
        <div id="modal-tambah-folder" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Folder Baru</h3>
                    <div class="mt-2 px-7 py-3">
                        <form action="{{ route('folders.store') }}" method="POST">
                            @csrf

                            {{-- Input Parent ID yang Tersembunyi --}}
                            <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? null }}">

                            <div class="mb-4">
                                <label for="name" class="block text-left text-sm font-medium text-gray-700">Nama Folder</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                    placeholder="Contoh: Dokumen Juni">
                            </div>

                            {{-- Tampilkan Error Validasi (Jika validasi gagal) --}}
                            @error('name')
                            <p class="text-red-500 text-xs italic text-left">{{ $message }}</p>
                            @enderror

                            <div class="items-center px-4 py-3">
                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    Simpan Folder
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="items-center px-4 py-1">
                        <button id="closeModal" onclick="document.getElementById('modal-tambah-folder').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- F. Form Modal Upload Dokumen (BARU) --}}
        <div id="modal-upload-file" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Upload Dokumen Baru</h3>
                    <div class="mt-2 px-7 py-3">
                        {{-- Form action ini hanya placeholder. Nanti kita buat route-nya --}}
                        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Input Folder ID (ID folder saat ini) --}}
                            <input type="hidden" name="folder_id" value="{{ $currentFolder->id ?? null }}">

                            <div class="mb-4">
                                <label for="file_name" class="block text-left text-sm font-medium text-gray-700">Nama Dokumen</label>
                                <input type="text" name="name" id="file_name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2"
                                    placeholder="Contoh: Invoice Bulan April">
                            </div>

                            <div class="mb-4">
                                <label for="file_upload" class="block text-left text-sm font-medium text-gray-700">Pilih File (PDF, DOCX, dll.)</label>
                                <input type="file" name="file" id="file_upload" required
                                    class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2">
                            </div>

                            <div class="items-center px-4 py-3">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Upload Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="items-center px-4 py-1">
                        <button id="closeUploadModal" onclick="document.getElementById('modal-upload-file').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @endsection