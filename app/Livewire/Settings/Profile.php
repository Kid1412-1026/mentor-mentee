<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

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
    public string $pose = '';
    public $currentImage = null;
    public $newImage = null;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;

        // Handle image path based on user role
        if ($user->role === 'admin') {
            $admin = $user->admin;
            if ($admin) {
                $this->currentImage = $admin->img;
                $this->phone = $admin->phone ?? '';
                $this->faculty = $admin->faculty ?? '';
                $this->pose = $admin->pose ?? '';
            }
        } else {
            $student = $user->student;
            if ($student) {
                $this->currentImage = $student->img;
                $this->matric_no = $student->matric_no ?? '';
                $this->program = $student->program ?? '';
                $this->intake = $student->intake ?? 0;
                $this->phone = $student->phone ?? '';
                $this->state = $student->state ?? '';
                $this->address = $student->address ?? '';
                $this->motto = $student->motto ?? '';
                $this->faculty = $student->faculty ?? '';
            }
        }
    }

    public function updateProfileInformation()
    {
        $user = Auth::user();

        if ($this->newImage) {
            // Delete old image if it exists
            if ($this->currentImage) {
                Storage::disk('public')->delete($this->currentImage);
            }

            // Store new image
            $imagePath = $this->newImage->store('profile-photos', 'public');

            // Update image path based on user role
            if ($user->role === 'admin') {
                $user->admin->update(['img' => $imagePath]);
            } else {
                $user->student->update(['img' => $imagePath]);
            }

            $this->currentImage = $imagePath;
            $this->newImage = null; // Clear the selected image after saving
        }

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
                'phone' => ['nullable', 'string', 'max:15'],
                'faculty' => ['nullable', 'string', 'max:255'],
                'pose' => ['nullable', 'string', 'max:100'],
                'newImage' => ['nullable', 'image', 'max:2048'], // 2MB max
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
                    'phone' => $validated['phone'],
                    'faculty' => $validated['faculty'],
                    'pose' => $validated['pose'],
                ];

                if ($this->newImage) {
                    $adminData['img'] = $this->newImage->store('admin-photos', 'public');
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
                'newImage' => ['nullable', 'image', 'max:2048'], // 2MB max
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

                if ($this->newImage) {
                    $studentData['img'] = $this->newImage->store('profile-photos', 'public');
                }

                $student->update($studentData);
            }
        }

        $this->dispatch('profile-updated');
        $this->reset('newImage'); // This ensures the file input is also cleared
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}
























