<?php

namespace Database\Seeders;

use App\Models\Folder;
use Illuminate\Database\Seeder;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // LEVEL 1: Kategori Utama (parent_id = NULL)
        $bendahara = Folder::create(['name' => 'UP Bendahara', 'parent_id' => null]);
        $spm = Folder::create(['name' => 'SPM', 'parent_id' => null]);
        $pnbp = Folder::create(['name' => 'PNBP', 'parent_id' => null]);
        // Laporan Keuangan

        // LEVEL 2: Sub-Kategori (Anak dari UP Bendahara)
        $kkp = Folder::create(['name' => 'KKP', 'parent_id' => $bendahara->id]);
        $rm = Folder::create(['name' => 'RM', 'parent_id' => $bendahara->id]);
        $tup = Folder::create(['name' => 'TUP', 'parent_id' => $bendahara->id]);

        // LEVEL 3: Tahun Arsip (Anak dari KKP)
        $tahun_2024 = Folder::create(['name' => '2024', 'parent_id' => $kkp->id]);
        $tahun_2023 = Folder::create(['name' => '2023', 'parent_id' => $kkp->id]);

        // LEVEL 4: Penyimpanan Fisik (Anak dari Tahun 2024)
        $box_1 = Folder::create(['name' => 'Box 1', 'parent_id' => $tahun_2024->id]);
        $box_2 = Folder::create(['name' => 'Box 2', 'parent_id' => $tahun_2024->id]);

        // LEVEL 5: Wadah Dokumen (Anak dari Box 1)
        $folder_a = Folder::create(['name' => 'Folder A', 'parent_id' => $box_1->id]);
        $folder_b = Folder::create(['name' => 'Folder B', 'parent_id' => $box_1->id]);

        // File HANYA akan bisa di-upload ke Folder A dan Folder B.
        // File tidak akan bisa di-upload ke Folder level 1, 2, 3, atau 4
        // karena hanya level 5 yang kita anggap "terbawah" (tidak punya anak folder lagi).
        
    }
}