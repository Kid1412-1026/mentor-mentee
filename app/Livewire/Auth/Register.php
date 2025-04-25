<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Student;
use App\Models\Admin;
use App\Models\Programme;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';

    // Student fields
    public string $matric_no = '';
    public ?int $programme_id = null;
    public int $intake = 0;

    // Admin fields
    public string $faculty = '';
    public string $pose = '';

    // Add this property to store programmes
    public $programmes = [];

    public function mount()
    {
        $this->programmes = Programme::select('id', 'code', 'name')
            ->orderBy('code')
            ->get();
    }

    public function register(): void
    {
        $baseRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:student,admin'], // Ensure role is validated before creating user
        ];

        $roleSpecificRules = [];
        if ($this->role === 'student') {
            $roleSpecificRules = [
                'matric_no' => ['required', 'string', 'max:15'],
                'programme_id' => ['required', 'exists:programmes,id'],
                'intake' => ['required', 'integer'],
            ];
        } elseif ($this->role === 'admin') {
            $roleSpecificRules = [
                'faculty' => ['required', 'string', 'max:255'],
                'pose' => ['required', 'string', 'max:100'],
            ];
        }

        $validated = $this->validate(array_merge($baseRules, $roleSpecificRules));

        // Create user with explicitly set role
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $this->role, // Use $this->role instead of $validated['role']
        ]);

        if ($this->role === 'student') {
            $programme = Programme::find($validated['programme_id']);
            if (!$programme) {
                throw ValidationException::withMessages([
                    'programme_id' => 'Selected programme not found.',
                ]);
            }

            Student::create([
                'matric_no' => $validated['matric_no'],
                'name' => $validated['name'],
                'programme_id' => $programme->id,
                'program' => $programme->name,
                'email' => $validated['email'],
                'intake' => $validated['intake'],
                'user_id' => $user->id,
                // Remove or comment out the admin_id field
                // It will be assigned later
            ]);
        } elseif ($this->role === 'admin') {
            Admin::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'faculty' => $validated['faculty'],
                'pose' => $validated['pose'],
                'user_id' => $user->id,
            ]);
        }

        event(new Registered($user));
        Auth::login($user);
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'programmes' => $this->programmes
        ]);
    }
}








