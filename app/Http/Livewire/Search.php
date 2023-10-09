<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use Livewire\Component;

class Search extends Component
{
    public $search;
    public $employees;

    public $show = false;

    public $message;
//    protected $queryString = [
//        'search' => ['except' => ''],
//    ];

    public function mount()
    {
        $this->performSearch();
        $this->message = 'Hello World!';
    }

    public function updatedSearch($value)
    {
        // 检查用户按下的键是否为 Enter 键（键码 13）
//        if ($this->search && $value === $this->search && request()->input('key') === 'Enter') {
//            $this->performSearch();
//        }

        $this->show = true;
        $this->performSearch();
        return $value;
    }

    public function render()
    {
        $this->performSearch();
        return view('livewire.search', ['message' => $this->message]);
    }

    public function performSearch()
    {
        $this->employees = Employee::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->get();
    }
}
