@extends('layouts.template')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle mb-4">
            <h1>Daftar Pelanggan</h1>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body pt-4">
                            @if(in_array(auth()->user()->role, ['Admin', 'CS']))
                                <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block"
                                    data-bs-toggle="modal" data-bs-target="#tambahPelangganModal">
                                    Tambah Pelanggan
                                </button>
                            @else
                                <div class="alert alert-warning mb-4" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i> Anda tidak memiliki akses ke manajemen pelanggan
                                </div>
                            @endif

                            <table id="pelangganTable"
                                class="table table-striped table-bordered table-hover dt-responsive nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pelanggan</th>
                                        <th>Nama</th>
                                        <th>Telp</th>
                                        <th>Email</th>
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
                let table = $('#pelangganTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('customer.data') }}",
                        type: 'GET',
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
                            data: 'kode_pelanggan',
                            name: 'kode_pelanggan'
                        },
                        {
                            data: 'nama_pelanggan',
                            name: 'nama_pelanggan'
                        },
                        {
                            data: 'no_telp',
                            name: 'no_telp'
                        },
                        {
                            data: 'email',
                            name: 'email'
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

                // Handle form submit - Tambah Pelanggan
                $('#formTambahPelanggan').on('submit', function(e) {
                    e.preventDefault();

                    // DEBUG: Check dropdown value sebelum validasi
                    console.log('====== FORM SUBMIT DEBUG ======');
                    console.log('Dropdown HTML:', $('#id_paket').html());
                    console.log('Dropdown value:', $('#id_paket').val());
                    console.log('Dropdown selected option:', $('#id_paket').find('option:selected').val());
                    console.log('Dropdown selected text:', $('#id_paket').find('option:selected').text());
                    console.log('All options:', $('#id_paket').find('option').map(function() { 
                        return { value: $(this).val(), text: $(this).text() };
                    }).get());
                    console.log('==============================');

                    // Validasi client-side
                    const idPaket = $('#id_paket').val();
                    const namaPelanggan = $('#nama_pelanggan').val();
                    const alamat = $('#alamat').val();
                    const noTelp = $('#no_telp').val();
                    const email = $('#email').val();

                    console.log('Validating form data:', {
                        idPaket,
                        namaPelanggan,
                        alamat,
                        noTelp,
                        email
                    });

                    if (!idPaket || idPaket === '') {
                        alert('❌ Silahkan pilih Paket Internet terlebih dahulu!');
                        $('#id_paket').focus();
                        return false;
                    }

                    if (!namaPelanggan.trim()) {
                        alert('❌ Nama Pelanggan tidak boleh kosong!');
                        $('#nama_pelanggan').focus();
                        return false;
                    }

                    if (!alamat.trim()) {
                        alert('❌ Alamat tidak boleh kosong!');
                        $('#alamat').focus();
                        return false;
                    }

                    if (!noTelp.trim()) {
                        alert('❌ No. Telepon tidak boleh kosong!');
                        $('#no_telp').focus();
                        return false;
                    }

                    if (!email.trim()) {
                        alert('❌ Email tidak boleh kosong!');
                        $('#email').focus();
                        return false;
                    }

                    let formData = {
                        nama_pelanggan: namaPelanggan,
                        alamat: alamat,
                        no_telp: noTelp,
                        email: email,
                        id_paket: idPaket,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    console.log('Form Data:', formData);

                    $.ajax({
                        url: "{{ route('customer.store') }}",
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            console.log('Success:', response);
                            alert('✅ Pelanggan berhasil ditambahkan!');
                            $('#tambahPelangganModal').modal('hide');
                            $('#formTambahPelanggan')[0].reset();
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                            let message = 'Terjadi kesalahan!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            alert('❌ Error: ' + message);
                        }
                    });
                });

                // Handle Edit Button Click
                $(document).on('click', '.edit', function() {
                    let id = $(this).data('id');
                    console.log('Edit ID:', id);

                    $.ajax({
                        url: "{{ route('customer.index') }}" + '/' + id + '/edit',
                        type: 'GET',
                        success: function(response) {
                            console.log('Edit Data:', response);
                            $('#edit_kode_pelanggan').val(response.kode_pelanggan);
                            $('#edit_id').val(response.kode_pelanggan);
                            $('#edit_nama_pelanggan').val(response.nama_pelanggan);
                            $('#edit_alamat').val(response.alamat);
                            $('#edit_no_telp').val(response.no_telp);
                            $('#edit_email').val(response.email);
                            $('#edit_id_paket').val(response.paket_id_internet);
                        },
                        error: function(xhr) {
                            alert('Gagal memuat data pelanggan');
                        }
                    });
                });

                // Handle form submit - Edit Pelanggan
                $('#formEditPelanggan').on('submit', function(e) {
                    e.preventDefault();

                    // Validasi client-side
                    const idPaket = $('#edit_id_paket').val();
                    const namaPelanggan = $('#edit_nama_pelanggan').val();
                    const alamat = $('#edit_alamat').val();
                    const noTelp = $('#edit_no_telp').val();
                    const email = $('#edit_email').val();

                    if (!idPaket || idPaket === '') {
                        alert('❌ Silahkan pilih Paket Internet terlebih dahulu!');
                        $('#edit_id_paket').focus();
                        return false;
                    }

                    if (!namaPelanggan.trim()) {
                        alert('❌ Nama Pelanggan tidak boleh kosong!');
                        $('#edit_nama_pelanggan').focus();
                        return false;
                    }

                    if (!alamat.trim()) {
                        alert('❌ Alamat tidak boleh kosong!');
                        $('#edit_alamat').focus();
                        return false;
                    }

                    if (!noTelp.trim()) {
                        alert('❌ No. Telepon tidak boleh kosong!');
                        $('#edit_no_telp').focus();
                        return false;
                    }

                    if (!email.trim()) {
                        alert('❌ Email tidak boleh kosong!');
                        $('#edit_email').focus();
                        return false;
                    }

                    let id = $('#edit_id').val();
                    let formData = {
                        nama_pelanggan: namaPelanggan,
                        alamat: alamat,
                        no_telp: noTelp,
                        email: email,
                        id_paket: idPaket,
                        _method: 'PUT',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    $.ajax({
                        url: "{{ route('customer.index') }}" + '/' + id,
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            alert('✅ Pelanggan berhasil diupdate!');
                            $('#editPelangganModal').modal('hide');
                            $('#formEditPelanggan')[0].reset();
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan!';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            alert('❌ Error: ' + message);
                        }
                    });
                });

                // Handle Delete Button Click
                $(document).on('click', '.delete', function() {
                    let id = $(this).data('id');

                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        $.ajax({
                            url: "{{ route('customer.index') }}" + '/' + id,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                alert('Pelanggan berhasil dihapus!');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                let message = 'Terjadi kesalahan!';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = xhr.responseJSON.message;
                                }
                                alert(message);
                            }
                        });
                    }
                });

                // Reset form when modal closed
                $('#tambahPelangganModal').on('hidden.bs.modal', function() {
                    $('#formTambahPelanggan')[0].reset();
                });

                $('#editPelangganModal').on('hidden.bs.modal', function() {
                    $('#formEditPelanggan')[0].reset();
                });
            });
        </script>
    @endpush

    <!-- Modal Tambah Pelanggan -->
    <div class="modal fade" id="tambahPelangganModal" tabindex="-1" aria-labelledby="tambahPelangganLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPelangganLabel">Tambah Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahPelanggan" action="{{ route('customer.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No Telepon</label>
                            <input type="number" class="form-control" id="no_telp" name="no_telp" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_paket" class="form-label">Paket Internet</label>
                            <select class="form-control" id="id_paket" name="id_paket" required>
                                <option value="">-- Pilih Paket --</option>
                                @forelse ($paketList as $paket)
                                    <option value="{{ $paket->kode_paket }}">{{ $paket->nama_paket }} ({{ $paket->kecepatan }})</option>
                                @empty
                                    <option value="" disabled>Tidak ada paket tersedia</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pelanggan -->
    <div class="modal fade" id="editPelangganModal" tabindex="-1" aria-labelledby="editPelangganLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPelangganLabel">Edit Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditPelanggan">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="edit_id">
                        <div class="mb-3">
                            <label for="kode_pelanggan" class="form-label">Kode Pelanggan</label>
                            <input type="text" class="form-control" id="edit_kode_pelanggan" name="kode_pelanggan"
                                readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_pelanggan" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="edit_nama_pelanggan" name="nama_pelanggan"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_no_telp" class="form-label">No Telepon</label>
                            <input type="number" class="form-control" id="edit_no_telp" name="no_telp" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_paket" class="form-label">Paket Internet</label>
                            <select class="form-control" id="edit_id_paket" name="id_paket" required>
                                <option value="">-- Pilih Paket --</option>
                                @forelse ($paketList as $paket)
                                    <option value="{{ $paket->kode_paket }}">{{ $paket->nama_paket }} ({{ $paket->kecepatan }})</option>
                                @empty
                                    <option value="" disabled>Tidak ada paket tersedia</option>
                                @endforelse
                            </select>
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
