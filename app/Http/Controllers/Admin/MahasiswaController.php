<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::all();

        return view('admin.Mahasiswa.index', ['mahasiswa' => $mahasiswa]);
    }

    public function show ($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        return view('Admin.Mahasiswa.show', ['mahasiswa' => $mahasiswa]);
    }
}
