@extends('admin.layout')
@section('content')


<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
	<div class="layout-px-spacing">

		<div class="row layout-top-spacing">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
				<div class="widget widget-one_hybrid widget-followers" style="background:rgb(255, 255, 255);">
					<div class="widget-heading">
						<div class="row" style="color: #ffffff;">
							<div class="col-md-12">

								<div class="row" style="padding: 20px 20px 0px 20px">

									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">

										<div class="table-responsive box-body">

											<center>
												<br><br>
												<p style="font-size:22px;font-weight:bold">{{ $employee->name }}</p>
												<p style="font-size:18px;font-weight:bold">{{ $employee->nik }}</p>
											</center>

											<table class="table table-bordered">
												<tr style="background-color: #2196f3;color:white">
													<th style="width: 200px;text-align:center;font-size:16px" colspan=2>DATA PRIBADI</th>
												</tr>
												<tr>
													<th style="width: 200px">NIY</th>
													<td id="data-niy">: {{ $employee->niy }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Tempat Tanggal Lahir</th>
													<td id="data-birthplace">: {{ $employee->birthplace }}, @if($employee->birthdate) {{ date('d-m-Y', strtotime($employee->birthdate)) }} @endif</td>
												</tr>
												<tr>
													<th style="width: 200px">Jenis Kelamin</th>
													<td id="data-gender">
														: @if ($employee->gender == 'Male')
														Laki-laki
														@elseif ($employee->gender == 'Female')
														Perempuan
														@endif
													</td>
												</tr>
												<tr>
													<th style="width: 200px">Alamat</th>
													<td id="data-address">: {{ $employee->address }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Agama</th>
													<td id="data-religion">: {{ $employee->religion }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Gol. Darah</th>
													<td id="data-blood_type">: {{ $employee->blood_type }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Status pernikahan</th>
													<td id="data-marital_status">: {{ $employee->marital_status }}</td>
												</tr>
												<tr>
													<th style="width: 200px">No. HP</th>
													<td id="data-phone">: {{ $employee->phone }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Suku</th>
													<td id="data-ethnic">: {{ $employee->ethnic }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Email</th>
													<td id="data-email">: {{ $employee->email }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Pendidikan terakhir</th>
													<td id="data-education">: {{ $employee->education }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Instagram</th>
													<td id="data-ig">: {{ $employee->ig }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Facebook</th>
													<td id="data-fb">: {{ $employee->fb }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Tiktok</th>
													<td id="data-tiktok">: {{ $employee->tiktok }}</td>
												</tr>
												<tr>
													<th style="width: 200px">TMT</th>
													<td id="data-tmt">: {{ date('d-m-Y', strtotime($employee->tmt)) }}</td>
												</tr>
												<tr>
													<th style="width: 200px">Unit Kerja</th>
													<td id="data-work_unit">: {{ $employee->work_unit->name }}</td>
												</tr>
												<tr>
													<th style="width: 200px">File KTP</th>
													<td id="data-file_ktp">
														@if($employee->file_ktp)
														<a href="{{ config('filesystems.disks.simpeg_storage.url') . '/storage/upload/employee/'.$employee->file_ktp }}" class="btn mb-2 mr-1 btn-sm btn-info snackbar-bg-info" target="_blank">Lihat File Sebelumnya</a>
														@endif
													</td>
												</tr>
												<tr>
													<th style="width: 200px">File KK</th>
													<td id="data-file_kk">
														@if($employee->file_kk)
														<a href="{{ config('filesystems.disks.simpeg_storage.url') . '/storage/upload/employee/'.$employee->file_kk }}" class="btn mb-2 mr-1 btn-sm btn-info snackbar-bg-info" target="_blank">Lihat File Sebelumnya</a>
														@endif
													</td>
												</tr>
												<tr>
													<th style="width: 200px">Foto</th>
													<td id="data-photo">
														@if($employee->photo)
														<img src="{{ config('filesystems.disks.simpeg_storage.url') . '/storage/upload/employee/'.$employee->photo }}" width="100px">
														@endif
													</td>
												</tr>
											</table>


											@include('admin.employee.create')

											<a href="#" onClick="getData( {{ $employee->id }})" id="{{ $employee->id }}" class="btn btn-primary" title="Edit" data-toggle="modal" data-target="#exampleModal">
												Edit Data
											</a>
										</div>
									</div>

								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<script src="{{ asset('backend/assets/js/jquery-3.4.1.min.js')}}"></script>
			<script>
				$(document).ready(function() {

					$('#myForm').submit(function(e) {
						e.preventDefault(); // Hindari pengiriman form secara default

						var action = document.getElementById('action').innerText;
						var id_employee = $('#id_employee').val();
						var phone = $('#phone').val();

						// Buat objek FormData untuk mengirim data form, termasuk file
						var formData = new FormData();
						formData.append('id', id_employee);
						formData.append('phone', phone);
						formData.append('_token', "{{ csrf_token() }}");

						var fileInput = document.getElementById('file_ktp');
						if (fileInput.files.length > 0) {
							formData.append('file_ktp', fileInput.files[0]);
						}

						var fileInput = document.getElementById('file_kk');
						if (fileInput.files.length > 0) {
							formData.append('file_kk', fileInput.files[0]);
						}

						var fileInput = document.getElementById('photo');
						if (fileInput.files.length > 0) {
							formData.append('photo', fileInput.files[0]);
						}

						// Kirim permintaan validasi ke controller via Ajax
						var url = "{{ url('/employee2/validate') }}";
						$.ajax({
							url: url + "/",
							type: "POST",
							data: formData,
							contentType: false, // Tidak mengatur contentType secara otomatis
							processData: false, // Tidak memproses data secara otomatis
							success: function(response) {

								$('.invalid-feedback').html(''); // Hapus pesan kesalahan
								$('.is-invalid').removeClass('is-invalid'); // Hapus kelas is-invalid dari bidang-bidang yang divalidasi

								update(id_employee);

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
					document.getElementById("show_file_ktp").innerHTML = '';
					document.getElementById("show_file_kk").innerHTML = '';
					document.getElementById("show_photo").innerHTML = '';
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

				// Get Data
				function getData(id) {
					document.getElementById("head_title").textContent = "Ubah Data";
					document.getElementById("action").textContent = "Update";
					// Kirim data formulir ke server menggunakan AJAX

					var employeeStorageUrl = @json(config('filesystems.disks.simpeg_storage.url'));		
					var url = "{{ url('/employee/edit') }}";
					$.ajax({
						url: url + "/" + id,
						type: "GET",
						success: function(response) {
							document.getElementById("id_employee").value = response.data.id;
							document.getElementById("birthplace").value = response.data.birthplace;
							document.getElementById("gender").value = response.data.gender;
							document.getElementById("address").value = response.data.address;
							document.getElementById("religion").value = response.data.religion;
							document.getElementById("blood_type").value = response.data.blood_type;
							document.getElementById("marital_status").value = response.data.marital_status;
							document.getElementById("phone").value = response.data.phone;
							document.getElementById("ethnic").value = response.data.ethnic;
							document.getElementById("email").value = response.data.email;
							document.getElementById("education").value = response.data.education;
							document.getElementById("ig").value = response.data.ig;
							document.getElementById("fb").value = response.data.fb;
							document.getElementById("tiktok").value = response.data.tiktok;

							if (response.data.file_ktp) {
								var file_ktp = '<br><a href="' + employeeStorageUrl + 'storage/upload/employee/' + response.data.file_ktp + '" class="btn mb-2 mr-1 btn-sm btn-info snackbar-bg-info" target="_blank">Lihat File Sebelumnya</a>';

								document.getElementById("show_file_ktp").innerHTML = file_ktp;
							} else {
								document.getElementById("show_file_ktp").innerHTML = '';
							}

							if (response.data.file_kk) {
								var file_kk = '<br><a href="' + employeeStorageUrl + 'storage/upload/employee/' + response.data.file_kk + '" class="btn mb-2 mr-1 btn-sm btn-info snackbar-bg-info" target="_blank">Lihat File Sebelumnya</a>';

								document.getElementById("show_file_kk").innerHTML = file_kk;
							} else {
								document.getElementById("show_file_kk").innerHTML = '';
							}

							if (response.data.photo) {
								var photo = '<br><a href="' + employeeStorageUrl + 'storage/upload/employee/' + response.data.photo + '" class="btn mb-2 mr-1 btn-sm btn-info snackbar-bg-info" target="_blank">Lihat File Sebelumnya</a>';

								document.getElementById("show_photo").innerHTML = photo;
							} else {
								document.getElementById("show_photo").innerHTML = '';
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

					var url = "{{ url('/employee2/edit') }}";
					$.ajax({
						url: url + "/" + id,
						type: "POST",
						data: formData,
						contentType: false, // Biarkan jQuery menentukan contentType secara otomatis
						processData: false, // Biarkan jQuery menangani proses data secara otomatis
						success: function(response) {
							showSuccessToast(response.message); // Tampilkan notifikasi toast untuk keberhasilan
							// Ubah isi tabel secara langsung
							$('#data-birthplace').text(': ' + response.data.birthplace + ', ' + response.data.birthdate);
							$('#data-gender').text(': ' + (response.data.gender === 'Male' ? 'Laki-laki' : response.data.gender === 'Female' ? 'Perempuan' : ''));
							$('#data-address').text(': ' + (response.data.address ?? ''));
							$('#data-religion').text(': ' + (response.data.religion ?? ''));
							$('#data-blood_type').text(': ' + (response.data.blood_type ?? ''));
							$('#data-marital_status').text(': ' + (response.data.marital_status ?? ''));
							$('#data-email').text(': ' + (response.data.email ?? ''));
							$('#data-phone').text(': ' + (response.data.phone ?? ''));
							$('#data-ethnic').text(': ' + (response.data.ethnic ?? ''));
							$('#data-ig').text(': ' + (response.data.ig ?? ''));
							$('#data-fb').text(': ' + (response.data.fb ?? ''));
							$('#data-tiktok').text(': ' + (response.data.tiktok ?? ''));

							// 🔥 Update tombol lihat file kalau file ada
							if (response.data.file_ktp) {
								var ktpBtn = '<a href="{{ asset("storage/upload/employee") }}/' + response.data.file_ktp + '" class="btn mb-2 mr-1 btn-sm btn-info snackbar-bg-info" target="_blank">Lihat File Sebelumnya</a>';
								$('#show_file_ktp').html(ktpBtn);
								$('#data-file_ktp').html(ktpBtn);
							}

							if (response.data.file_kk) {
								var kkBtn = '<a href="{{ asset("storage/upload/employee") }}/' + response.data.file_kk + '" class="btn mb-2 mr-1 btn-sm btn-info snackbar-bg-info" target="_blank">Lihat File Sebelumnya</a>';
								$('#show_file_kk').html(kkBtn);
								$('#data-file_kk').html(kkBtn);
							}

							if (response.data.photo) {
								var photoTag = '<img src="{{ asset("storage/upload/employee") }}/' + response.data.photo + '" width="100px">';
								$('#show_photo').html(photoTag);
								$('#data-photo').html(photoTag);
							}

							$('#myForm')[0].reset(); // Reset form setelah berhasil memperbarui data
							$('#exampleModal').modal('hide'); // Tutup modal setelah berhasil memperbarui data
						},
						error: function(xhr) {
							// Tangani kesalahan jika pengiriman formulir gagal
							showFailedToast(xhr); // Tampilkan notifikasi toast untuk keberhasilan
							console.error("Error pengiriman formulir:", xhr);
						}
					});
				}
			</script>
		</div>
	</div>
	<div class="footer-wrapper">
		<div class="footer-section f-section-1">
			<p class="">Copyright © 2025</p>
		</div>
	</div>
</div>
@endsection