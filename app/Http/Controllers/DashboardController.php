<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Mungkin tidak diperlukan, tapi baik untuk ada

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard setelah user login.
     */
    public function index()
    {
        // Biasanya mengembalikan view 'dashboard'
        return view('dashboard');
    }
}