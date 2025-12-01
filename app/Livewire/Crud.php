<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\User;
use Exception;

class Crud extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Url(history: true, keep: true)]
    public $search = '';

    public $perPage = 10;

    public $name, $email, $password, $password_confirmation;

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::where('id', '!=', auth('web')->id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.crud', compact('users'));
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        try{
            User::create([
                'name' => $this->name,
                'slug' => str()->slug($this->name),
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);
            session()->put('success', 'User created successfully');
            $this->reset(['name', 'email', 'password', 'password_confirmation']);
        } catch (Exception $e) {
           session()->put('error', $e->getMessage());
           return;
        }
    }
}

