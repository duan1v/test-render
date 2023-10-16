<?php

namespace App\Livewire;

use Livewire\Component;
use function App\setInitValueWithCookie;

class Code extends Component
{
    public $showCodeList;
    public $allBooks;
    public $codeTables;
    public $codeTag;
    public $bookId = 0;

    public function mount()
    {
//        $this->allBooks = CodeBook::all();
        $this->bookId = setInitValueWithCookie('book-id', 0, false);
    }

    protected function getListeners()
    {
        return [
            'updateCodeTag' => 'updateCodeTag',
            'updatesShowCodeList' => 'updatesShowCodeList',
        ];
    }

    public function handleBookIdChange()
    {
        $this->emit('changeBookId', $this->bookId);
        $this->emit('localStorageBookIdSaved', $this->bookId);
    }

    public function updateCodeTag($codeTag)
    {
        $this->showCodeList = 1;
        $this->codeTag = $codeTag;
    }

    public function updatesShowCodeList()
    {
        $this->showCodeList = 0;
    }

    public function changeBookId($bookId)
    {
        $this->bookId = (int)$bookId;
    }

    public function render()
    {
        if ($this->codeTag) {
            $pattern = '/CL(\d+)/';
            if (preg_match($pattern, $this->codeTag, $matches)) {
                $this->codeTables = CodeTable::with(['codes', 'book'])
                    ->when($this->bookId > 0, function ($query) {
                        $query->where('book_id', $this->bookId);
                    })
                    ->where('table_nr', $matches[1])
                    ->get();
            }
        }
        return view('livewire.code');
    }
}
