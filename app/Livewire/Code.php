<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Component;
use function App\setInitValueWithCookie;

class Code extends Component
{
    public $showJobList;
    public $nums;
    public $ems;
    public $job;
    public $num = 0;

    public function mount()
    {
        $this->nums = [1 => '一', 2 => '二', 3 => '三'];
        $this->num = setInitValueWithCookie('ee-num', 0, false);
    }

    public function handleNumChange()
    {
        $this->dispatch('localStorageNumSaved', $this->num);
    }

    #[On('updateJob')]
    public function updateJob($job)
    {
        $this->showJobList = 1;
        $this->job = $job;
    }

    #[On('closeJobList')]
    public function closeJobList()
    {
        $this->showJobList = 0;
    }

    public function render()
    {
        if ($this->job) {
            $this->ems = \App\Models\Employee::query()
                ->where('job', 'like', '%' . $this->job . '%')
                ->when($this->num > 0, function (Builder $query) {
                    $query->inRandomOrder()->take($this->num);
                })
                ->get();
        }
        return view('livewire.code');
    }
}
