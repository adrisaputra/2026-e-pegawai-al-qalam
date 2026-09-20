@extends('admin.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">
            <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Data {{ __($title) }} ( {{ __($employee->name)}}  )</h4>
                            </div>
                        </div>
                    </div>

                    <form action="{{ url(Request::segment(1).'/search') }}" method="GET">
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-8 col-md-12 col-sm-12 col-12">
                                    @if(Auth::user()->group_id == 1 || Auth::user()->group_id == 2)
                                        @if($periodization_history)
                                            @if($periodization_history->periodization != 5)
                                                @if($status != 'Pending' && $status != 'Not Yet Time' && $status != 'Request')
                                                <b id="TambahData">
                                                    <a href="#" class="btn mb-2 mr-1 btn-success" data-placement="top" data-toggle="modal" data-target="#exampleModal" title="Tambah Data" onClick="clearForm()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                                        </svg></a>
                                                </b>
                                                @endif
                                            @endif
                                        @endif
                                    @else
                                        @if($request_periodization)
                                            @if($periodization_history_check->periodization != 5)
                                                @if($periodization_history_check->status == 'Not Yet Time' || $periodization_history_check->status == 'No Pending'  || $periodization_history_check->status == 'Pending' )
                                                    <a href="#" class="btn mb-2 mr-1 btn-info" onClick="requestPeriodization()">Ajukan Periodik</a>
                                                @endif
                                            @else
                                                <a href="#" class="btn mb-2 mr-1 btn-danger">Belum Bisa Mengajukan Periodik</a>
                                            @endif
                                        @endif
                                    @endif
                                    <a href="{{ url('/'.Request::segment(1).'/'.Request::segment(2)) }}" class="btn mb-2 mr-1 btn-warning" data-toggle="tooltip" data-placement="top" title="Refresh"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw">
                                            <polyline points="1 4 1 10 7 10"></polyline>
                                            <polyline points="23 20 23 14 17 14"></polyline>
                                            <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                                        </svg></a>
                                    @if(Auth::user()->group_id == 1 || Auth::user()->group_id == 2)
                                        @if($employee->deleted_at)
                                            <a href="{{ url('employee_deleted') }}" class="btn mb-2 mr-1 btn-danger" data-toggle="tooltip" data-placement="top" title="Kembali"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg></a>
                                        @else
                                            <a href="{{ url('employee') }}" class="btn mb-2 mr-1 btn-danger" data-toggle="tooltip" data-placement="top" title="Kembali"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg></a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>

                    @include('admin.periodization_history.create')

                    <div class="widget-content widget-content-area" style="padding-top: 0px;">
                        @if ($periodization_history_check)
                            @if ($periodization_history_check->status == 'Request')
                            <div class="alert alert-info mb-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                                <h4 style="color: #ffffff;"><i class="icon fa fa-check"></i> Berhasil !</h4>
                                @if(Auth::user()->group_id == 3) Anda @else {{ __($employee->name) }} @endif Telah Mengajukan Periodik ! Pengajuan @if(Auth::user()->group_id == 3) Anda @else {{ __($employee->name) }} @endif Dalam Tinjauan Admin !
                            </div>
                            @elseif ($periodization_history_check->status == 'Pending')
                            <div class="alert alert-danger mb-4" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                                <h4 style="color: #ffffff;"><i class="icon fa fa-check"></i> Pending !</h4>
                                Pengujan @if(Auth::user()->group_id == 3) Anda @else {{ __($employee->name) }} @endif dipending sampai tanggal {{ \App\Helpers\Helpers::date($periodization_history_check->date_periodization) }} !
                                <br><b>Alasan Pending</b> : {{ $periodization_history_check->note }}
                            </div>
                            @endif
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover " id="periodization_history-table">
                                <thead>
                                    <tr>
                                        <th style="width: 2%">Number</th>
                                        <th style="width: 2%">No</th>
                                        <th style="width: 20%">Periodisasi</th>
                                        <th style="width: 10%">Periodik</th>
                                        <th>No. SK</th>
                                        <th>Tanggal Berlaku</th>
                                        <th>Pengajuan Periodik Selanjutnya</th>
                                        <th>File SK</th>
                                        <th>Alasan Pending</th>
                                        @if(Auth::user()->group_id!=3)
                                            <th style="width: 8%"></th>
                                        @endif
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script>
            var table;

            $(document).ready(function() {
                table = $('#periodization_history-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('periodization_historys.list', ['employee' => Crypt::encrypt($employee->id)]) }}",
                        type: 'GET',
                        dataType: 'json',
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            visible: false
                        },
                        {
                            data: 'number',
                            name: 'number'
                        }, // Kolom nomor urut
                        {
                            data: 'periodization',
                            name: 'periodization',
                            visible: false
                        },
                        {
                            data: 'periodization_text',
                            name: 'periodization_text'
                        },
                        {
                            data: 'sk_number',
                            name: 'sk_number'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'date_periodization',
                            name: 'date_periodization'
                        },
                        {
                            data: 'file',
                            name: 'file'
                        },
                        {
                            data: 'note',
                            name: 'note'
                        },
                        @if(Auth::user()->group_id != 3) {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                        @endif
                    ],
                    order: [
                        [2, 'asc'] // Mengatur pengurutan kolom pertama (id) secara descending
                    ],
                    paging: true,
                    drawCallback: function() {
                        var api = this.api();
                        var startIndex = api.context[0]._iDisplayStart; // Indeks baris pertama di halaman
                        api.column(1, {
                            page: 'current'
                        }).nodes().each(function(cell, i) {
                            cell.innerHTML = startIndex + i + 1; // Menghitung nomor urut berdasarkan indeks baris dan nomor halaman
                        });
                    }
                });

                $('#myForm').submit(function(e) {
                    e.preventDefault(); // Hindari pengiriman form secara default

                    var action = document.getElementById('action').innerText;
                    var id_periodization_history = $('#id_periodization_history').val();
                    var employee_id = $('#employee_id ').val();
                    var date = $('#date ').val();

                    // Buat objek FormData untuk mengirim data form, termasuk file
                    var formData = new FormData();
                    formData.append('id', id_periodization_history);
                    formData.append('employee_id', employee_id);
                    formData.append('date', date);
                    formData.append('_token', "{{ csrf_token() }}");

                    var fileInput = document.getElementById('file');
                    if (fileInput.files.length > 0) {
                        formData.append('file', fileInput.files[0]);
                    }

                    // Kirim permintaan validasi ke controller via Ajax
                    var url = "{{ url('/periodization_history/validate') }}";
                    $.ajax({
                        url: url + "/" + action,
                        type: "POST",
                        data: formData,
                        contentType: false, // Tidak mengatur contentType secara otomatis
                        processData: false, // Tidak memproses data secara otomatis
                        success: function(response) {

                            $('.invalid-feedback').html(''); // Hapus pesan kesalahan
                            $('.is-invalid').removeClass('is-invalid'); // Hapus kelas is-invalid dari bidang-bidang yang divalidasi

                            if (action === "Simpan") {
                                send();
                            } else {
                                update(id_periodization_history);
                            }

                        },
                        error: function(xhr) {
                            var errors = xhr.responseJSON.errors;

                            // Bersihkan semua pesan kesalahan sebelum menampilkan yang baru
                            $('.fv-plugins-message-container').html('');

                            // Tampilkan pesan kesalahan untuk setiap bidang jika ada
                            if (errors) {
                                $.each(errors, function(key, value) {
                                    $('#' + key + '-error').html(value[0]);
                                });
                            }
                        }
                    });
                });

            });

            function clearForm() {
                document.getElementById("head_title").textContent = "Tambah {{ __($title) }}";
                $('#myForm')[0].reset();
                getPeriodization();
                document.getElementById("action").textContent = "Simpan";
            }

            // Fungsi untuk menampilkan notifikasi toast dengan ikon centang
            function showSuccessToast(message) {
                Snackbar.show({
                    text: message,
                    showAction: false,
                    actionTextColor: '#fff',
                    backgroundColor: '#8dbf42',
                    pos: 'top-right'
                });
            }

            function showFailedToast(message) {
                Snackbar.show({
                    text: message,
                    showAction: false,
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                    pos: 'top-right'
                });
            }

            // Create Data
            function send() {
                var formData = new FormData($('#myForm')[0]); // Buat objek FormData dari formulir

                // Kirim data formulir ke server menggunakan AJAX
                $.ajax({
                    url: "{{ url('periodization_history/store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false, // Biarkan jQuery menentukan contentType secara otomatis
                    processData: false, // Biarkan jQuery menangani proses data secara otomatis
                    success: function(response) {
                        showSuccessToast(response.message); // Tampilkan notifikasi toast
                        getPeriodization();
                        $('#myForm')[0].reset(); // Reset form setelah berhasil menambahkan data
                        $('#exampleModal').modal('hide');
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        // Tangani kesalahan jika pengiriman formulir gagal
                        console.error("Error pengiriman formulir:", xhr);
                    }
                });
            }

            // Get Data
            function getData(id) {
                document.getElementById("head_title").textContent = "Ubah {{ __($title) }}";
                document.getElementById("action").textContent = "Update";
                // Kirim data formulir ke server menggunakan AJAX

                var url = "{{ url('/periodization_history/edit') }}";
                $.ajax({
                    url: url + "/" + id,
                    type: "GET",
                    success: function(response) {

                        document.getElementById("id_periodization_history").value = response.data.id;
                        document.getElementById("employee_id").value = response.data.employee_id;
                        document.getElementById("periodization2").value = response.data.periodization;
                        document.getElementById("sk_number").value = response.data.sk_number;
                        document.getElementById("date").value = response.data.date;
                        document.getElementById("file").value = response.data.file;
                    },
                    error: function(xhr) {
                        // Tangani kesalahan jika pengiriman formulir gagal
                        showFailedToast(xhr); // Tampilkan notifikasi toast untuk keberhasilan
                        console.error("Error pengiriman formulir:", xhr);
                    }
                });
            }

            // Update Data
            function update(id) {
                var formData = new FormData($('#myForm')[0]); // Buat objek FormData dari formulir
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('_method', "PUT");

                // Kirim data formulir ke server menggunakan AJAX

                var url = "{{ url('/periodization_history/edit') }}";
                $.ajax({
                    url: url + "/" + id,
                    type: "POST",
                    data: formData,
                    contentType: false, // Biarkan jQuery menentukan contentType secara otomatis
                    processData: false, // Biarkan jQuery menangani proses data secara otomatis
                    success: function(response) {
                        showSuccessToast(response.message); // Tampilkan notifikasi toast untuk keberhasilan
                        getPeriodization();
                        $('#myForm')[0].reset(); // Reset form setelah berhasil memperbarui data
                        $('#exampleModal').modal('hide'); // Tutup modal setelah berhasil memperbarui data
                        table.ajax.reload(null, false); // Muat ulang DataTables setelah update
                    },
                    error: function(xhr) {
                        // Tangani kesalahan jika pengiriman formulir gagal
                        showFailedToast(xhr); // Tampilkan notifikasi toast untuk keberhasilan
                        console.error("Error pengiriman formulir:", xhr);
                    }
                });
            }

            // Delete Data
            function deleteData(id) {
                swal({
                    title: 'Apakah Kamu Yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    padding: '2em'
                }).then(function(result) {
                    if (result.value) {
                        swal(
                            'Deleted!',
                            'Data Berhasil Dihapus.',
                            'success'
                        ).then(function() {
                            var url = "{{ url('/periodization_history/delete') }}";
                            $.ajax({
                                url: url + "/" + id,
                                success: function(response) {
                                    showSuccessToast(response.message);
                                    getPeriodization();
                                    $('#myForm')[0].reset();
                                    table.ajax.reload(null, false);
                                },
                                error: function(xhr) {
                                    showFailedToast(xhr); // Tampilkan notifikasi toast untuk keberhasilan
                                    console.error("Error pengiriman formulir:", xhr);
                                }
                            });
                        });
                    }
                });

            }

            function getPeriodization() {
                employee_id = document.getElementById('employee_id').value;
                var url = "{{ url('/get_periodization') }}";
                $.ajax({
                    url: url + '/' + employee_id,
                    type: 'GET',
                    success: function(response) {
                        if (response.status != 'Not Yet Time') {
                            document.getElementById('TambahData').style.display = 'inline';
                        } else {
                            document.getElementById('TambahData').style.display = 'none';
                        }

                        if (response.data == 1) {
                            document.getElementById("periodization2").value = 'I';
                        } else if (response.data == 2) {
                            document.getElementById("periodization2").value = 'II';
                        } else if (response.data == 3) {
                            document.getElementById("periodization2").value = 'III';
                        } else if (response.data == 4) {
                            document.getElementById("periodization2").value = 'IV';
                        } else if (response.data == 5) {
                            document.getElementById("periodization2").value = 'V';
                        }
                        console.log(response.data)
                    }
                });
            }

            function requestPeriodization() {
                employee_id = document.getElementById('employee_id').value;
                var url = "{{ url('/request_periodization') }}";
                $.ajax({
                    url: url + '/' + employee_id,
                    type: 'GET',
                    success: function(response) {
                        if (response.success === true) {
                            showSuccessToast(response.message);
                        } else {
                            showFailedToast(response.message);
                        }

                        console.log(response.data)
                        window.location.reload();
                    }
                });
            }
        </script>

        @endsection