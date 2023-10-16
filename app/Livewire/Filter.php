<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Component;
use function App\setInitValueWithCookie;

class Filter extends Component
{
    public $search;

    public $limit;

    public $columns;

    public $tab = 'none';
    public $selectedCols = [];
    public $colsOrder = [];

    public function mount()
    {
        // 在 mount 方法中初始化变量
        $this->columns = Employee::COLUMNS;
        $ck = array_keys($this->columns);
        $this->selectedCols = setInitValueWithCookie('selectedColsEe', $ck);
        $this->colsOrder = setInitValueWithCookie('colsOrderEe', $ck);
        $this->limit = setInitValueWithCookie('ee-limit', 15, false);
    }

    protected function getListeners()
    {
        return [
            'changeFilterTab' => 'changeFilterTab',
            'resetCols' => 'resetCols',
            'updateColsOrder' => 'updateColsOrder',
            'localStorageSelectedColsReceived' => 'localStorageSelectedColsReceived',
            'localStorageColsOrderReceived' => 'localStorageColsOrderReceived',
            'localStorageLimitReceived' => 'localStorageLimitReceived',
        ];
    }

    public function localStorageSelectedColsReceived($data)
    {
        $this->selectedCols = $data;
    }

    public function localStorageColsOrderReceived($data)
    {
        $this->colsOrder = $data;
    }

    public function resetCols()
    {
        $this->selectedCols = array_keys($this->columns);
        $this->dispatch('localStorageSelectedColsSaved', json_encode($this->selectedCols));
    }

    public function changeFilterTab($tab)
    {
        if ($this->tab == $tab) {
            $this->tab = 'none';
        } else {
            $this->tab = $tab;
        }
        $this->dispatch('changeTab', $this->tab);
    }

    public function changeSelectedCols($cid)
    {
        $this->selectedCols = array_filter($this->colsOrder, function ($item) use ($cid) {
            if ($item !== $cid && in_array($item, $this->selectedCols))
                return true;
            elseif ($item == $cid) {
                return !in_array($item, $this->selectedCols);
            }
            return false;
        });
        $this->dispatch('localStorageSelectedColsSaved', json_encode($this->selectedCols));
    }

    public function updateColsOrder($list)
    {
        $this->colsOrder = array_unique($list);
    }

    public function handleLimitChange()
    {
        $this->dispatch('changeLimit', $this->limit);
        $this->dispatch('localStorageLimitSaved', $this->limit);
    }

    public function localStorageLimitReceived($limit)
    {
        $this->limit = (int)$limit;
    }

    public function render()
    {
        return view('livewire.filter');
    }
}
