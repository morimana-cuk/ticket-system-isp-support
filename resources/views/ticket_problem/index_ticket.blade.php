@extends('layouts.template')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle mb-4">
            <h1>Daftar Ticket Problem</h1>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body pt-4">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    @if (in_array(auth()->user()->role, ['Admin', 'CS']))
                                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                            data-bs-target="#tambahTicketModal">
                                            <i class="bi bi-plus-circle"></i> Buat Ticket Baru
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Custom Search and Filter Area -->
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <label class="me-2 mb-0">Show:</label>
                                        <select class="form-select form-select-sm me-3" id="customLength" style="width: auto;">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        <span class="me-3">entries</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex gap-2">
                                        <select class="form-select form-select-sm" id="filterPrioritas">
                                            <option value="">-- Semua Prioritas --</option>
                                            <option value="1">Low</option>
                                            <option value="2">Medium</option>
                                            <option value="3">High</option>
                                        </select>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Search:</span>
                                            <input type="text" class="form-control" id="customSearch" placeholder="Cari...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table id="ticketTable"
                                class="table table-striped table-bordered table-hover dt-responsive nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Ticket</th>
                                        <th>Judul Problem</th>
                                        <th>Status</th>
                                        <th>Prioritas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            $(document).ready(function() {
                console.log('Document ready');

                // Initialize DataTable
                let table = $('#ticketTable').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'rtip', // Hide default search and length controls
                    ajax: {
                        url: "{{ route('ticket.data') }}",
                        type: 'GET',
                        data: function(d) {
                            d.filterPrioritas = $('#filterPrioritas').val();
                            d.search.value = $('#customSearch').val();
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            console.error('Response:', xhr.responseText);
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            sortable: false,
                            searchable: false
                        },
                        {
                            data: 'ticket_number',
                            name: 'ticket_number',
                            searchable: true
                        },
                        {
                            data: 'judul_problem',
                            name: 'judul_problem',
                            searchable: true
                        },
                        {
                            data: 'status',
                            name: 'status',
                            sortable: false,
                            searchable: false
                        },
                        {
                            data: 'prioritas',
                            name: 'prioritas',
                            sortable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            sortable: false,
                            searchable: false
                        }
                    ],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ]
                });

                // Handle Custom Length Change
                $('#customLength').on('change', function() {
                    table.page.len($(this).val()).draw();
                });

                // Handle Custom Search
                $('#customSearch').on('keyup', function() {
                    table.ajax.reload();
                });

                // Handle Prioritas Filter
                $('#filterPrioritas').on('change', function() {
                    table.ajax.reload();
                });

                // Handle form submit - Tambah Ticket
                $('#formTambahTicket').on('submit', function(e) {
                    e.preventDefault();

                    let formData = {
                        pelanggan_id: $('#pelanggan_id').val(),
                        judul_problem: $('#judul_problem').val(),
                        deskripsi_problem: $('#deskripsi_problem').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    console.log('Form Data:', formData);

                    $.ajax({
                        url: "{{ route('ticket.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            console.log('Success:', response);
                            alert('Ticket berhasil dibuat!');
                            $('#tambahTicketModal').modal('hide');
                            $('#formTambahTicket')[0].reset();
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                            let message = 'Terjadi kesalahan!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            alert(message);
                        }
                    });
                });

                // Handle View Button Click
                $(document).on('click', '.view', function() {
                    let id = $(this).data('id');
                    console.log('View ID:', id);

                    $.ajax({
                        url: "/ticket/" + id + '/view',
                        type: 'GET',
                        success: function(response) {
                            console.log('View Data:', response);
                            $('#view_ticket_number').text(response.ticket_number);
                            $('#view_kode_pelanggan').text(response.kode_pelanggan);
                            $('#view_nama_pelanggan').text(response.nama_pelanggan);
                            $('#view_judul_problem').text(response.judul_problem);
                            $('#view_deskripsi_problem').text(response.deskripsi_problem);
                            $('#view_status').text(response.status == 1 ? 'Open' : (response
                                .status == 2 ? 'In Progress' : 'Closed'));
                            $('#view_prioritas').text(response.prioritas == 1 ? 'Low' : (response
                                .prioritas == 2 ? 'Medium' : 'High'));
                            $('#view_teknisi').text(response.teknisi || '-');

                            const timelineContainer = $('#status_timeline');
                            timelineContainer.empty();

                            if (response.history && response.history.length > 0) {
                                response.history.forEach(function(entry) {
                                    const statusText =
                                        `${entry.status_from_label} → ${entry.status_to_label}`;

                                    const actor = entry.pegawai_name || 'Sistem';
                                    const timestamp = entry.changed_at_formatted || '-';
                                    const human = entry.changed_at_human ?
                                        `<div class="small text-muted">${entry.changed_at_human}</div>` :
                                        '';
                                    const notes = entry.notes ?
                                        `<div class="small">${entry.notes}</div>` : '';

                                    timelineContainer.append(
                                        `<li class="list-group-item">
                                            <div class="fw-semibold">${statusText}</div>
                                            <div class="small text-muted">${actor} • ${timestamp}</div>
                                            ${notes}
                                            ${human}
                                        </li>`
                                    );
                                });
                            } else {
                                timelineContainer.append(
                                    '<li class="list-group-item text-muted">Belum ada perubahan status.</li>'
                                );
                            }
                        },
                        error: function(xhr) {
                            alert('Gagal memuat data ticket');
                            console.error('Error:', xhr.responseText);
                        }
                    });
                });

                // Handle Edit Button Click
                $(document).on('click', '.edit', function() {
                    let id = $(this).data('id');
                    console.log('Edit ID:', id);

                    $.ajax({
                        url: "/ticket/" + id + '/edit',
                        type: 'GET',
                        success: function(response) {
                            console.log('Edit Data:', response);
                            $('#edit_id').val(response.ticket_number);
                            $('#edit_ticket_number').val(response.ticket_number);
                            $('#edit_judul_problem').val(response.judul_problem);
                            $('#edit_deskripsi_problem').val(response.deskripsi_problem);
                            $('#edit_status').val(response.status);
                            $('#edit_prioritas').val(response.prioritas);
                            
                            // Set teknisi in format "PGW001 - Nama Teknisi"
                            if (response.teknisi_kode && response.teknisi_nama) {
                                $('#edit_teknisi').val(response.teknisi_kode + ' - ' + response.teknisi_nama);
                            } else {
                                $('#edit_teknisi').val('');
                            }
                        },
                        error: function(xhr) {
                            alert('Gagal memuat data ticket');
                            console.error('Error:', xhr.responseText);
                        }
                    });
                });

                // Handle form submit - Edit Ticket
                $('#formEditTicket').on('submit', function(e) {
                    e.preventDefault();

                    let id = $('#edit_id').val();
                    console.log('Submitting update for ID:', id);

                    // Extract kode_pegawai dari format "PGW001 - Nama Teknisi"
                    let teknisiValue = $('#edit_teknisi').val();
                    let kodePegawai = teknisiValue.split(' - ')[0].trim();

                    let formData = {
                        judul_problem: $('#edit_judul_problem').val(),
                        deskripsi_problem: $('#edit_deskripsi_problem').val(),
                        status: $('#edit_status').val(),
                        prioritas: $('#edit_prioritas').val(),
                        teknisi: kodePegawai,
                        _method: 'PUT',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    // console.log('Update Form Data:', formData);

                    $.ajax({
                        url: "/ticket/" + id,
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            alert('Ticket berhasil diupdate!');
                            $('#editTicketModal').modal('hide');
                            $('#formEditTicket')[0].reset();
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            alert(message);
                            console.error('Update Error:', xhr.responseText);
                        }
                    });
                });

                // Handle Delete Button Click
                $(document).on('click', '.delete', function() {
                    let id = $(this).data('id');

                    if (confirm('Apakah Anda yakin ingin menghapus ticket ini?')) {
                        $.ajax({
                            url: "/ticket/" + id,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                alert('Ticket berhasil dihapus!');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let message = 'Terjadi kesalahan!';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                alert(message);
                                console.error('Delete Error:', xhr.responseText);
                            }
                        });
                    }
                });

                // Reset form when modal closed
                $('#tambahTicketModal').on('hidden.bs.modal', function() {
                    $('#formTambahTicket')[0].reset();
                });

                $('#editTicketModal').on('hidden.bs.modal', function() {
                    $('#formEditTicket')[0].reset();
                });

                $('#viewTicketModal').on('hidden.bs.modal', function() {
                    $('#status_timeline').empty();
                });
            });
        </script>
    @endpush

    <!-- Modal Tambah Ticket -->
    <div class="modal fade" id="tambahTicketModal" tabindex="-1" aria-labelledby="tambahTicketLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahTicketLabel">Buat Ticket Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahTicket" action="{{ route('ticket.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Pilih Pelanggan</label>
                            <select class="form-control" id="pelanggan_id" name="pelanggan_id" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach ($pelangganList as $pelanggan)
                                    <option value="{{ $pelanggan->kode_pelanggan }}">{{ $pelanggan->kode_pelanggan }} -
                                        {{ $pelanggan->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="judul_problem" class="form-label">Judul Masalah</label>
                            <input type="text" class="form-control" id="judul_problem" name="judul_problem" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi_problem" class="form-label">Deskripsi Masalah</label>
                            <textarea class="form-control" id="deskripsi_problem" name="deskripsi_problem" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Buat Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal View Ticket -->
    <div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTicketLabel">Detail Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Nomor Ticket:</strong></label>
                        <p id="view_ticket_number"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Kode Pelanggan:</strong></label>
                        <p id="view_kode_pelanggan"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Nama Pelanggan:</strong></label>
                        <p id="view_nama_pelanggan"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Judul Problem:</strong></label>
                        <p id="view_judul_problem"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Deskripsi:</strong></label>
                        <p id="view_deskripsi_problem"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Status:</strong></label>
                        <p id="view_status"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Prioritas:</strong></label>
                        <p id="view_prioritas"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Teknisi:</strong></label>
                        <p id="view_teknisi"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Riwayat Perubahan Status:</strong></label>
                        <ul class="list-group" id="status_timeline"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Ticket -->
    <div class="modal fade" id="editTicketModal" tabindex="-1" aria-labelledby="editTicketLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTicketLabel">Edit Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditTicket">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_ticket_number" class="form-label">Nomor Ticket</label>
                            <input type="text" class="form-control" id="edit_ticket_number" name="ticket_number"
                                readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label for="edit_judul_problem" class="form-label">Judul Problem</label>
                            <input type="text" class="form-control" id="edit_judul_problem" name="judul_problem"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi_problem" class="form-label">Deskripsi Problem</label>
                            <textarea class="form-control" id="edit_deskripsi_problem" name="deskripsi_problem" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Open</option>
                                <option value="2">In Progress</option>
                                <option value="3">Closed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_prioritas" class="form-label">Prioritas</label>
                            <select class="form-control" id="edit_prioritas" name="prioritas" required>
                                <option value="">-- Pilih Prioritas --</option>
                                <option value="1">Low</option>
                                <option value="2">Medium</option>
                                <option value="3">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_teknisi" class="form-label">Teknisi</label>
                            <input class="form-control" id="edit_teknisi" name="teknisi" list="teknisiList" placeholder="-- Cari atau Pilih Teknisi --" required>
                            <datalist id="teknisiList">
                                <option value="">-- Pilih Teknisi --</option>
                                @foreach ($pegawai as $tech)
                                    <option value="{{ $tech->kode_pegawai }} - {{ $tech->nama_pegawai }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
