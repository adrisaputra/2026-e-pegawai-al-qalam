<form id="myForm" action="{{ url('/'.Request::segment(1)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
{{ csrf_field() }}

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="head_title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
				<input type="hidden" class="form-control" name="id" id="id_education_history"/>
                <input type="hidden" class="form-control form-control-sm" name="employee_id" id="employee_id" value="{{ $employee->id }}">
	
				<div class="form-group">
					<p>{{ __('Pendidikan') }} <span class="required" style="color: #dd4b39;">*</span></p>
					<select class="form-control form-control-sm" name="educational_level" id="educational_level">
                        <option value=""> -Pilih Pendidikan-</option>
                        <option value="SD"> SD</option>
                        <option value="SLTP"> SLTP</option>
                        <option value="SLTP Kejuruan"> SLTP Kejuruan</option>
                        <option value="SLTA"> SLTA</option>
                        <option value="SLTA Kejuruan"> SLTA Kejuruan</option>
                        <option value="SLTA Keguruan"> SLTA Keguruan</option>
                        <option value="Diploma I"> Diploma I</option>
                        <option value="Diploma II"> Diploma II</option>
                        <option value="Diploma III / Sarjana Muda"> Diploma III / Sarjana Muda</option>
                        <option value="Diploma IV"> Diploma IV</option>
                        <option value="S1 / Sarjana"> S1 / Sarjana</option>
                        <option value="S2"> S2</option>
                        <option value="S3 / Doktor"> S3 / Doktor</option>
                    </select>
					<div id="educational_level-error" class="fv-plugins-message-container invalid-feedback" style="display: block;"></div>
				</div>
						
                <div class="form-group">
                    <p>{{ __('Nama Sekolah / Institusi ') }}</p>
                    <input type="text" class="form-control form-control-sm" name="institution" id="institution">
                    <div id="institution-error" class="fv-plugins-message-container invalid-feedback" style="display: block;"></div>
                </div>
			
                <div class="form-group">
                    <p>{{ __('Jurusan / Program Studi ') }}</p>
                    <input type="text" class="form-control form-control-sm" name="major" id="major">
                    <div id="major-error" class="fv-plugins-message-container invalid-feedback" style="display: block;"></div>
                </div>
			
                <div class="form-group">
                    <p>{{ __('Tahun Lulus') }}</p>
                    <input type="number" class="form-control form-control-sm" name="year" id="year">
                    <div id="year-error" class="fv-plugins-message-container invalid-feedback" style="display: block;"></div>
                </div>
			
                <div class="fv-row mb-7">
                    <label class="fw-bold fs-6 mb-2">{{ __('File Ijazah') }}</label>
                    <p style="font-size:12px">Format harus berupa berkas berjenis: jpg,jpeg,png atau pdf.<p>
                    <input type="file" name="file" id="file" class="form-control  form-control-sm">
                    <span class="text-red" id="show_file"></span>
					<div id="file-error" class="fv-plugins-message-container invalid-feedback" style="display: block;"></div>
                </div>

                <div class="fv-row mb-7">
                    <label class="fw-bold fs-6 mb-2">{{ __('File Transkrip Nilai') }}</label>
                    <p style="font-size:12px">Format harus berupa berkas berjenis: jpg,jpeg,png atau pdf.<p>
                    <input type="file" name="file2" id="file2" class="form-control form-control-sm">
                    <span class="text-red" id="show_file2"></span>
					<div id="file2-error" class="fv-plugins-message-container invalid-feedback" style="display: block;"></div>
                </div>

			</div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Tutup</button>
                <button type="submit" class="btn btn-primary" id="action" title="Tambah Data"> Simpan</button>
            </div>
        </div>
    </div>
</div>

</form>