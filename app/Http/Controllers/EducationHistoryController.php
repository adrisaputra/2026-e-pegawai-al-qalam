<?php

namespace App\Http\Controllers;

use App\Models\EducationHistory;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EducationHistoryController extends Controller
{
    public function index(Request $request, $employee)
    {
        $title = "Riwayat Pendidikan";
        $employee = Crypt::decrypt($employee);
        $employee = Employee::withTrashed()->where('id',$employee)->first();
        return view('admin.education_history.index',compact('title','employee'));
    }
     
     
    public function get_education_history_index(Request $request, $employee)
    {
        if ($request->ajax()) {
            $counter = 1;

            $employee = Crypt::decrypt($employee);
            $employee = Employee::withTrashed()->where('id',$employee)->first();
            
            $education_history = EducationHistory::where('employee_id',$employee->id)->limit(10);
    
            return DataTables::of($education_history)
                ->addIndexColumn()
                ->addColumn('number', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('file', function ($v) {
                    $url_file = Storage::disk('simpeg_storage')->url('storage/upload/education_history/' . $v->file);
                    if ($v->file) {
                        $file = '<a href=' . $url_file . ' target="_blank" class="btn btn-info btn-sm" data-placement="top">Download File</a>';
                    } else {
                        $file = NULL;
                    }
                    return $file;
                })
                ->addColumn('file2', function ($v) {
                    $url_file2 = Storage::disk('simpeg_storage')->url('storage/upload/education_history/' . $v->file2);
                    if ($v->file2) {
                        $file2 = '<a href=' . $url_file2 . ' target="_blank" class="btn btn-info btn-sm" data-placement="top">Download File</a>';
                    } else {
                        $file2 = NULL;
                    }
                    return $file2;
                })
                ->addColumn('action', function ($v) {
                    $btn = '<a href="#" onClick="getData('.$v->id.')" id="'.$v->id.'" title="Edit" data-toggle="modal" data-target="#exampleModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </a>';  
                    $btn .= '<a href="#" onclick="deleteData('.$v->id.')" id="'.$v->id.'" class="warning confirm" data-toggle="tooltip" data-placement="top" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['file','file2','action'])
                ->make(true);

        }
    }
     
    public function validate(Request $request, $action)
    {
        if ($request->ajax()) {

            if ($request->ajax()) {

                $attributes = [
                    'educational_level' => 'Pendidikan ',
                    'year' => 'Tahun Lulus',
                    'file' => 'File Ijazah',
                    'file2' => 'File Transkrip Nilai',
                ];
    
                if ($action === "Simpan") {
                    $rules = [
                        'educational_level' => 'required',
                        'year' => 'nullable|numeric',
                        'file' => 'mimes:jpg,jpeg,png,pdf',
                        'file2' => 'mimes:jpg,jpeg,png,pdf'
                    ];
                } else {
                    $rules = [
                        'educational_level' => 'required',
                        'year' => 'nullable|numeric',
                        'file' => 'mimes:jpg,jpeg,png,pdf',
                        'file2' => 'mimes:jpg,jpeg,png,pdf'
                    ];
                }
    
                $request->validate($rules, [], $attributes);
    
                return response()->json(['success' => true]);
            }
        }
    }

    ## Save Data
	public function store(Request $request)
    {
        if ($request->ajax()) {
            $education_history = New EducationHistory();
            $education_history->fill($request->all());

            $educationMap = [
                "SD" => 1,
                "SLTP" => 2,
                "SLTP Kejuruan" => 3,
                "SLTA" => 4,
                "SLTA Kejuruan" => 5,
                "SLTA Keguruan" => 6,
                "Diploma I" => 7,
                "Diploma II" => 8,
                "Diploma III / Sarjana Muda" => 9,
                "Diploma IV" => 10,
                "S1 / Sarjana" => 11,
                "S2" => 12,
                "S3 / Doktor" => 13,
            ];

            $education_history->educational_no = $educationMap[$request->educational_level] ?? null;
            
            if ($request->file) {
                $education_history->file = '1'.time() . '.' . $request->file->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/education_history', $request->file('file'), $education_history->file);
            }

            if ($request->file2) {
                $education_history->file2 = '2'.time() . '.' . $request->file2->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/education_history', $request->file('file2'), $education_history->file2);
            }

            $education_history->save();
            
            activity()->log('Create Data Counter Category');
            return response()->json(['success' => true,'message' => 'Tambah Data Berhasil']);
        }
    }

    ## Get Data
    public function edit(Request $request,$id)
    {
        if ($request->ajax()) {
            $education_history = EducationHistory::where('id',$id)->first();
            return response()->json(['success' => true,'data' => $education_history]);
        }
    }

    ## Edit Data
    public function update(Request $request, EducationHistory $education_history)
    {
        if ($request->ajax()) {
            
            $educationMap = [
                "SD" => 1,
                "SLTP" => 2,
                "SLTP Kejuruan" => 3,
                "SLTA" => 4,
                "SLTA Kejuruan" => 5,
                "SLTA Keguruan" => 6,
                "Diploma I" => 7,
                "Diploma II" => 8,
                "Diploma III / Sarjana Muda" => 9,
                "Diploma IV" => 10,
                "S1 / Sarjana" => 11,
                "S2" => 12,
                "S3 / Doktor" => 13,
            ];

            $education_history->educational_no = $educationMap[$request->educational_level] ?? null;
            
            $education_history->educational_level = $request->educational_level;
            $education_history->institution = $request->institution;
            $education_history->major = $request->major;
            $education_history->year = $request->year;
            
            if ($education_history->file && $request->file('file') != "") {
                Storage::disk('simpeg_storage')->delete('upload/education_history/' . $education_history->file);
            }

            if ($request->file('file')) {
                $education_history->file = '1'.time() . '.' . $request->file->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/education_history', $request->file('file'), $education_history->file);
            }

            if ($education_history->file2 && $request->file('file2') != "") {
                Storage::disk('simpeg_storage')->delete('upload/education_history/' . $education_history->file2);
            }

            if ($request->file('file2')) {
                $education_history->file2 = '2'.time() . '.' . $request->file2->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/education_history', $request->file('file2'), $education_history->file2);
            }

            $education_history->save();
    
            activity()->log('Edit Data Counter Category With ID = '.$education_history->id);
            return response()->json(['success' => true,'message' => 'Ubah Data Berhasil']);
        }
    }

    ## Delete Data
    public function delete(Request $request, $education_history)
    {
        if ($request->ajax()) {
            $education_history = EducationHistory::where('id',$education_history)->first();
            
            Storage::disk('simpeg_storage')->delete('upload/education_history/' . $education_history->file);
            Storage::disk('simpeg_storage')->delete('upload/education_history/' . $education_history->file2);

            $education_history->delete();
            activity()->log('Delete Data Counter Category With ID = '.$education_history->id);
            return response()->json(['success' => true,'message' => 'Hapus Data Berhasil']);
        }
    }

    public function get_education_history($education_history)
    {
        $employee = Employee::where('id',$education_history)->first();
        $education_history = EducationHistory::where('employee_id',$education_history)->orderBy('id','ASC')->get();
        return view('get_education_history',compact('employee','education_history'));
    }

}
