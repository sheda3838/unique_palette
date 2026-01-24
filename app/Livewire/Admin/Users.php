<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $search = '';

    #[Layout('layouts.app')]
    public function render()
    {
        $query = User::select('id', 'name', 'email', 'role', 'profile_photo_path', 'created_at')
            ->selectRaw('profile_image_blob IS NOT NULL as has_profile_image_blob')
            ->latest();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        return view('livewire.admin.users', [
            'users' => $query->paginate(10),
        ]);
    }
}
