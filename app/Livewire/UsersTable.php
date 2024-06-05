<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;
    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 5;

    #[Url(history: true)]
    public $UserType = '';

    #[Url(history: true)]
    public $sortBy = 'name';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function delete(User $user)
    {
        $user->delete();
    }
    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == 'ASC') ? 'DESC' : 'ASC';
        }
        $this->sortBy = $sortByField;
    }

    public function render()
    {
        return view('livewire.users-table', ['users' => User::Search($this->search)->when($this->UserType !== '', function ($query) {
            $query->where('is_admin', $this->UserType);
        })->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage)]);
    }
}
