<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelangganController extends Controller
{
    //
    public function index()
    {
            $paketList = \App\Models\paket_internet::all();
            return view('pelanggan.index_customer', compact('paketList'));
    }

    public function data()
    {
        //
        $data = \App\Models\pelanggan::with('paketInternet')->orderBy('created_at','DESC');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kode_pelanggan', function ($row) {
                return $row->kode_pelanggan;
            })
            ->addColumn('nama_pelanggan', function ($row) {
                return $row->nama_pelanggan;
            })
            ->addColumn('no_telp', function ($row) {
                return $row->no_telp;
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="edit btn btn-primary btn-sm" data-id="'.$row->kode_pelanggan.'" data-bs-toggle="modal" data-bs-target="#editPelangganModal">Edit</button> ';
                $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="'.$row->kode_pelanggan.'">Delete</button>';
                return $btn;
            })
            ->filter(function($query){
                if(request()->has('search') && !empty(request('search')['value'])) {
                    $searchValue = request('search')['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->where('nama_pelanggan', 'like', "%{$searchValue}%")
                          ->orWhere('kode_pelanggan', 'like', "%{$searchValue}%")
                          ->orWhere('email', 'like', "%{$searchValue}%")
                          ->orWhere('no_telp', 'like', "%{$searchValue}%")
                          ;
                    });
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        // $id sebenarnya adalah kode_pelanggan
        $pelanggan = \App\Models\pelanggan::where('kode_pelanggan', $id)->first();
        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        }
        return response()->json($pelanggan);
    }

    public function update($id, Request $request)
    {
        // $id sebenarnya adalah kode_pelanggan
        $pelanggan = \App\Models\pelanggan::where('kode_pelanggan', $id)->first();
        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'email' => 'required|email',
            'id_paket' => 'required|exists:paket_internet,kode_paket'
        ]);

        $pelanggan->update([
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'],
            'email' => $validated['email'],
            'paket_id_internet' => $validated['id_paket']
        ]);

        return response()->json(['message' => 'Pelanggan berhasil diperbarui', 'data' => $pelanggan], 200);
    }

    public function destroy($id)
    {
        // $id sebenarnya adalah kode_pelanggan
        $pelanggan = \App\Models\pelanggan::where('kode_pelanggan', $id)->first();
        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        }

        $pelanggan->delete();
        return response()->json(['message' => 'Pelanggan berhasil dihapus'], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'email' => 'required|email',
            'id_paket' => 'required|exists:paket_internet,kode_paket'
        ]);

        try {
            // Verifikasi paket ada di database
            $paket = \App\Models\paket_internet::where('kode_paket', $validated['id_paket'])->first();
            if (!$paket) {
                return response()->json([
                    'message' => 'Paket Internet tidak valid. Silahkan pilih paket yang tersedia.',
                    'errors' => ['id_paket' => ['Paket Internet tidak ditemukan dalam database']]
                ], 422);
            }

            // Generate kode pelanggan otomatis - ambil nilai numerik terbesar
            $lastPelanggan = \App\Models\pelanggan::orderBy('kode_pelanggan', 'DESC')->first();
            $nextNumber = 1;
            
            if ($lastPelanggan) {
                // Extract number dari kode_pelanggan (PLG00001 -> 00001)
                $lastNumber = intval(substr($lastPelanggan->kode_pelanggan, 3));
                $nextNumber = $lastNumber + 1;
            }
            
            $kodePelanggan = 'PLG' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $pelanggan = \App\Models\pelanggan::create([
                'kode_pelanggan' => $kodePelanggan,
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'alamat' => $validated['alamat'],
                'no_telp' => $validated['no_telp'],
                'email' => $validated['email'],
                'paket_id_internet' => $validated['id_paket']
            ]);

            return response()->json(['message' => 'Pelanggan berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 422);
        }
    }
}
