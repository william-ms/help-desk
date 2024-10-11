<?php

use App\Models\Log;
use App\Models\LogItem;
use Illuminate\Database\Eloquent\Model;

function register_log(Model $Model, string $type, int $status, array $data = [], $body = null): array
{
    $Log = create_log($Model);

    $LogItem = create_log_item($Log, $Model, $type, $status, $data, $body);

    return ['Log' => $Log, 'LogItem' => $LogItem];
}

function create_log(Model $Model): Log
{
    $Log = Log::where('model_type', $Model->type)->where('model_id', $Model->id)->first();

    if(empty($Log)) {
        $Log = Log::create([
            'model_type' => $Model->type,
            'model_id' => $Model->id,
            'model_name' => $Model->name,
        ]);
    }

    return $Log;
}

function create_log_item(Log $Log, Model $Model, string $type, int $status, array $data = [], $body = null): LogItem
{
    $labels = [
        'create' => 'Cadastro',
        'update' => 'Atualizado',
        'delete' => 'Deletado',
        'restore' => 'Restaurado',
    ];

    if ($type == 'update' && !array_key_exists('deleted_at', $Model->getDirty())) {
        $data = array_merge($Model->getDirty(), $data);

        if(!empty($data['name'])) {
            $Log->update(['model_name' => $Model->name]);
        }
    } else {
        $data = array_merge($Model->getAttributes(), $data);
    }

    $LogItem = LogItem::create([
        'log_id' => $Log->id,
        'user_id' => auth()->id() || 1,
        'type' => $type,
        'label' => $labels[$type] ?? null,
        'status' => $status,
        'action' => create_action($Model, $data, $type),
        'body' => $body,
    ]);

    return $LogItem;
}


function create_action(Model $Model, array $data, string $type): string
{
    $labels = [
        'create' => 'Cadastrou',
        'delete' => 'Deletou',
        'restore' => 'Restaurou'
    ];

    $fields = $Model->fields;
    $action = [];

    $x = [
        'p' => ['element' => "p", 'style' => "class='m-0'"],
        'ul' => ['element' => "ul", 'style' => "class='m-0'"],
        'li' => ['element' => "li", 'style' => ""]
    ];

    if(!empty($labels[$type])) {
        $action[] = "<{$x['p']['element']} {$x['p']['style']}>{$labels[$type]} {$Model->type} de id <b>{$Model->id}</b>;</{$x['p']['element']}>";
    }

    unset($data['created_at'], $data['updated_at'], $data['deleted_at'], $data['id']);

    foreach($data as $k => $v) {
        if(!empty($v['values'])) {

            $action[] = "<{$x['p']['element']} {$x['p']['style']}>{$v['title']}:</p>";

            $action[][] = "<{$x['ul']['element']} {$x['ul']['style']}>";
            foreach($v['values'] as $m => $n) {
                $action[count($action) - 1][] = "<{$x['li']['element']} {$x['li']['style']}>{$n}</{$x['li']['element']}>";
            }
            $action[count($action) - 1][] ="</{$x['ul']['element']}>";
        }

        if(!empty($v['value'])) {
            $action[] = "<{$x['p']['element']} {$x['p']['style']}>{$v['value']};</{$x['p']['element']}>";
        }

        if ($type == 'update' && !is_array($v) && !empty($v)) {
            $action[] = "<{$x['p']['element']} {$x['p']['style']}>Alterou {$fields[$k]} de <b>{$Model->getOriginal()[$k]}</b> para <b>{$v}</b>;</{$x['p']['element']}>";
        }

        if ($type != 'update' && !is_array($v) && !empty($v)) {
            $action[] = "<{$x['p']['element']} {$x['p']['style']}>". mb_ucfirst($fields[$k]) ." <b>{$v}</b>;</{$x['p']['element']}>";
        }
    }

    return json_encode($action);  
}
