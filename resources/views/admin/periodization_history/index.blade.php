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
                            </div>
                        </div>
                    </form>

                    @include('admin.periodization_history.create')

                    <div class="widget-content widget-content-area" style="padding-top: 0px;">
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
                                        <th>Alasan Pending</th>
                                        <th>Keterangan</th>
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
                            data: 'note',
                            name: 'note'
                        },
                        {
                            data: 'desc',
                            name: 'desc'
                        }
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
                    var periodization = $('#periodization ').val();
                    var employee_id = $('#employee_id ').val();
                    var date = $('#date ').val();
                    var date_periodization = $('#date_periodization ').val();

                    // Buat objek FormData untuk mengirim data form, termasuk file
                    var formData = new FormData();
                    formData.append('id', id_periodization_history);
                    formData.append('periodization', periodization);
                    formData.append('employee_id', employee_id);
                    formData.append('date', date);
                    formData.append('date_periodization', date_periodization);
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
                $('#show_date_periodization').hide();
                $('#show_note').show();
                // $('#show_note').hide();
                document.getElementById("show_file").innerHTML = '';
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
                $('#show_date_periodization').show();
                $('#show_note').show();
                // Kirim data formulir ke server menggunakan AJAX

		        var employeeStorageUrl = @json(config('filesystems.disks.simpeg_storage.url'));	
                var url = "{{ url('/periodization_history/edit') }}";
                $.ajax({
                    url: url + "/" + id,
                    type: "GET",
                    success: function(response) {

                        document.getElementById("id_periodization_history").value = response.data.id;
                        document.getElementById("employee_id").value = response.data.employee_id;
                        document.getElementById("periodization").value = response.data.periodization;
                        document.getElementById("sk_number").value = response.data.sk_number;
                        document.getElementById("date").value = response.data.date;
                        document.getElementById("date_periodization").value = response.data.date_periodization;
                        document.getElementById("note").value = response.data.note;
                        document.getElementById("desc").value = response.data.desc;
                                
                        if(response.data.file){
                            var file = '<br><a href="' + employeeStorageUrl + 'storage/upload/periodization_history' + response.data.file + '" class="btn mb-2 mr-1 btn-sm btn-info snackbar-bg-info" target="_blank">Lihat File Sebelumnya</a>';
                            document.getElementById("show_file").innerHTML = file;
                        } else {
                            document.getElementById("show_file").innerHTML = '';
                        }
                        
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

        </script>

        @endsection