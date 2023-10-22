<?php

namespace App\Livewire;

use App\Entities\Field;
use App\Enums\FieldTypes1;
use App\Jobs\GenMigrateJob;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Migrate1 extends Component
{
    public $step1;
    public $step2;

    public $tableName;
    public $fields;

    public $step1Data;

    public $fieldTypes;

    public function mount()
    {
        $this->fieldTypes = FieldTypes1::forSelect();
    }

    #[On('handleChangeDatum')]
    public function handleChangeDatum($sk, $step1Datum)
    {
        data_set($this->step1Data, $sk, $step1Datum);
    }

    public function handStep1()
    {
        if ($this->fields) {
            $step1Data = [];
            foreach (explode("\n", $this->fields) as $f) {
                if (!$f) {
                    continue;
                }
                $step1Data[] = new Field(name: preg_replace('/[^a-zA-Z0-9]+/', '_', Str::snake($f)), type: 'string', comment: $f);
            }
            $this->step1Data = $step1Data;
        }
    }

    public function handStep2()
    {
        dispatch(new GenMigrateJob($this->step1Data, Str::snake($this->tableName)));
    }

    public function render()
    {
        return view('livewire.migrate1');
    }
}
