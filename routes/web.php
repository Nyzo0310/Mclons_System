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
Route::get('/schedule',[Display::class,'Display5'])->name('admin.schedule');
Route::get('/payroll',[Display::class,'Display8'])->name('admin.payroll');
Route::get('/deduction',[Display::class,'Display9'])->name('admin.deduction');
Route::get('/cashadvance',[Display::class,'Display10'])->name('admin.cashadvance');
Route::get('/login',[Display::class,'Display11'])->name('admin.login');

Route::delete('/deduction/{id}', [Display::class, 'deleteDeduction'])->name('deduction.delete');
Route::delete('/position/{id}', [Display::class, 'deletePosition'])->name('position.delete');
Route::delete('/schedule/{id}', [Display::class, 'deleteSchedule'])->name('schedule.delete'); 
Route::post('/schedule/store', [Display::class, 'AddSched'])->name('schedule.store');

Route::post('/AddDeduction', [Display::class, 'AddDeduction']);
Route::put('/deduction/{id}', [Display::class, 'updateDeduction'])->name('deduction.update');

Route::get('/admin/holiday', [Display::class, 'Display6'])->name('admin.holiday');
Route::post('/admin/holiday/add', [Display::class, 'addHoliday'])->name('admin.addHoliday');
Route::post('/holiday/update', [HolidayController::class, 'updateHoliday'])->name('holiday.update');
Route::delete('/holiday/{id}', [Display::class, 'deleteHoliday'])->name('holiday.delete');

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
Route::post('/AddSched', [Display::class, 'AddSched']);
Route::middleware('auth')->get('/dashboard', [Display::class, 'Display1']);
