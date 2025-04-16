<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app.sidebar')]
class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $faculty = '';
    public string $pose = '';
    public $img = null;
    public $newImage = null;

    public function mount()
    {
        $user = Auth::user();
        $admin = $user->admin;

        $this->name = $user->name;
        $this->email = $user->email;
        
        if ($admin) {
            $this->phone = $admin->phone ?? '';
            $this->faculty = $admin->faculty ?? '';
            $this->pose = $admin->pose ?? '';
            $this->img = $admin->img;
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();
        $admin = $user->admin;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'pose' => ['nullable', 'string', 'max:100'],
            'newImage' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        $user->update([
            'name' => $validated['name'],
        ]);

        $adminData = [
            'phone' => $validated['phone'],
            'faculty' => $validated['faculty'],
            'pose' => $validated['pose'],
        ];

        if ($this->newImage) {
            $adminData['img'] = $this->newImage->store('admin-photos', 'public');
        }

        $admin->update($adminData);

        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('livewire.admin.profile');
    }
}