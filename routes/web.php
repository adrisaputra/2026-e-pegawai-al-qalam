<?php

use App\Http\Controllers\EducationHistoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeKpiBonusController;
use App\Http\Controllers\EmployeeKpiDetailController;
use App\Http\Controllers\EmployeeKpiIndicatorController;
use App\Http\Controllers\EmployeeKpiIndicatorItemController;
use App\Http\Controllers\EmployeeKpiPeriodController;
use App\Http\Controllers\EmployeeReportCategoryController;
use App\Http\Controllers\EmployeeReportFileController;
use App\Http\Controllers\EmployeeReportPeriodController;
use App\Http\Controllers\EmployeeReportValueController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MutationHistoryController;
use App\Http\Controllers\ParentHistoryController;
use App\Http\Controllers\PartnerHistoryController;
use App\Http\Controllers\PeriodizationHistoryController;
use App\Http\Controllers\SkHistoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/buat_storage', function () {
    Artisan::call('storage:link');
    dd("Storage Berhasil Di Buat");
});

Route::get('/clear-cache-all', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    dd("Cache Clear All");
});


Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);


Route::middleware(['role:Employee'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index']);

    ## Employee
    Route::get('/employee', [EmployeeController::class, 'index']);
    Route::post('/employee2/validate', [EmployeeController::class, 'validate2']);
    Route::get('/employee/edit/{employee}', [EmployeeController::class, 'edit']);
    Route::put('/employee2/edit/{employee}', [EmployeeController::class, 'update2']);
    
    ## Education History
    Route::get('/education_history/{employee}', [EducationHistoryController::class, 'index'])->name('education_historys.index');
    Route::get('/education_history/list/{employee}', [EducationHistoryController::class, 'get_education_history_index'])->name('education_historys.list');
    Route::post('/education_history/store', [EducationHistoryController::class, 'store']);
    Route::post('/education_history/validate/{action}', [EducationHistoryController::class, 'validate']);
    Route::get('/education_history/edit/{education_history}', [EducationHistoryController::class, 'edit']);
    Route::put('/education_history/edit/{education_history}', [EducationHistoryController::class, 'update']);
    Route::get('/education_history/delete/{education_history}',[EducationHistoryController::class, 'delete']);

    ## Sk History
    Route::get('/sk_history/{employee}', [SkHistoryController::class, 'index'])->name('sk_historys.index');
    Route::get('/sk_history/list/{employee}', [SkHistoryController::class, 'get_sk_history_index'])->name('sk_historys.list');
    Route::post('/sk_history/store', [SkHistoryController::class, 'store']);
    Route::post('/sk_history/validate/{action}', [SkHistoryController::class, 'validate']);
    Route::get('/sk_history/edit/{sk_history}', [SkHistoryController::class, 'edit']);
    Route::put('/sk_history/edit/{sk_history}', [SkHistoryController::class, 'update']);
    Route::get('/sk_history/delete/{sk_history}',[SkHistoryController::class, 'delete']);

    ## Partner History
    Route::get('/partner_history/{employee}', [PartnerHistoryController::class, 'index'])->name('partner_historys.index');
    Route::get('/partner_history/list/{employee}', [PartnerHistoryController::class, 'get_partner_history_index'])->name('partner_historys.list');
    Route::post('/partner_history/store', [PartnerHistoryController::class, 'store']);
    Route::post('/partner_history/validate/{action}', [PartnerHistoryController::class, 'validate']);
    Route::get('/partner_history/edit/{partner_history}', [PartnerHistoryController::class, 'edit']);
    Route::put('/partner_history/edit/{partner_history}', [PartnerHistoryController::class, 'update']);
    Route::get('/partner_history/delete/{partner_history}',[PartnerHistoryController::class, 'delete']);

    ## Parent History
    Route::get('/parent_history/{employee}', [ParentHistoryController::class, 'index'])->name('parent_historys.index');
    Route::get('/parent_history/list/{employee}', [ParentHistoryController::class, 'get_parent_history_index'])->name('parent_historys.list');
    Route::post('/parent_history/store', [ParentHistoryController::class, 'store']);
    Route::post('/parent_history/validate/{action}', [ParentHistoryController::class, 'validate']);
    Route::get('/parent_history/edit/{parent_history}', [ParentHistoryController::class, 'edit']);
    Route::put('/parent_history/edit/{parent_history}', [ParentHistoryController::class, 'update']);
    Route::get('/parent_history/delete/{parent_history}',[ParentHistoryController::class, 'delete']);

    ## Mutation History
    Route::get('/mutation_history/{employee}', [MutationHistoryController::class, 'index'])->name('mutation_historys.index');
    Route::get('/mutation_history/list/{employee}', [MutationHistoryController::class, 'get_mutation_history_index'])->name('mutation_historys.list');
   
    ## Periodization History
    Route::get('/periodization_history/{employee}', [PeriodizationHistoryController::class, 'index'])->name('periodization_historys.index');
    Route::get('/periodization_history/list/{employee}', [PeriodizationHistoryController::class, 'get_periodization_history_index'])->name('periodization_historys.list');
    Route::post('/periodization_history/store', [PeriodizationHistoryController::class, 'store']);
    Route::post('/periodization_history/validate/{action}', [PeriodizationHistoryController::class, 'validate']);
    Route::get('/periodization_history/edit/{periodization_history}', [PeriodizationHistoryController::class, 'edit']);
    Route::put('/periodization_history/edit/{periodization_history}', [PeriodizationHistoryController::class, 'update']);
    Route::get('/periodization_history/delete/{periodization_history}',[PeriodizationHistoryController::class, 'delete']);
    Route::get('/get_periodization/{employee}',[PeriodizationHistoryController::class, 'get_periodization']);
    Route::get('/request_periodization/{employee}',[PeriodizationHistoryController::class, 'request_periodization']);

    ## Employee KPI Detail
    Route::get('/employee_kpi_detail/{employee}', [EmployeeKpiDetailController::class, 'index'])->name('employee_kpi_detail.index');
    Route::get('/employee_kpi_detail/list/{employee}', [EmployeeKpiDetailController::class, 'get_employee_kpi_detail_index'])->name('employee_kpi_detail.list');
    Route::post('/employee_kpi_detail/store', [EmployeeKpiDetailController::class, 'store']);
    Route::post('/employee_kpi_detail/validate/{action}', [EmployeeKpiDetailController::class, 'validate']);
    Route::get('/employee_kpi_detail/edit/{employee_kpi}', [EmployeeKpiDetailController::class, 'edit']);
    Route::put('/employee_kpi_detail/edit/{employee_kpi}', [EmployeeKpiDetailController::class, 'update']);
    Route::get('/employee_kpi_detail/delete/{employee_kpi}',[EmployeeKpiDetailController::class, 'delete']);
    
    ## Employee KPI Periode
    Route::get('/employee_kpi_period/{employee_kpi}', [EmployeeKpiPeriodController::class, 'index'])->name('employee_kpi_period.index');
    Route::get('/employee_kpi_period/list/{employee_kpi}', [EmployeeKpiPeriodController::class, 'get_employee_kpi_period_index'])->name('employee_kpi_period.list');
    Route::get('/employee_kpi_period_bonus/list/{employee_kpi}', [EmployeeKpiPeriodController::class, 'get_employee_kpi_period_bonus_index'])->name('employee_kpi_period_bonus.list');
    
    ## Employee KPI Bonus
    Route::post('/employee_kpi_bonus/validate', [EmployeeKpiBonusController::class, 'validate']);
    Route::get('/employee_kpi_bonus/edit/{employee_kpi_bonus}', [EmployeeKpiBonusController::class, 'edit']);
    Route::put('/employee_kpi_bonus/edit/{employee_kpi_bonus}', [EmployeeKpiBonusController::class, 'update']);
    
    ## Employee KPI Indicator
    Route::post('/employee_kpi_indicator/store', [EmployeeKpiIndicatorController::class, 'store']);

    ## Employee KPI Indicator Item
    Route::get('/employee_kpi_indicator_item/{employee_kpi_indicator}', [EmployeeKpiIndicatorItemController::class, 'index'])->name('employee_kpi_indicator_item.index');
    Route::get('/employee_kpi_indicator_item/list/{employee_kpi_indicator}', [EmployeeKpiIndicatorItemController::class, 'get_employee_kpi_indicator_item_index'])->name('employee_kpi_indicator_item.list');
    Route::put('/employee_kpi_indicator_item/edit/{employee_kpi_indicator_item}', [EmployeeKpiIndicatorItemController::class, 'update']);
   
    ## Employee Report Category
    Route::get('/employee_report_category/{employee}', [EmployeeReportCategoryController::class, 'index'])->name('employee_report_category.index');
    Route::get('/employee_report_category/list/{employee}', [EmployeeReportCategoryController::class, 'get_employee_report_category_index'])->name('employee_report_category.list');
    Route::post('/employee_report_category/store', [EmployeeReportCategoryController::class, 'store']);
    Route::post('/employee_report_category/validate/{action}', [EmployeeReportCategoryController::class, 'validate']);
    Route::get('/employee_report_category/edit/{employee_report_category}', [EmployeeReportCategoryController::class, 'edit']);
    Route::put('/employee_report_category/edit/{employee_report_category}', [EmployeeReportCategoryController::class, 'update']);
    Route::get('/employee_report_category/delete/{employee_report_category}',[EmployeeReportCategoryController::class, 'delete']);
    
    ## Employee Report Periode
    Route::get('/employee_report_period/{employee_report_category}', [EmployeeReportPeriodController::class, 'index'])->name('employee_report_period.index');
    Route::get('/employee_report_period/list/{employee_report_category}', [EmployeeReportPeriodController::class, 'get_employee_report_period_index'])->name('employee_report_period.list');
    Route::post('/employee_report_period/store', [EmployeeReportPeriodController::class, 'store']);

    ## Employee Report
    Route::get('/employee_report_value/{employee_report_period}', [EmployeeReportValueController::class, 'index'])->name('employee_report_value.index');
    Route::get('/employee_report_value/list/{employee_report_period}', [EmployeeReportValueController::class, 'get_employee_report_value_index'])->name('employee_report_value.list');
    Route::put('/employee_report_value/edit/{employee_report}', [EmployeeReportValueController::class, 'update']);
   
    ## Employee Report File
    Route::get('/employee_report_file/{employee_report}', [EmployeeReportFileController::class, 'index'])->name('employee_report_file.index');
    Route::get('/employee_report_file/list/{employee_report}', [EmployeeReportFileController::class, 'get_employee_report_file_index'])->name('employee_report_file.list');
    Route::post('/employee_report_file/store', [EmployeeReportFileController::class, 'store']);
    Route::post('/employee_report_file/validate/{action}', [EmployeeReportFileController::class, 'validate']);
    Route::get('/employee_report_file/edit/{employee_report_file}', [EmployeeReportFileController::class, 'edit']);
    Route::put('/employee_report_file/edit/{employee_report_file}', [EmployeeReportFileController::class, 'update']);
    Route::get('/employee_report_file/delete/{employee_report_file}',[EmployeeReportFileController::class, 'delete']);

    ## Edit Profile
    Route::get('/edit_profil/{user}',[UserController::class, 'edit_profil']);
    Route::post('/edit_profil/validate/{action}', [UserController::class, 'validate_profile']);
    Route::put('/edit_profil/{user}',[UserController::class, 'update_profil']);

});

