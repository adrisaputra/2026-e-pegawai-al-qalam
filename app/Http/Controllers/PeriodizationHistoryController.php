<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\PeriodizationHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PeriodizationHistoryController extends Controller
{
    public function index(Request $request, $employee)
    {
        $title = "Riwayat Periodisasi";
        $employee = Crypt::decrypt($employee);
        $employee = Employee::withTrashed()->where('id',$employee)->first();
        return view('admin.periodization_history.index',compact('title','employee'));
    }
     
     
    public function get_periodization_history_index(Request $request, $employee)
    {
        if ($request->ajax()) {
            $counter = 1;

            $employee = Crypt::decrypt($employee);
            $employee = Employee::withTrashed()->where('id',$employee)->first();
            
            $periodization_history = PeriodizationHistory::where('employee_id',$employee->id)->where('periodization', '!=', '0')->limit(10);
    
            return DataTables::of($periodization_history)
                ->addIndexColumn()
                ->addColumn('number', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('periodization_text', function ($v){
                    if($v->periodization==1){
                        $periodization_text = 'Periodik I';
                    } else if($v->periodization==2){
                        $periodization_text = 'Periodik II';
                    } else if($v->periodization==3){
                        $periodization_text = 'Periodik III';
                    } else if($v->periodization==4){
                        $periodization_text = 'Periodik IV';
                    } else if($v->periodization==5){
                        $periodization_text = 'Periodik V';
                    } 
                    return $periodization_text;
                })
                ->addColumn('date', function ($v) {
                    if($v->date){
                        return Helpers::date($v->date);
                    } else {
                        return NULL;
                    }
                })
                ->addColumn('date_periodization', function ($v) {
                    if($v->date_periodization){
                        if($v->periodization==5){
                            return NULL;
                        } else {
                            return Helpers::date($v->date_periodization);
                        }
                    } else {
                        return NULL;
                    }
                })
                ->addColumn('file', function ($v){
                    $url_file = Storage::disk('simpeg_storage')->url('storage/upload/periodization_history/' . $v->file);
                    if($v->file){
                        $file = '<a href='.$url_file.' target="_blank" class="btn btn-info btn-sm" data-placement="top">Download File SK</a>';
                    } else {
                        $file = NULL;
                    }
                    return $file;
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
                ->rawColumns(['file','action'])
                ->make(true);

        }
    }
     
    public function validate(Request $request, $action)
    {
        if ($request->ajax()) {

            if ($request->ajax()) {

                $attributes = [
                    'periodization' => 'Periodik',
                    'file' => 'File SK',
                    'date' => 'Tanggal Berlaku',
                    'date_periodization' => 'Pengajuan Periodik Selanjutnya',
                ];
    
                if ($action === "Simpan") {
                    $rules = [
                        'periodization' => 'required',
                        'file' => 'mimes:jpg,jpeg,png,pdf',
                        'date' => 'required',
                    ];
                } else {
                    $rules = [
                        'periodization' => 'required',
                        'file' => 'mimes:jpg,jpeg,png,pdf',
                        'date' => 'required',
                        'date_periodization' => 'required',
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
            $periodization_history = New PeriodizationHistory();
            $periodization_history->fill($request->all());
            
            if ($request->file) {
                $periodization_history->file = time() . '.' . $request->file->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/periodization_history', $request->file('file'), $periodization_history->file);
            }

            // if ($request->periodization == 1) {
            //     $periodization_history->date_periodization = Carbon::parse($request->date)->addYears(1)->addMonths(3)->toDateString();
            // } else {
                $periodization_history->date_periodization = Carbon::parse($request->date)->addYears(3)->toDateString();
            // }

            $periodization_history->save();


            activity()->log('Create Data Periodization History');
            return response()->json(['success' => true,'message' => 'Tambah Data Berhasil']);
        }
    }

    ## Get Data
    public function edit(Request $request,$id)
    {
        if ($request->ajax()) {
            $periodization_history = PeriodizationHistory::where('id',$id)->first();
            return response()->json(['success' => true,'data' => $periodization_history]);
        }
    }

    ## Edit Data
    public function update(Request $request, PeriodizationHistory $periodization_history)
    {
        if ($request->ajax()) {
            $periodization_history->periodization = $request->periodization;
            $periodization_history->sk_number = $request->sk_number;
            $periodization_history->date = $request->date;
            $periodization_history->date_periodization =  $request->date_periodization;
            $periodization_history->note =  $request->note;
            $periodization_history->desc =  $request->desc;
            
            if ($periodization_history->file && $request->file('file') != "") {
                Storage::disk('simpeg_storage')->delete('upload/periodization_history/' . $periodization_history->file);
            }

            if ($request->file('file')) {
                $periodization_history->file = time() . '.' . $request->file->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/periodization_history', $request->file('file'), $periodization_history->file);
            }

            $periodization_history->save();
    
            activity()->log('Edit Data Counter Category With ID = '.$periodization_history->id);
            return response()->json(['success' => true,'message' => 'Ubah Data Berhasil']);
        }
    }

    ## Delete Data
    public function delete(Request $request, $periodization_history)
    {
        if ($request->ajax()) {

            $periodization_history = PeriodizationHistory::where('id',$periodization_history)->first();
            Storage::disk('simpeg_storage')->delete('upload/periodization_history/' . $periodization_history->file);
            $periodization_history->delete();

            activity()->log('Delete Data Counter Category');
            return response()->json(['success' => true,'message' => 'Hapus Data Berhasil']);
        }
    }

}
