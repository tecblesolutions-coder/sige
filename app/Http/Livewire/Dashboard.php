<?php

namespace App\Http\Livewire;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $employeesCount = Employee::count();
        $departmentsCount = Department::count();
        $usersCount = User::count();
        $activeCount = Employee::where('status', 'activo')->count();
        $inactiveCount = Employee::where('status', 'inactivo')->count();
        $attendanceToday = Attendance::whereDate('date', now()->toDateString())->whereNotNull('time_in')->count();

        $byPosition = Position::withCount('employees')->get();
        $positionLabels = $byPosition->pluck('name');
        $positionValues = $byPosition->pluck('employees_count');

        $isEmployee = auth()->user()->hasRole('Empleado');
        $myAttendance = $isEmployee
            ? Attendance::where('user_id', auth()->id())->orderBy('date', 'desc')->orderBy('time_in', 'desc')->take(5)->get()
            : collect();

        return view('livewire.dashboard', [
            'employeesCount' => $employeesCount,
            'departmentsCount' => $departmentsCount,
            'usersCount' => $usersCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
            'attendanceToday' => $attendanceToday,
            'positionLabels' => $positionLabels,
            'positionValues' => $positionValues,
            'isEmployee' => $isEmployee,
            'myAttendance' => $myAttendance,
        ]);
    }
}
