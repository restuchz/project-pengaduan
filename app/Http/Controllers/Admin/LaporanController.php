<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.Laporan.index');
    }

    public function getLaporan(Request $request)
    {
        $from = $request->from . ' ' . '00:00:00';
        $to = $request->to . ' ' . '23:59:59';

        $pengaduan = Pengaduan::whereBetween('tgl_pengaduan', [$from, $to])->get();

        return view('admin.Laporan.index', ['pengaduan' => $pengaduan, 'from' => $from, 'to' => $to]);
    }

    public function cetakLaporan($from, $to)
    {
        $pengaduan = Pengaduan::whereBetween('tgl_pengaduan', [$from, $to])->get();

        $pdf = Pdf::loadView('admin.Laporan.cetak', ['pengaduan' => $pengaduan]);

        return $pdf->download('Laporan-Pengaduan.pdf');
    }
}
