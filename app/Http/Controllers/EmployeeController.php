<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class   EmployeeController extends Controller
{
    ## Show Data
    public function index()
    {
        $title = "Dashboard";
        $employee = Employee::where('nik',Auth::user()->name)->first();
        return view('admin.employee.index', compact('title','employee'));
    }

    
    public function validate2(Request $request)
    {

        if ($request->ajax()) {

            $attributes = [
                'phone' => 'No. HP',
                'file_ktp' => 'File KTP',
                'file_kk' => 'File KK',
                'photo' => 'Foto'
            ];

            $rules = [
                'phone' => 'nullable|numeric',
                'file_ktp' => 'mimes:jpg,jpeg,png,pdf',
                'file_kk' => 'mimes:jpg,jpeg,png,pdf',
                'photo' => 'mimes:jpg,jpeg,png,pdf'
            ];

            $request->validate($rules, [], $attributes);

            return response()->json(['success' => true]);
        }
    }

    ## Get Data
    public function edit(Request $request, Employee $employee)
    {
        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $employee]);
        }
    }

    
    ## Edit Data
    public function update2(Request $request, Employee $employee)
    {
        if ($request->ajax()) {
            $employee->birthplace = $request->birthplace;
            $employee->gender = $request->gender;
            $employee->address = $request->address;
            $employee->religion = $request->religion;
            $employee->blood_type = $request->blood_type;
            $employee->marital_status = $request->marital_status;
            $employee->phone = $request->phone;
            $employee->ethnic = $request->ethnic;
            $employee->email = $request->email;
            $employee->education = $request->education;
            $employee->ig = $request->ig;
            $employee->fb = $request->fb;
            $employee->tiktok = $request->tiktok;

            if ($employee->file_ktp && $request->file('file_ktp') != "") {
                Storage::disk('simpeg_storage')->delete('upload/employee/' . $employee->file_ktp);
            }

            if ($request->hasFile('file_ktp')) {
                $employee->file_ktp = '1' . time() . '.' . $request->file_ktp->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/employee', $request->file('file_ktp'), $employee->file_ktp);
            }

            if ($employee->file_kk && $request->file('file_kk') != "") {
                Storage::disk('simpeg_storage')->delete('upload/employee/' . $employee->file_kk);
            }

            if ($request->hasFile('file_kk')) {
                $employee->file_kk = '2' . time() . '.' . $request->file_kk->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/employee', $request->file('file_kk'), $employee->file_kk);
            }

            if ($employee->photo && $request->file('photo') != "") {
                Storage::disk('simpeg_storage')->delete('upload/employee/' . $employee->photo);
            }

            if ($request->hasFile('photo')) {
                $employee->photo = '3' . time() . '.' . $request->photo->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/employee', $request->file('photo'), $employee->photo);
            }

            $employee->save();

            activity()->log('Edit Data Employee With ID = ' . $employee->id);
            return response()->json(['success' => true, 'message' => 'Ubah Data Berhasil', 'data' => $employee]);
        }
    }

}
