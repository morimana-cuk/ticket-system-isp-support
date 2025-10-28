<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PaketInternetRepositoryInterface;
use App\Repositories\Contracts\PelangganRepositoryInterface;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    //
    public function __construct(
        private PelangganRepositoryInterface $pelangganRepo,
        private PaketInternetRepositoryInterface $paketRepo,
    ) {}

    public function index()
    {
        $paketList = $this->paketRepo->all();
        return view('pelanggan.index_customer', compact('paketList'));
    }

    public function data()
    {
        //
        $data = $this->pelangganRepo->queryForDataTable();

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
                $btn = '<button type="button" class="edit btn btn-primary btn-sm" data-id="' . $row->kode_pelanggan . '" data-bs-toggle="modal" data-bs-target="#editPelangganModal">Edit</button> ';
                $btn .= '<button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->kode_pelanggan . '">Delete</button>';
                return $btn;
            })
            ->filter(function ($query) {
                if (request()->has('search') && !empty(request('search')['value'])) {
                    $searchValue = request('search')['value'];
                    $query->where(function ($q) use ($searchValue) {
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
        $pelanggan = $this->pelangganRepo->findByKode($id);
        if (!$pelanggan) {
            return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan'], 404);
        }
        return response()->json($pelanggan);
    }

    public function update($id, Request $request)
    {
        // $id sebenarnya adalah kode_pelanggan
        $pelanggan = $this->pelangganRepo->findByKode($id);
        if (!$pelanggan) {
            return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan'], 404);
        }


        try {
            $validated = $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'no_telp' => 'required|string|max:20',
                'email' => 'required|email',
                'id_paket' => 'required|exists:paket_internet,kode_paket'
            ]);
            //code...
            $this->pelangganRepo->updateByKode($id, [
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'alamat' => $validated['alamat'],
                'no_telp' => $validated['no_telp'],
                'email' => $validated['email'],
                'paket_id_internet' => $validated['id_paket']
            ]);

            return response()->json(['success' => true, 'message' => 'Pelanggan berhasil diperbarui'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['success' => false, 'message' => 'Error: ' . $th->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        // $id sebenarnya adalah kode_pelanggan
        $pelanggan = $this->pelangganRepo->findByKode($id);
        if (!$pelanggan) {
            return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan'], 404);
        }

        $this->pelangganRepo->deleteByKode($id);
        return response()->json(['success' => true, 'message' => 'Pelanggan berhasil dihapus'], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_pelanggan' => 'required|string',
                'alamat' => 'required|string',
                'no_telp' => 'required|string',
                'email' => 'required|email',
                'id_paket' => 'required|exists:paket_internet,kode_paket'
            ]);
            // Verifikasi paket ada di database
            $paket = $this->paketRepo->findByKode($validated['id_paket']);
            if (!$paket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paket Internet tidak valid. Silahkan pilih paket yang tersedia.',
                    'errors' => ['id_paket' => ['Paket Internet tidak ditemukan dalam database']]
                ], 422);
            }

            $pelanggan = $this->pelangganRepo->createWithAutoKode([
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'alamat' => $validated['alamat'],
                'no_telp' => $validated['no_telp'],
                'email' => $validated['email'],
                'paket_id_internet' => $validated['id_paket']
            ]);

            return response()->json(['success' => true, 'message' => 'Pelanggan berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }
}
