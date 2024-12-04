<?php

function calculate_elapsed_time($datetime, $contraction = false)
{
    $seconds = $datetime->diffInSeconds(now());
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2419200);
    $years = round($seconds / 29030400);


    $texts = ($contraction) ? [
        "sec" => ["s" => "s", "p" => "s"],
        "min" => ["s" => "min", "p" => "min"],
        "hour" => ["s" => "hr", "p" => "hrs"],
        "day" => ["s" => "d", "p" => "d"],
        "week" => ["s" => "sem", "p" => "sem"],
        "month" => ["s" => "m", "p" => "m"],
        "year" => ["s" => "a", "p" => "a"],
    ] : [
        "sec" => ["s" => "segundo", "p" => "segundos"],
        "min" => ["s" => "minuto", "p" => "minutos"],
        "hour" => ["s" => "hora", "p" => "horas"],
        "day" => ["s" => "dia", "p" => "dias"],
        "week" => ["s" => "semana", "p" => "semanas"],
        "month" => ["s" => "mês", "p" => "meses"],
        "year" => ["s" => "ano", "p" => "anos"],
    ];

    if ($seconds <= 60) {
        return "Agora";
    } else if ($minutes <= 60) {
        return $minutes == 1 ? "Há 1 {$texts['min']['s']}" : "Há {$minutes} {$texts['min']['p']}";
    } else if ($hours <= 24) {
        return $hours == 1 ? "Há 1 {$texts['hour']['s']}" : "Há {$hours} {$texts['hour']['p']}";
    } else if ($days <= 7) {
        return $days == 1 ? "Há 1 {$texts['day']['s']}" : "Há {$days} {$texts['day']['p']}";
    } else if ($weeks <= 4) {
        return $weeks == 1 ? "Há 1 {$texts['week']['s']}" : "Há {$weeks} {$texts['week']['s']}";
    } else if ($months <= 12) {
        return $months == 1 ? "Há 1 {$texts['month']['s']}" : "Há {$months} {$texts['month']['s']}";
    } else {
        return $years == 1 ? "Há 1 {$texts['year']['s']}" : "Há {$years} {$texts['year']['s']}";
    }
}