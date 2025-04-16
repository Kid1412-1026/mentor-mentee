<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;

#[Layout('components.layouts.app.sidebar')]
class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $matric_no = '';
    public string $program = '';
    public int $intake = 0;
    public string $phone = '';
    public string $state = '';
    public string $address = '';
    public string $motto = '';
    public string $faculty = '';
    public $img = null;
    public $newImage = null;

    public function mount()
    {
        $user = Auth::user();
        $student = $user->student;

        $this->name = $user->name;
        $this->email = $user->email;

        if ($student) {
            $this->matric_no = $student->matric_no ?? '';
            $this->program = $student->program ?? '';
            $this->intake = $student->intake ?? 0;
            $this->phone = $student->phone ?? '';
            $this->state = $student->state ?? '';
            $this->address = $student->address ?? '';
            $this->motto = $student->motto ?? '';
            $this->faculty = $student->faculty ?? '';
            $this->img = $student->img;
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();
        $student = $user->student;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'matric_no' => ['required', 'string', 'max:15'],
            'program' => ['nullable', 'string', 'max:100'],
            'intake' => ['nullable', 'integer'],
            'phone' => ['nullable', 'string', 'max:15'],
            'state' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'motto' => ['nullable', 'string', 'max:255'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'newImage' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->save();
        }

        $studentData = collect($validated)
            ->except(['name', 'email', 'newImage'])
            ->toArray();

        if ($this->newImage) {
            $studentData['img'] = $this->newImage->store('profile-photos', 'public');
        }

        $student->update($studentData);

        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('livewire.student.profile');
    }
}




