<?php

namespace App\Entities;

use Livewire\Wireable;

/**
 * Class Field
 * @package App\Models
 * @version 2023/10/21 0021, 18:51
 *
 */
class Field implements Wireable
{
    public function __construct(public $name, public $type, public $comment)
    {
    }

    public function toLivewire()
    {
        return [
            'name'    => $this->name,
            'type'    => $this->type,
            'comment' => $this->comment,
        ];
    }

    public static function fromLivewire($value)
    {
        $name = $value['name'];
        $type = $value['type'];
        $comment = $value['comment'];

        return new static($name, $type, $comment);
    }
}
