<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\User;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    // Student properties
    public string $matric_no = '';
    public string $program = '';
    public int $intake = 0;
    public string $phone = '';
    public string $state = '';
    public string $address = '';
    public string $motto = '';
    public string $faculty = '';
    public $img = null;
    // Admin properties
    public string $admin_phone = '';
    public string $admin_email = '';
    public string $admin_faculty = '';
    public string $pose = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;

        if ($user->role === 'admin') {
            $admin = $user->admin;
            if ($admin) {
                $this->phone = $admin->phone ?? '';  // Changed from admin_phone
                $this->email = $admin->email ?? $user->email;  // Changed from admin_email
                $this->faculty = $admin->faculty ?? '';  // Changed from admin_faculty
                $this->pose = $admin->pose ?? '';  // This was correct
                $this->img = $admin->img ?? null;
            }
        } else {
            $student = $user->student;
            if ($student) {
                $this->matric_no = $student->matric_no ?? '';
                $this->program = $student->program ?? '';
                $this->intake = $student->intake ?? 0;
                $this->phone = $student->phone ?? '';
                $this->state = $student->state ?? '';
                $this->address = $student->address ?? '';
                $this->motto = $student->motto ?? '';
                $this->faculty = $student->faculty ?? '';
                $this->img = $student->img ?? null;
            }
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $admin = $user->admin;

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
                'admin_phone' => ['nullable', 'string', 'max:15'],
                'admin_email' => ['nullable', 'string', 'max:255', 'email'],
                'admin_faculty' => ['nullable', 'string', 'max:255'],
                'pose' => ['nullable', 'string', 'max:100'],
                'img' => ['nullable', 'image', 'max:1024'], // 1MB Max
            ]);

            // Update user information
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            // Update admin information
            if ($admin) {
                $adminData = [
                    'phone' => $validated['admin_phone'],
                    'email' => $validated['admin_email'],
                    'faculty' => $validated['admin_faculty'],
                    'pose' => $validated['pose'],
                ];

                if ($this->img) {
                    $adminData['img'] = $this->img->store('admin-photos', 'public');
                }

                $admin->update($adminData);
            }
        } else {
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
                'img' => ['nullable', 'image', 'max:1024'], // 1MB Max
            ]);

            // Update user information
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            // Update student information
            if ($student) {
                $studentData = collect($validated)
                    ->except(['name', 'email'])
                    ->toArray();

                if ($this->img) {
                    $studentData['img'] = $this->img->store('profile-photos', 'public');
                }

                $student->update($studentData);
            }
        }

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}








