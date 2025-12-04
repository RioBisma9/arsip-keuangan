<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    // Konstruktor: Pastikan hanya user yang terautentikasi yang bisa mengakses fungsi di Controller ini
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Fungsi untuk menampilkan daftar folder dan file
    public function index(Folder $folder = null)
    {
        $currentFolder = $folder;
        $userId = Auth::id();

        // Tentukan ID induk yang sedang dilihat. 
        // Jika $currentFolder is null (halaman utama), maka parent_id yang dicari adalah null.
        $parentIdToSearch = $currentFolder ? $currentFolder->id : null;

        // 1. Ambil folder anak (Sub-folders)
        $folders = Folder::where('user_id', $userId) // Filter berdasarkan user yang sedang login
            ->where('parent_id', $parentIdToSearch) // Kunci: Filter berdasarkan parent_id (NULL untuk Level 1)
            ->withCount('children') // Untuk menghitung sub-folder
            ->get();

        // 2. Ambil file di folder saat ini
        $files = File::where('user_id', $userId)
            ->where('folder_id', $parentIdToSearch)
            ->get();

        // 3. Logika Breadcrumb (Navigasi)
        $breadcrumbPath = collect();
        $tempFolder = $currentFolder;

        // Melakukan loop ke atas (parent) untuk membangun path
        while ($tempFolder) {
            $parent = $tempFolder->parent; // Menggunakan relasi parent() yang sudah kita definisikan
            
            if ($parent) {
                 // Menambahkan di depan agar urutannya dari root ke bawah
                $breadcrumbPath->prepend($parent);
            }
            // Lanjut ke folder induk berikutnya, atau berhenti jika sudah di root (parent_id null)
            $tempFolder = $parent;
        }

        return view('folders.index', compact('folders', 'files', 'currentFolder', 'breadcrumbPath'));
    }

    // Fungsi untuk menyimpan folder baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // parent_id harus ada di tabel folders jika tidak NULL
            'parent_id' => 'nullable|exists:folders,id', 
        ]);

        $folder = new Folder();
        $folder->name = $request->name;
        $folder->parent_id = $request->parent_id;
        $folder->user_id = Auth::id(); // PENTING: Kaitkan dengan user yang login
        $folder->save();

        // Arahkan kembali ke folder induk atau halaman utama (jika parent_id null)
        $redirectId = $request->parent_id ?? null;
        
        return redirect()->route('folders.index', $redirectId)
            ->with('success', 'Folder "' . $folder->name . '" berhasil dibuat.');
    }
}