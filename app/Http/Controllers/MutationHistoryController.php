<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\MutationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class MutationHistoryController extends Controller
{
    public function index(Request $request, $employee)
    {
        $title = "Riwayat Mutasi";
        $employee = Crypt::decrypt($employee);
        $employee = Employee::withTrashed()->where('id', $employee)->first();
        return view('admin.mutation_history.index', compact('title', 'employee'));
    }


    public function get_mutation_history_index(Request $request, $employee)
    {
        if ($request->ajax()) {
            $counter = 1;

            $employee = Crypt::decrypt($employee);
            $employee = Employee::withTrashed()->where('id', $employee)->first();

            $mutation_history = MutationHistory::where('employee_id', $employee->id)->limit(10);

            return DataTables::of($mutation_history)
                ->addIndexColumn()
                ->addColumn('number', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('date', function ($v) {
                    return Helpers::date($v->date);
                })
                ->addColumn('origin', function ($v) {
                    return $v->originWorkUnit->name;
                })
                ->addColumn('to', function ($v) {
                    return $v->toWorkUnit->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function validate(Request $request, $action)
    {
        if ($request->ajax()) {

            if ($request->ajax()) {

                $attributes = [
                    'date' => 'Tanggal Mutasi'
                ];

                if ($action === "Simpan") {
                    $rules = [
                        'date' => 'required',
                    ];
                } else {
                    $rules = [
                        'date' => 'required',
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
            $mutation_history = new MutationHistory();
            $mutation_history->fill($request->all());
            $mutation_history->save();

            activity()->log('Create Data Mutation History');
            return response()->json(['success' => true, 'message' => 'Tambah Data Berhasil']);
        }
    }

    ## Get Data
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $mutation_history = MutationHistory::where('id', $id)->first();
            return response()->json(['success' => true, 'data' => $mutation_history]);
        }
    }

    ## Edit Data
    public function update(Request $request, MutationHistory $mutation_history)
    {
        if ($request->ajax()) {
            $mutation_history->date = $request->date;
            $mutation_history->origin = $request->origin;
            $mutation_history->to = $request->to;
            $mutation_history->save();

            activity()->log('Edit Data Mutation History With ID = ' . $mutation_history->id);
            return response()->json(['success' => true, 'message' => 'Ubah Data Berhasil']);
        }
    }

    ## Delete Data
    public function delete(Request $request, $mutation_history)
    {
        if ($request->ajax()) {
            $mutation_history = MutationHistory::where('id', $mutation_history)->first();

            $mutation_history->delete();
            activity()->log('Delete Data Mutation History With ID = ' . $mutation_history->id);
            return response()->json(['success' => true, 'message' => 'Hapus Data Berhasil']);
        }
    }

}
