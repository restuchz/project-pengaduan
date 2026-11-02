<?php

namespace App\Http\Controllers\Admin;

use App\Models\Petugas;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::all();

        return view('admin.petugas.index', ['petugas' => $petugas]);
    }

    public function create()
    {
        return view('admin.petugas.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'nama_petugas' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:petugas',
            'password' => 'required|string|min:6',
            'telp' => 'required|string|max:15',
            'level' => 'required|in:admin,petugas',
        ]);
         
        if ($validate->fails()){
            return redirect()->back()->withErrors($validate);
        }

        $username = Petugas::where('username', $data['username'])->first();

        if ($username){
            return redirect()->back()->with(['username' => 'Username sudah digunakan !']);
        }

        Petugas::create([
            'nama_petugas'=> $data['nama_petugas'], 
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'telp' => $data['telp'],
            'level' => $data['level'],
        ]);

        return redirect()->route('petugas.index');
    }   

    public function edit($id_petugas)
    {
        $petugas = Petugas::where('id_petugas', $id_petugas)->first();

        return view('Admin.Petugas.edit', ['petugas' => $petugas]);
    }

    public function update(Request $request, $id_petugas)
    {
        $data = $request->all();
    
        $petugas = Petugas::find($id_petugas);
    
        $validate = Validator::make($data, [
            'nama_petugas' => 'required|string|max:255|unique:petugas,nama_petugas,' .$id_petugas.',id_petugas',
            'username' => 'required|string|max:255|unique:petugas,username,'.$id_petugas.',id_petugas',
            'password' => 'required|string|min:6',
            'telp' => 'required|string|max:15',
            'level' => 'required|in:admin,petugas',
        ], [
            'nama_petugas.unique' => 'nama_petugas sudah digunakan.',
            'username.unique' => 'Username sudah digunakan.',
            'password' => 'Password minimal 8 karakter',
            'telp' => 'No Telp maksimal 15 karakter',
        ]);
    
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
    
        $petugas->update([
            'nama_petugas'=> $data['nama_petugas'],
            'username'=> $data['username'],
            'password'=> Hash::make($data['password']),
            'telp'=> $data['telp'],
            'level'=> $data['level'],
        ]);
    
        return redirect()->route('petugas.index');
    }
        

    public function destroy($id_petugas)
    {
        $petugas = Petugas::findOrFail($id_petugas);
    
        // Periksa apakah petugas memiliki tanggapan terkait
        $hasTanggapan = Tanggapan::where('id_petugas', $id_petugas)->exists();
    
        if ($hasTanggapan) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus petugas karena petugas telah memberikan tanggapan.');
        }
    
        $petugas->delete();
    
        return redirect()->route('petugas.index');
    }
}
