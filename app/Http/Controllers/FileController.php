<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use App\Models\Folder; 


class FileController extends Controller
{
    /**
     * Menyimpan file yang diupload ke storage dan mencatatnya ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx', 
            'folder_id' => 'required|exists:folders,id',
        ]);

        // 2. Memproses File
        $uploadedFile = $request->file('file');
        
        // Simpan file menggunakan 'arsip_disk' ke folder 'files'
        // 'arsip_disk' menunjuk ke storage/app/private/public
        $path = Storage::disk('arsip_disk')->putFile('files', $uploadedFile);
        
        $storagePath = $path; 

        // 3. Catat ke Database
        File::create([
            'name' => $request->name,
            'folder_id' => $request->folder_id,
            'file_path' => $storagePath,
            
            // Perbaikan: Menambahkan KEY 'file_mime_type'
            'file_mime_type' => $uploadedFile->getMimeType(), 
        ]);

        // 4. Redirect dengan pesan sukses
        $folderId = $request->folder_id;
        
        if ($folderId) {
            return redirect()->route('folders.index', ['folder' => $folderId])
                             ->with('success', 'Dokumen "' . $request->name . '" berhasil diupload.');
        } 
        
        return redirect()->route('folders.index')
                         ->with('success', 'Dokumen "' . $request->name . '" berhasil diupload di level utama.');
    }
    
    // =========================================================================
    // METHOD: Untuk mendownload/menampilkan file secara aman via Controller
    // =========================================================================
    public function download(File $file)
    {
        // Menggunakan 'arsip_disk' yang menunjuk ke storage/app/private/public
        if (Storage::disk('arsip_disk')->exists($file->file_path)) {
            // download() memaksa browser mengunduh atau menampilkannya
            return Storage::disk('arsip_disk')->download($file->file_path, $file->name);
        }
        
        return abort(404, 'Dokumen tidak ditemukan di server.');
    }
}