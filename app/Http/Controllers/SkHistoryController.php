<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SkHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SkHistoryController extends Controller
{
    public function index(Request $request, $employee)
    {
        $title = "Riwayat SK";
        $employee = Crypt::decrypt($employee);
        $employee = Employee::withTrashed()->where('id', $employee)->first();
        return view('admin.sk_history.index', compact('title', 'employee'));
    }


    public function get_sk_history_index(Request $request, $employee)
    {
        if ($request->ajax()) {
            $counter = 1;

            $employee = Crypt::decrypt($employee);
            $employee = Employee::withTrashed()->where('id', $employee)->first();

            $sk_history = SkHistory::where('employee_id', $employee->id)->limit(10);

            return DataTables::of($sk_history)
                ->addIndexColumn()
                ->addColumn('number', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('file', function ($v) {
                    $url_file = Storage::disk('simpeg_storage')->url('storage/upload/sk_history/' . $v->file);
                    if ($v->file) {
                        $file = '<a href=' . $url_file . ' target="_blank" class="btn btn-info btn-sm" data-placement="top">Download File</a>';
                    } else {
                        $file = NULL;
                    }
                    return $file;
                })
                ->addColumn('action', function ($v) {
                    $btn = '<a href="#" onClick="getData(' . $v->id . ')" id="' . $v->id . '" title="Edit" data-toggle="modal" data-target="#exampleModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </a>';
                    $btn .= '<a href="#" onclick="deleteData(' . $v->id . ')" id="' . $v->id . '" class="warning confirm" data-toggle="tooltip" data-placement="top" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['file', 'action'])
                ->make(true);
        }
    }

    public function validate(Request $request, $action)
    {
        if ($request->ajax()) {

            if ($request->ajax()) {

                $attributes = [
                    'name' => 'Nama SK',
                    'file' => 'Nomor SK',
                ];

                if ($action === "Simpan") {
                    $rules = [
                        'name' => 'required',
                        'file' => 'mimes:jpg,jpeg,png,pdf'
                    ];
                } else {
                    $rules = [
                        'name' => 'required',
                        'file' => 'mimes:jpg,jpeg,png,pdf'
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
            $sk_history = new SkHistory();
            $sk_history->fill($request->all());

            if ($request->file) {
                $sk_history->file = time() . '.' . $request->file->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/sk_history', $request->file('file'), $sk_history->file);
            }

            $sk_history->save();

            activity()->log('Create Data Sk History');
            return response()->json(['success' => true, 'message' => 'Tambah Data Berhasil']);
        }
    }

    ## Get Data
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $sk_history = SkHistory::where('id', $id)->first();
            return response()->json(['success' => true, 'data' => $sk_history]);
        }
    }

    ## Edit Data
    public function update(Request $request, SkHistory $sk_history)
    {
        if ($request->ajax()) {
            $sk_history->name = $request->name;
            $sk_history->sk_number = $request->sk_number;
            $sk_history->date = $request->date;

            if ($sk_history->file && $request->file('file') != "") {
                Storage::disk('simpeg_storage')->delete('upload/sk_history/' . $sk_history->file);
            }

            if ($request->file('file')) {
                $sk_history->file = time() . '.' . $request->file->getClientOriginalExtension();
                Storage::disk('simpeg_storage')->putFileAs('upload/sk_history', $request->file('file'), $sk_history->file);
            }

            $sk_history->save();

            activity()->log('Edit Data Sk History With ID = ' . $sk_history->id);
            return response()->json(['success' => true, 'message' => 'Ubah Data Berhasil']);
        }
    }

    ## Delete Data
    public function delete(Request $request, $sk_history)
    {
        if ($request->ajax()) {
            $sk_history = SkHistory::where('id', $sk_history)->first();

            Storage::disk('simpeg_storage')->delete('upload/sk_history/' . $sk_history->file);

            $sk_history->delete();
            activity()->log('Delete Data Sk History With ID = ' . $sk_history->id);
            return response()->json(['success' => true, 'message' => 'Hapus Data Berhasil']);
        }
    }

    public function get_sk_history($sk_history)
    {
        $employee = Employee::where('id', $sk_history)->first();
        $sk_history = SkHistory::where('employee_id', $sk_history)->orderBy('id', 'ASC')->get();
        return view('get_sk_history', compact('employee', 'sk_history'));
    }
}
