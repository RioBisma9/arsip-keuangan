<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    // Pastikan hanya user terotentikasi yang bisa mengakses
    public function __construct()
    {
        // Asumsi: Anda sudah mengaktifkan middleware 'auth'
        $this->middleware('auth');
    }

    // Metode Index utama untuk menampilkan isi folder atau root
    public function index(Folder $folder = null)
    {
        $breadcrumbPath = collect([]);
        $folders = collect(); 
        $files = collect(); 

        // 1. Logika untuk menentukan folder saat ini
        if (!$folder) {
            // ===================================
            // INI BAGIAN KRITISNYA: LEVEL ROOT
            // ===================================
            $current_folder = null;
            
            // Ambil SEMUA folder di level paling atas (parent_id NULL)
            // Menggunakan withCount('children') agar loading jumlah sub-folder cepat
            $folders = Folder::withCount('children')->whereNull('parent_id')->get();
            
        } else {
            // Level Sub-Folder
            $current_folder = $folder;

            // 2. Logika Breadcrumb Rekursif (agar path lengkap)
            $path = $current_folder;
            while ($path->parent) {
                $breadcrumbPath->prepend($path->parent);
                $path = $path->parent;
            }
            
            // 3. Ambil Folder Anak dan File dari folder saat ini
            $folders = $folder->children()->withCount('children')->get();
            $files = $folder->files; 
        }

        return view('folders.index', [
            'currentFolder' => $current_folder,
            'folders' => $folders,
            'files' => $files, 
            'breadcrumbPath' => $breadcrumbPath,
        ]);
    }

    // Method store untuk membuat Folder
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        Folder::create([
            'name' => $request->name,
            // 'user_id' => Auth::id(), // Jika sudah ada kolom user_id
            'parent_id' => $request->parent_id, 
        ]);

        if ($request->parent_id) {
            return redirect()->route('folders.index', ['folder' => $request->parent_id])
                             ->with('success', 'Folder "' . $request->name . '" berhasil dibuat.');
        } else {
            return redirect()->route('folders.index')
                             ->with('success', 'Folder "' . $request->name . '" berhasil dibuat di level utama.');
        }
    }
}