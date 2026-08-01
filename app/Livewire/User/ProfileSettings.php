<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.user')]
class ProfileSettings extends Component
{
    use WithFileUploads;

    public $sidebarOpen = false;
    public $sidebarCollapsed = false;

    public $name;
    public $email;
    public $avatar;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'avatar' => 'nullable|image|max:2048',
        'current_password' => 'nullable|string',
        'new_password' => 'nullable|string|min:8|confirmed',
    ];

    public function mount(): void
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function updateProfile(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->avatar) {
            if ($user->getFirstMedia('avatar')) {
                $user->clearMediaCollection('avatar');
            }
            $user->addMedia($this->avatar->getRealPath())
                ->toMediaCollection('avatar');
        }

        $user->save();
        $this->dispatch('toast', type: 'success', message: 'Profile updated successfully!');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
        
        $this->dispatch('toast', type: 'success', message: 'Password updated successfully!');
    }

    public function render()
    {
        return view('livewire.user.profile-settings');
    }
}