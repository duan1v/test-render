<?php

namespace App\Livewire;

use App\Models\Employee as EmployeeModel;
use Livewire\Component;
use Livewire\WithPagination;
use function App\setInitValueWithCookie;

class Employee extends Component
{
    use WithPagination;

    public $columns;

    public $limit = 15;
    public $validations;
    public $colsOrder = [];

    public $search;
    public $selectedCols = [];
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->columns = EmployeeModel::COLUMNS;
        $ck = array_keys($this->columns);
        $this->selectedCols = setInitValueWithCookie('selectedColsEe', $ck);
        $this->colsOrder = setInitValueWithCookie('colsOrderEe', $ck);
        $this->limit = setInitValueWithCookie('ee-limit', 15, false);
    }

    protected function getListeners()
    {
        return [
            'updateCols' => 'setCols',
            'search' => 'search',
            'updateColsOrder' => 'updateColsOrder',
            'resetCols' => 'resetCols',
            'changeLimit' => 'changeLimit',
            'localStorageSelectedColsReceived' => 'localStorageSelectedColsReceived',
            'localStorageColsOrderReceived' => 'localStorageColsOrderReceived',
            'localStorageLimitReceived' => 'localStorageLimitReceived',
        ];
    }

    public function handleLimitChange()
    {
        $this->dispatch('changeLimit', $this->limit);
        $this->dispatch('localStorageLimitSaved', $this->limit);
    }

    public function localStorageSelectedColsReceived($data)
    {
        $this->selectedCols = $data;
    }

    public function localStorageColsOrderReceived($data)
    {
        $this->colsOrder = $data;
    }

    public function setCols($cols)
    {
        $this->selectedCols = json_decode($cols, true);
    }

    public function changeLimit($limit)
    {
        $this->resetPage();
        $this->limit = (int)$limit;
    }

    public function localStorageLimitReceived($limit)
    {
        $this->limit = (int)$limit;
    }

    public function updateColsOrder($colsOrder)
    {
        $this->colsOrder = $colsOrder;
    }

    public function search($search)
    {
        $this->resetPage();
        $this->search = $search;
    }

    public function resetCols()
    {
        $this->selectedCols = array_keys($this->columns);
    }

    public function render()
    {
        if ($this->selectedCols) {
            $rows = EmployeeModel::query()
                ->select([...$this->selectedCols, 'id'])
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('brief', 'like', '%' . $this->search . '%');
                })
                ->orderBy('id')
                ->paginate($this->limit ?: 15);
        } else {
            $rows = collect();
        }
        $this->dispatch('resetPopover');
        return view('livewire.employee', compact('rows'));
    }
}
