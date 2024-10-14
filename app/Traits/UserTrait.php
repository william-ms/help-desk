<?php
namespace App\Traits;

trait UserTrait
{
    public $UserStatus = [
        1 => [
            'status' => 'ativo',
            'color' => 'success'
        ],
        2 => [
            'status' => 'inativo',
            'color' => 'danger'
        ],
    ];
}
