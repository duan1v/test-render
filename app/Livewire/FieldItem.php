<?php

namespace App\Livewire;

use Livewire\Component;

class FieldItem extends Component
{
    public $sk;
    public $step1Datum;

    public $fieldTypes;

    public function handleChangeDatum()
    {
        $this->dispatch('handleChangeDatum', $this->sk, $this->step1Datum);
    }

    public function render()
    {
        return view('livewire.field-item');
    }
}
