<?php

namespace App\Http\Livewire;

use App\Models\Attendance;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class MyAttendance extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $rangeStart;

    public $rangeEnd;

    public $employeeId; // To link to the employee profile

    public function mount()
    {
        $today = now()->format('Y-m-d');
        $this->rangeStart = $today;
        $this->rangeEnd = $today;

        // Ensure the logged-in user is associated with an employee profile
        $user = auth()->user();
        if ($user && $user->employee) {
            $this->employeeId = $user->employee->id;
        } else {
            // Handle case where user is not linked to an employee (e.g., redirect or show error)
            session()->flash('error', 'Tu perfil de usuario no está vinculado a un empleado. Contacta a administración.');

            return redirect()->route('dashboard');
        }
    }

    // Method to get today's attendance record for the logged-in user
    public function getTodayAttendanceProperty()
    {
        return Attendance::firstOrNew([
            'user_id' => auth()->id(),
            'employee_id' => $this->employeeId, // Use employee_id
            'date' => now()->toDateString(),
        ]);
    }

    public function markIn()
    {
        if (! $this->employeeId) {
            session()->flash('error', 'No tienes un perfil de empleado asociado para registrar asistencia.');

            return;
        }

        $attendance = Attendance::firstOrCreate(
            ['user_id' => auth()->id(), 'employee_id' => $this->employeeId, 'date' => now()->toDateString()]
        );

        if ($attendance->time_in) {
            session()->flash('attendance-message', 'Ya registraste la entrada hoy.');

            return;
        }

        $attendance->time_in = now();
        $attendance->status = 'abierta';
        $attendance->save();
        session()->flash('attendance-message', 'Entrada registrada.');
    }

    public function markOut()
    {
        if (! $this->employeeId) {
            session()->flash('error', 'No tienes un perfil de empleado asociado para registrar asistencia.');

            return;
        }

        $attendance = Attendance::where('user_id', auth()->id())
            ->where('employee_id', $this->employeeId)
            ->where('date', now()->toDateString())
            ->first();

        if (! $attendance || ! $attendance->time_in) {
            session()->flash('attendance-message', 'Primero debes registrar entrada.');

            return;
        }
        if ($attendance->time_out) {
            session()->flash('attendance-message', 'Ya registraste salida hoy.');

            return;
        }

        $attendance->time_out = now();
        $attendance->status = 'cerrada';
        $attendance->save();
        session()->flash('attendance-message', 'Salida registrada.');
    }

    public function render()
    {
        $query = Attendance::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->orderBy('time_in', 'desc');

        if ($this->rangeStart) {
            $query->whereDate('date', '>=', $this->rangeStart);
        }
        if ($this->rangeEnd) {
            $query->whereDate('date', '<=', $this->rangeEnd);
        }

        $attendances = $query->paginate(10);

        return view('livewire.my-attendance', [
            'attendances' => $attendances,
        ])->layout('layouts.main');
    }
}
