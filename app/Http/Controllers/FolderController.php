<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Menampilkan daftar folder Level 1 (tanpa parent) untuk user yang login.
     */
    public function index(Folder $folder = null)
    {
        // Mendapatkan ID user yang sedang login
        $userId = Auth::id();

        // Query untuk Folder Level 1 (parent_id = NULL)
        // Pastikan hanya mengambil folder milik user saat ini
        $folders = Folder::where('user_id', $userId)
            ->whereNull('parent_id') // Filter hanya folder Level 1
            ->latest() // Urutkan berdasarkan yang terbaru
            ->get();
            
        // Jika Anda ingin mengambil file-file di folder Level 1 (saat folder belum diklik)
        // Kita lewati dulu, fokus ke folder dulu.

        // Melewatkan data folder ke view
        return view('folders.index', [
            'currentFolder' => null, // Karena ini tampilan Level 1, tidak ada parent
            'folders' => $folders,
        ]);
    }

    /**
     * Menyimpan folder baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // parent_id akan NULL jika ini folder Level 1
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $folder = new Folder();
        $folder->user_id = Auth::id(); // Wajib diisi user ID
        $folder->name = $request->name;
        $folder->parent_id = $request->parent_id; 
        $folder->save();

        // Redirect kembali ke halaman folder utama
        return redirect()->route('folders.index')
            ->with('success', 'Folder "' . $folder->name . '" berhasil dibuat.');
    }
}