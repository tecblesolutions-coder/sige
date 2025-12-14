<?php

use App\Http\Livewire\Attendance\AttendanceIndex;
use App\Http\Livewire\City\CityIndex;
use App\Http\Livewire\Country\CountryIndex;
use App\Http\Livewire\Department\DepartmentIndex;
use App\Http\Livewire\Employee\Create as EmployeeCreate;
use App\Http\Livewire\Employee\Edit as EmployeeEdit;
use App\Http\Livewire\Employee\EmployeeIndex;
use App\Http\Livewire\MyAttendance;
use App\Http\Livewire\Position\PositionIndex;
use App\Http\Livewire\Reports\ReportsIndex;
use App\Http\Livewire\Roles\RoleIndex;
use App\Http\Livewire\State\StateIndex;
use App\Http\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->middleware('permission:ver panel')->name('dashboard');
    Route::get('/mi-asistencia', MyAttendance::class)->name('my-attendance.index');
    Route::get('/users', UserIndex::class)->middleware('permission:ver usuarios')->name('users.index');
    Route::get('/countries', CountryIndex::class)->middleware('permission:gestionar catalogos')->name('countries.index');
    Route::get('/states', StateIndex::class)->middleware('permission:gestionar catalogos')->name('states.index');
    Route::get('/cities', CityIndex::class)->middleware('permission:gestionar catalogos')->name('cities.index');
    Route::get('/departments', DepartmentIndex::class)->middleware('permission:gestionar catalogos')->name('departments.index');
    Route::get('/positions', PositionIndex::class)->middleware('permission:gestionar catalogos')->name('positions.index');
    Route::get('/employees', EmployeeIndex::class)->middleware('permission:ver empleados')->name('employees.index');
    Route::get('/employees/create', EmployeeCreate::class)->middleware('permission:crear empleados')->name('employees.create');
    Route::get('/employees/{employee}/edit', EmployeeEdit::class)->middleware('permission:editar empleados')->name('employees.edit');
    Route::get('/roles', RoleIndex::class)->name('roles.index')->middleware('permission:gestionar roles');
    Route::get('/asistencia', AttendanceIndex::class)->middleware('permission:ver asistencia')->name('attendance.index');
    Route::get('/reportes', ReportsIndex::class)->middleware('permission:ver reportes')->name('reports.index');
});
