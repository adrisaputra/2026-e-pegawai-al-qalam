<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\PartnerHistory;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class PartnerHistoryController extends Controller
{
    public function index(Request $request, $employee)
    {
        $title = "Riwayat Pasangan";
        $employee = Crypt::decrypt($employee);
        $employee = Employee::withTrashed()->where('id',$employee)->first();
        return view('admin.partner_history.index',compact('title','employee'));
    }
     
     
    public function get_partner_history_index(Request $request, $employee)
    {
        if ($request->ajax()) {
            $counter = 1;

            $employee = Crypt::decrypt($employee);
            $employee = Employee::withTrashed()->where('id',$employee)->first();
            
            $partner_history = PartnerHistory::where('employee_id',$employee->id)->limit(10);
    
            return DataTables::of($partner_history)
                ->addIndexColumn()
                ->addColumn('number', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('birthdate', function ($v) {
                    if($v->birthdate){
                        return $v->birthplace.', '.Helpers::date($v->birthdate);
                    } else {
                        return $v->birthplace;
                    }
                })
                ->addColumn('action', function ($v) {
                    $btn = '<a href="#" onClick="getData('.$v->id.')" id="'.$v->id.'" title="Edit" data-toggle="modal" data-target="#exampleModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </a>';  
                    // $btn .= '<a href="#" onclick="deleteData('.$v->id.')" id="'.$v->id.'" class="warning confirm" data-toggle="tooltip" data-placement="top" title="Hapus">
                    //             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    //         </a>';
                    return $btn;
                })
                ->rawColumns(['button','action'])
                ->make(true);

        }
    }
     
    public function validate(Request $request, $action)
    {
        if ($request->ajax()) {

            if ($request->ajax()) {

                $attributes = [
                    'name' => 'Nama'
                ];
    
                if ($action === "Simpan") {
                    $rules = [
                        'name' => 'required'
                    ];
                } else {
                    $rules = [
                        'name' => 'required'
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
            $partner_history = New PartnerHistory();
            $partner_history->fill($request->all());
            $partner_history->save();
            
            activity()->log('Create Data Counter Category');
            return response()->json(['success' => true,'message' => 'Tambah Data Berhasil']);
        }
    }

    ## Get Data
    public function edit(Request $request,$id)
    {
        if ($request->ajax()) {
            $partner_history = PartnerHistory::where('id',$id)->first();
            return response()->json(['success' => true,'data' => $partner_history]);
        }
    }

    ## Edit Data
    public function update(Request $request, PartnerHistory $partner_history)
    {
        if ($request->ajax()) {
            $partner_history->name = $request->name;
            $partner_history->birthplace = $request->birthplace;
            $partner_history->birthdate = $request->birthdate;
            $partner_history->work = $request->work;
            $partner_history->save();
    
            activity()->log('Edit Data Counter Category With ID = '.$partner_history->id);
            return response()->json(['success' => true,'message' => 'Ubah Data Berhasil']);
        }
    }

    ## Delete Data
    public function delete(Request $request, $partner_history)
    {
        if ($request->ajax()) {
            $partner_history = PartnerHistory::where('id',$partner_history)->first();
            $partner_history->delete();
            activity()->log('Delete Data Counter Category With ID = '.$partner_history->id);
            return response()->json(['success' => true,'message' => 'Hapus Data Berhasil']);
        }
    }

    public function get_partner_history($partner_history)
    {
        $employee = Employee::where('id',$partner_history)->first();
        $partner_history = PartnerHistory::where('employee_id',$partner_history)->orderBy('id','ASC')->get();
        return view('get_partner_history',compact('employee','partner_history'));
    }

}
