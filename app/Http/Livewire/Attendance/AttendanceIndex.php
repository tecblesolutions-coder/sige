<?php

namespace App\Http\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use Livewire\Component;

class AttendanceIndex extends Component
{
    public $date;

    public function mount()
    {
        $this->date = today()->toDateString();
    }

    public function markIn(Employee $employee)
    {
        abort_unless(auth()->user()->can('gestionar asistencia'), 403, 'No tienes permiso para realizar esta acción.');

        $attendance = Attendance::where('employee_id', $employee->id)->where('date', $this->date)->first();

        if ($attendance && $attendance->time_in) {
            session()->flash('attendance-message', 'Este empleado ya registró su entrada hoy.');

            return;
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'user_id' => $employee->user_id, // Assuming employees are linked to users
            'date' => $this->date,
            'time_in' => now(),
            'status' => 'abierta',
        ]);

        session()->flash('attendance-message', "Entrada registrada para {$employee->first_name}.");
    }

    public function markOut(Employee $employee)
    {
        abort_unless(auth()->user()->can('gestionar asistencia'), 403, 'No tienes permiso para realizar esta acción.');

        $attendance = Attendance::where('employee_id', $employee->id)->where('date', $this->date)->first();

        if (! $attendance || ! $attendance->time_in) {
            session()->flash('attendance-message', 'Este empleado no ha registrado su entrada todavía.');

            return;
        }

        if ($attendance->time_out) {
            session()->flash('attendance-message', 'Este empleado ya registró su salida hoy.');

            return;
        }

        $attendance->update([
            'time_out' => now(),
            'status' => 'cerrada',
        ]);

        session()->flash('attendance-message', "Salida registrada para {$employee->first_name}.");
    }

    public function render()
    {
        $employees = Employee::where('status', 'activo')->orderBy('first_name')->get();
        $todaysAttendances = Attendance::where('date', $this->date)
            ->get()
            ->keyBy('employee_id');

        return view('livewire.attendance.attendance-index', [
            'employees' => $employees,
            'todaysAttendances' => $todaysAttendances,
        ])->layout('layouts.main');
    }
}
