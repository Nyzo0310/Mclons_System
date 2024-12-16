<?php

use App\Http\Controllers\Display;
use Illuminate\Support\Facades\Route;

Route::delete('/employee/{id}', [Display::class, 'deleteEmployee'])->name('employee.delete');
Route::get('/Employee',[Display::class,'Display2'])->name('admin.employee');
Route::get('/addEmployeeList', [Display::class, 'DisplayAddEmployeeList'])->name('admin.addEmployeeList');

Route::get('/Attendance',[Display::class,'Display'])->name('admin.attendance');
Route::get('/AttendanceDash',[Display::class,'Display1'])->name('admin.attendanceDash');
Route::get('/AttendanceRecords', [Display::class, 'AttendanceRecords'])->name('admin.attendanceRecords');
Route::get('/admindash',[Display::class,'Display3'])->name('admin.dashboard');
Route::get('/position',[Display::class,'Display4'])->name('admin.position');
Route::get('/payroll',[Display::class,'Display8'])->name('admin.payroll');
Route::get('/deduction',[Display::class,'Display9'])->name('admin.deduction');
Route::get('/cashadvance',[Display::class,'Display10'])->name('admin.cashadvance');
Route::get('/',[Display::class,'Display11'])->name('admin.login');

Route::delete('/deduction/{id}', [Display::class, 'deleteDeduction'])->name('deduction.delete');
Route::delete('/position/{id}', [Display::class, 'deletePosition']);
Route::get('/schedule', [Display::class, 'Display5'])->name('admin.schedule');
Route::post('/schedule/store', [Display::class, 'addSchedule'])->name('schedule.store');
Route::put('/schedule/{schedule_id}', [Display::class, 'updateSchedule'])->name('schedule.update');
Route::delete('/schedule/{schedule_id}', [Display::class, 'deleteSchedule'])->name('schedule.delete');

Route::post('/AddDeduction', [Display::class, 'AddDeduction']);
Route::put('/deduction/{id}', [Display::class, 'updateDeduction'])->name('deduction.update');

Route::get('/admin/holiday', [Display::class, 'Display6'])->name('admin.holiday');
Route::post('/admin/holiday/add', [Display::class, 'addHoliday'])->name('admin.addHoliday');
Route::put('/holiday/{holiday_id}', [Display::class, 'updateHoliday'])->name('holiday.update');
Route::delete('/holiday/{holiday_id}', [Display::class, 'deleteHoliday'])->name('holiday.delete');




Route::post('/overtime', [Display::class, 'addOvertime'])->name('addOvertime');
Route::get('/overtime', [Display::class, 'Display7'])->name('admin.overtime'); 
Route::delete('/overtime/{id}', [Display::class, 'deleteOvertime'])->name('deleteOvertime');
Route::put('/overtime/{id}', [Display::class, 'updateOvertime'])->name('updateOvertime');

Route::get('/cashadvance/{id}/edit', [Display::class, 'edit'])->name('cashadvance.edit');
Route::put('/cashadvance/{id}', [Display::class, 'update'])->name('cashadvance.update');
Route::delete('/cashadvance/{id}', [Display::class, 'destroy'])->name('cashadvance.destroy');
Route::post('/store', [Display::class, 'store'])->name('admin.cashadvance.store');

Route::post('/loginAuth', [Display::class,'loginAuth'])->name('admin.loginAuth');
Route::post('/Submit', [Display::class,'Submit'])->name('admin.submit');
Route::post('/add', [Display::class,'add'])->name('admin.add');

Route::post('/position/save', [Display::class, 'saveposition'])->name('admin.saveposition');
Route::middleware('auth')->get('/dashboard', [Display::class, 'Display1']);

Route::get('/employee/{id}', [Display::class, 'getEmployee'])->name('employee.get');

Route::put('/employee/{id}', [Display::class, 'updateEmployee'])->name('employee.update');
Route::put('/position/{id}', [Display::class, 'updatePosition'])->name('position.update');

