<?php
namespace App\Traits;

trait TicketTrait
{
    public $TicketStatus = [
        1 => [
            'text' => "Aberto",
            'color' => 'info',
        ],
        2 => [
            'text' => 'Em tranferência',
            'color' => 'warning',
        ],
        3 => [
            'text' => "Resolvido",
            'color' => 'info',
        ],
        4 => [
            'text' => "Finalizado",
            'color' => 'success',
        ],
        5 => [
            'text' => "Não resolvido",
            'color' => 'warning',
        ],
        6 => [
            'text' => "Em atraso",
            'color' => 'danger',
        ],
        7 => [
            'text' => "Cancelado",
            'color' => 'secondary',
        ],

    ];

    public $TicketAction = [
        1 => [
            'text' => "Usuário cadastrou o ticket",
            'color' => 'info',
        ],
        2 => [
            'text' => "Técnico puxou o ticket",
            'color' => 'warning',
        ],
        3 => [
            'text' => "Técnico respondeu o ticket",
            'color' => 'warning',
        ],
        4 => [
            'text' => "Usuário respondeu o ticket",
            'color' => 'warning',
        ],
        5 => [
            'text' => "Técnico solicitou transferência",
            'color' => 'warning',
        ],
        6 => [
            'text' => "Técnico aceitou a transferência",
            'color' => 'success',
        ],
        7 => [
            'text' => "Técnico recusou a transfêrencia",
            'color' => 'danger',
        ],
        8 => [
            'text' => "Técnico cancelou a transfêrencia",
            'color' => 'info',
        ],
        9 => [
            'text' => "Técnico resolveu o ticket",
            'color' => 'info',
        ],
        10 => [
            'text' => "Usuário finalizou o ticket",
            'color' => 'success',
        ],
    ];
}
