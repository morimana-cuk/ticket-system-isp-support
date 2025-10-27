<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use Illuminate\Http\Request;
use App\Models\ticket_problem;
use App\Models\TicketStatusHistory;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $pelangganList = \App\Models\pelanggan::all();
        $pegawai = pegawai::where('level', 'teknisi')->get();
        return view('ticket_problem.index_ticket', compact('pelangganList', 'pegawai'));
    }

    public function data()
    {
        // $data = ticket_problem::with('pelanggan')->orderBy('id', 'DESC');
        $data = ticket_problem::with('pelanggan')->orderBy('created_at', 'DESC');

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('ticket_number', function ($row) {
                return $row->ticket_number;
            })
            ->addColumn('judul_problem', function ($row) {
                return $row->judul_problem;
            })
            ->addColumn('status', function ($row) {
                if ($row->status === 1) {
                    # code...
                    return '<span class="badge bg-warning">Open</span>';
                }
                if ($row->status === 2) {
                    # code...
                    return '<span class="badge bg-info">In Progress</span>';
                }
                if ($row->status === 3) {
                    # code...
                    return '<span class="badge bg-success">Closed</span>';
                }
                return '<span class="badge bg-secondary">Unknown</span>';
            })
            ->addColumn('prioritas', function ($row) {
                if ($row->prioritas === 1) {
                    return '<span class="badge bg-success">Low</span>';
                }
                if ($row->prioritas === 2) {
                    return '<span class="badge bg-warning">Medium</span>';
                }
                if ($row->prioritas === 3) {
                    return '<span class="badge bg-danger">High</span>';
                }
                return '<span class="badge bg-secondary">Unknown</span>';
            })
            ->addColumn('action', function ($row) {
                $userRole = Auth::user()->role;
                $buttons = '<button class="btn btn-sm btn-primary view" data-id="' . $row->ticket_number . '" data-bs-toggle="modal" data-bs-target="#viewTicketModal">
                        View
                    </button>';
                
                // Edit dan Delete hanya untuk Admin dan NOC
                if (in_array($userRole, ['Admin', 'NOC'])) {
                    $buttons .= ' <button class="btn btn-sm btn-warning edit" data-id="' . $row->ticket_number . '" data-bs-toggle="modal" data-bs-target="#editTicketModal">
                        Edit
                    </button>';
                    $buttons .= ' <button class="btn btn-sm btn-danger delete" data-id="' . $row->ticket_number . '">
                        Delete
                    </button>';
                }
                
                return $buttons;
            })
            ->filter(function ($query) {
                if (request()->has('search') && !empty(request('search')['value'])) {
                    $search = request('search')['value'];
                    $query->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('judul_problem', 'like', "%{$search}%");
                }
                
                if (request()->has('filterPrioritas') && !empty(request('filterPrioritas'))) {
                    $prioritas = request('filterPrioritas');
                    $query->where('prioritas', $prioritas);
                }
            })
            ->rawColumns(['status', 'prioritas', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|string|exists:pelanggan,kode_pelanggan',
            'judul_problem' => 'required|string',
            'deskripsi_problem' => 'required|string'
        ]);

        try {
            // Generate ticket number otomatis - ambil nilai numerik terbesar
            $lastTicket = ticket_problem::orderBy('ticket_number', 'DESC')->first();
            $nextNumber = 1;
            
            if ($lastTicket) {
                // Extract number dari ticket_number (TKT00001 -> 00001)
                $lastNumber = intval(substr($lastTicket->ticket_number, 3));
                $nextNumber = $lastNumber + 1;
            }
            
            $ticketNumber = 'TKT' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $ticket = ticket_problem::create([
                'ticket_number' => $ticketNumber,
                'pelanggan_id' => $validated['pelanggan_id'],
                'judul_problem' => $validated['judul_problem'],
                'deskripsi_problem' => $validated['deskripsi_problem'],
                'status' => 1, // Default Open
                'prioritas' => 1, // Default Low
                // 'created_by' => Auth::id()
            ]);

            TicketStatusHistory::create([
                'ticket_number' => $ticket->ticket_number,
                'status_from' => null,
                'status_to' => $ticket->status,
                'changed_by' => Auth::id(),
                'notes' => 'Ticket dibuat',
            ]);

            return response()->json(['message' => 'Ticket berhasil dibuat'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function edit($id)
    {
        // $id sebenarnya adalah ticket_number
    $ticket = ticket_problem::with(['statusHistories.user.pegawai'])->where('ticket_number', $id)->first();
        if (!$ticket) {
            return response()->json(['message' => 'Ticket tidak ditemukan'], 404);
        }
        $history = $ticket->statusHistories->map(function ($entry) {
            return [
                'status_from' => $entry->status_from,
                'status_to' => $entry->status_to,
                'status_from_label' => $this->statusLabel($entry->status_from),
                'status_to_label' => $this->statusLabel($entry->status_to),
                'changed_by' => $entry->user?->email,
                'pegawai_name' => $entry->user?->pegawai?->nama_pegawai ?? $entry->user?->email ?? '-',
                'changed_at_iso' => $entry->created_at?->toIso8601String(),
                'changed_at_formatted' => $entry->created_at?->format('d M Y H:i'),
                'changed_at_human' => $entry->created_at ? $entry->created_at->diffForHumans() : null,
                'notes' => $entry->notes,

            ];
        })->values();

        return response()->json([
            'ticket_number' => $ticket->ticket_number,
            'judul_problem' => $ticket->judul_problem,
            'deskripsi_problem' => $ticket->deskripsi_problem,
            'status' => $ticket->status,
            'prioritas' => $ticket->prioritas,
            'history' => $history,
            'teknisi_kode' => $ticket->pegawai?->kode_pegawai ?? null,
            'teknisi_nama' => $ticket->pegawai?->nama_pegawai ?? null,
        ]);
    }

    public function view($id)
    {
        // $id sebenarnya adalah ticket_number
        $ticket = ticket_problem::with('statusHistories.user.pegawai', 'pelanggan')->where('ticket_number', $id)->first();
        if (!$ticket) {
            return response()->json(['message' => 'Ticket tidak ditemukan'], 404);
        }

        $history = $ticket->statusHistories->map(function ($entry) {
            return [
                'status_from' => $entry->status_from,
                'status_to' => $entry->status_to,
                'status_from_label' => $this->statusLabel($entry->status_from),
                'status_to_label' => $this->statusLabel($entry->status_to),
                'changed_by' => $entry->user?->email,
                'pegawai_name' => $entry->user?->pegawai?->nama_pegawai ?? $entry->user?->email ?? '-',
                'changed_at_iso' => $entry->created_at?->toIso8601String(),
                'changed_at_formatted' => $entry->created_at?->format('d M Y H:i'),
                'changed_at_human' => $entry->created_at ? $entry->created_at->diffForHumans() : null,
                'notes' => $entry->notes,
            ];
        })->values();

        return response()->json([
            'ticket_number' => $ticket->ticket_number,
            'kode_pelanggan' => $ticket->pelanggan?->kode_pelanggan,
            'nama_pelanggan' => $ticket->pelanggan?->nama_pelanggan,
            'judul_problem' => $ticket->judul_problem,
            'deskripsi_problem' => $ticket->deskripsi_problem,
            'status' => $ticket->status,
            'prioritas' => $ticket->prioritas,
            'history' => $history,
            'teknisi' => $ticket->pegawai?->nama_pegawai ?? null,
        ]);
    }

    public function update(Request $request, $id)
    {
        // $id sebenarnya adalah ticket_number
        $validated = $request->validate([
            'judul_problem' => 'required|string',
            'deskripsi_problem' => 'required|string',
            'status' => 'required|in:1,2,3',
            'prioritas' => 'required|in:1,2,3',
            'teknisi' => 'required',
        ]);

        try {
            $ticket = ticket_problem::where('ticket_number', $id)->first();
            if (!$ticket) {
                return response()->json(['message' => 'Ticket tidak ditemukan'], 404);
            }

            $originalStatus = $ticket->status;

            $ticket->update([
                'judul_problem' => $validated['judul_problem'],
                'deskripsi_problem' => $validated['deskripsi_problem'],
                'status' => $validated['status'],
                'prioritas' => $validated['prioritas'],
                // 'updated_by' => Auth::id(),
                'pegawai_id' => $validated['teknisi'],
            ]);

            if ((int) $originalStatus !== (int) $validated['status']) {
                TicketStatusHistory::create([
                    'ticket_number' => $ticket->ticket_number,
                    'status_from' => $originalStatus,
                    'status_to' => $validated['status'],
                    'changed_by' => Auth::id(),
                    'notes' => 'Status diperbarui',
                ]);
            }

            return response()->json(['message' => 'Ticket berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy($id)
    {
        // $id sebenarnya adalah ticket_number
        try {
            $ticket = ticket_problem::where('ticket_number', $id)->first();
            if (!$ticket) {
                return response()->json(['message' => 'Ticket tidak ditemukan'], 404);
            }

            $ticket->delete();
            return response()->json(['message' => 'Ticket berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    private function statusLabel(?int $status): string
    {
        return match ((int) ($status ?? 1)) {
            1 => 'Open',
            2 => 'In Progress',
            3 => 'Closed',
            default => 'Unknown',
        };
    }
}
