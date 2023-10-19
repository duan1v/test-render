<?php

namespace App\Livewire;

use Livewire\Component;

class UpdateNote extends Component
{
    public $showNoteModal = 0;

    public $currentEmployee;

    protected $rules = [
        'currentEmployee.brief' => 'required|string|max:255',
    ];

    protected $messages = [
        'currentEmployee.brief.required' => 'The note field is required.',
        'currentEmployee.brief.string'   => 'The note field must be a string.',
        'currentEmployee.brief.max'      => 'The note field cannot exceed 255 characters.',
    ];

    protected function getListeners()
    {
        return [
            'saveNote'              => 'saveNote',
            'updateShowNoteModal'   => 'updateShowNoteModal',
            'updateCurrentEmployee' => 'updateCurrentEmployee',
        ];
    }

    public function updateShowNoteModal()
    {
        $this->showNoteModal = 0;
    }

    public function updateCurrentEmployee($xid)
    {
        $this->currentEmployee = \App\Models\Employee::query()->find($xid);
        $this->showNoteModal = 1;
    }

    public function saveNote()
    {
        if ($this->currentEmployee) {
            $this->currentEmployee->save();
        };
        $this->dispatch('saveNote1');
        $this->showNoteModal = 0;
    }

    public function render()
    {
        return view('livewire.update-note');
    }
}
