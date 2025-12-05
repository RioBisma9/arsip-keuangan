<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Menampilkan daftar folder dan file di dalam folder saat ini.
     * $folder adalah parameter opsional. Jika NULL, berarti kita di root (Level 1).
     */
    public function index(Folder $folder = null)
    {
        $userId = Auth::id();

        // 1. Verifikasi Kepemilikan (Jika folder diklik)
        if ($folder && $folder->user_id !== $userId) {
            // Jika user mencoba mengakses folder milik orang lain, lempar error 403 (Forbidden)
            abort(403, 'Akses ditolak: Anda tidak memiliki folder ini.');
        }

        // 2. Tentukan Folder Induk untuk Query
        // Jika $folder ada, kita mencari subfolder di dalamnya.
        // Jika $folder NULL, kita mencari folder Level 1 (parent_id = NULL).
        $parentId = $folder ? $folder->id : null;

        // 3. Ambil Subfolder/Folder Level 1
        $folders = Folder::where('user_id', $userId)
            ->where('parent_id', $parentId) // Menggunakan parent_id yang ditentukan
            ->latest()
            ->get();
            
        // 4. Ambil File (Saat ini kita abaikan dulu, fokus ke folder)
        $files = collect(); // Koleksi kosong untuk file

        // 5. Kirim ke View
        return view('folders.index', [
            // Variabel untuk folder yang sedang dibuka (null jika di root)
            'currentFolder' => $folder, 
            'folders' => $folders,
            'files' => $files, // File yang ada di folder ini
        ]);
    }

    /**
     * Menyimpan folder baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // parent_id adalah ID folder yang sedang dibuka saat ini (bisa NULL)
            'parent_id' => 'nullable|exists:folders,id', 
        ]);

        $folder = new Folder();
        $folder->user_id = Auth::id(); 
        $folder->name = $request->name;
        $folder->parent_id = $request->parent_id; // Simpan parent_id dari form
        $folder->save();

        // Tentukan redirect berdasarkan apakah folder baru ini adalah subfolder atau folder utama
        $redirectRoute = $folder->parent_id 
                         ? route('folders.index', ['folder' => $folder->parent_id]) 
                         : route('folders.index');

        // Redirect kembali ke lokasi tempat folder baru dibuat
        return redirect($redirectRoute)
            ->with('success', 'Folder "' . $folder->name . '" berhasil dibuat.');
    }
}