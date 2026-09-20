<?php

namespace App\Http\Controllers;

use App\Models\EmployeeKpi;
use App\Models\EmployeeKpiIndicator;
use App\Models\EmployeeReport;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    ## Show Data
    public function index()
    {
        $title = "Dashboard";
          
        $employee_kpi = EmployeeKpi::where('employee_id', Auth::user()->employee_id)
            ->where('month', date('m'))
            ->where('year', date('Y'))
            ->get();

        $weight_task_value = 0;
        $total_employee_kpi = 0;

        foreach ($employee_kpi as $x) {

            $value = EmployeeKpiIndicator::whereHas(
                'employee_kpi_period',
                function ($query) use ($x) {
                    $query->where('employee_kpi_id', $x->id)
                        ->where('employee_id', Auth::user()->employee_id)
                        ->where('month', date('m'))
                        ->where('year', date('Y'))
                        ->whereHas('employee_kpi');
                }
            )->sum('value');

            // Bobot task saat ini
            $weight = $x->weight_task_value ?? 0;

            // Total bobot semua task
            $weight_task_value += $weight;

            // Hitung nilai berdasarkan bobot task ini
            $total_employee_kpi += $value * $weight / 100;
        }

        $report = EmployeeReport::whereHas('employee_report_period',
                                    function ($query) {
                                        $query->where('employee_id', Auth::user()->employee_id)
                                            ->whereMonth('date', date('m'))
                                            ->whereYear('date', date('Y'));
                                    }
                                )->sum('value');
        return view('admin.home', compact('title','total_employee_kpi','report'));
    }
}