<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class Crud extends Component
{
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
    }

    public function render()
    {
        $users = User::where('id', '!=', auth('web')->id())->get();
        return view('livewire.crud', compact('users'));
    }
}
