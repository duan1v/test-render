<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class ListComponent extends Component
{
    use WithPagination;

    public function render()
    {
        $data = Employee::paginate(10);
        return view('livewire.list-component', ['data' => $data]);
    }
}
