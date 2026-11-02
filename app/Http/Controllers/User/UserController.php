<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        return view('user.landing');
    }

    public function login(Request $request)
    {
        $username = Mahasiswa::where('username', $request->username)->first();

        if (!$username) {
            return redirect()->back()->with(['pesan' => 'Username tidak terdaftar']);
        }

        $password = Hash::check($request->password, $username->password);

        if (!$password) {
            return redirect()->back()->with(['pesan' => 'Password tidak sesuai']);
        }

        if (Auth::guard('mahasiswa')->attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect()->back();
        } else {
            return redirect()->back()->with(['pesan' => 'Akun tidak terdaftar!']);
        }
    }

    public function formRegister()
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        $messages = [
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex' => 'NIM harus terdiri dari 8 digit.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'telp.required' => 'Nomor telepon wajib diisi.',
        ];

        $validate = Validator::make($request->all(), [
            'nim' => ['required', 'regex:/^\d{8,}$/'],
            'nama' => ['required'],
            'username' => ['required'],
            'password' => ['required'],
            'telp' => ['required'],
        ], $messages);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $username = Mahasiswa::where('username', $request->username)->first();

        if ($username) {
            return redirect()->back()->with(['pesan' => 'Username sudah terdaftar'])->withInput();
        }

        Mahasiswa::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'telp' => $request->telp,
        ]);

        return redirect()->route('pewa.index');
    }

    public function logout()
    {
        Auth::guard('mahasiswa')->logout();

        return redirect()->back();
    }

    public function storePengaduan(Request $request)
    {
        if (!Auth::guard('mahasiswa')->user()) {
            return redirect()->back()->with(['pesan' => 'Login dibutuhkan!'])->withInput();
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'isi_laporan' => ['required'],
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate);
        }

        if ($request->file('foto')) {
            $data['foto'] = $request->file('foto')->store('assets/pengaduan', 'public');
        }

        $now = Carbon::now('Asia/Kuala_Lumpur');

        $pengaduan = Pengaduan::create([
            'tgl_pengaduan' => $now,
            'nim' => Auth::guard('mahasiswa')->user()->nim,
            'isi_laporan' => $data['isi_laporan'],
            'foto' => $data['foto'] ?? '',
            'status' => '0',
        ]);

        if ($pengaduan) {
            return redirect()->route('pewa.laporan', 'me')->with(['pengaduan' => 'Berhasil terkirim!', 'type' => 'success']);
        } else {
            return redirect()->back()->with(['pengaduan' => 'Gagal terkirim!', 'type' => 'danger']);
        }
    }

    public function laporan($siapa = '')
    {
        if (!Auth::guard('mahasiswa')->check()) {
            return redirect()->route('pewa.index');
        }
    
        $user = Auth::guard('mahasiswa')->user();
        $terverifikasi = Pengaduan::where([['nim', $user->nim], ['status', '!=', '0']])->count();
        $proses = Pengaduan::where([['nim', $user->nim], ['status', 'proses']])->count();
        $selesai = Pengaduan::where([['nim', $user->nim], ['status', 'selesai']])->count();
    
        $hitung = [$terverifikasi, $proses, $selesai];
    
        if ($siapa == 'me') {
            $pengaduan = Pengaduan::where('nim', $user->nim)->orderBy('tgl_pengaduan', 'desc')->get();
        } else {
            $pengaduan = Pengaduan::where([['nim', '!=', $user->nim], ['status', '!=', '0']])->orderBy('tgl_pengaduan', 'desc')->get();
        }
    
        return view('user.laporan', ['pengaduan' => $pengaduan, 'hitung' => $hitung, 'siapa' => $siapa]);
    }    
}
