<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use App\Http\Controllers\Controller;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduan = Pengaduan::orderBy('tgl_pengaduan', 'desc')->get(); 

        return view('Admin.Pengaduan.index', ['pengaduan' => $pengaduan]);
    }

    public function show($id_pengaduan)
    {
        $pengaduan = Pengaduan::where('id_pengaduan', $id_pengaduan)->first();

        $tanggapan = Tanggapan::where('id_pengaduan', $id_pengaduan)->first();

        return view('Admin.Pengaduan.show', ['pengaduan' => $pengaduan, 'tanggapan' => $tanggapan]);
    }
}
