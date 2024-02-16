<?php 
declare(strict_types=1);

$tz = ini_get("date.timezone");
$zone = new DateTimeZone($tz);
function GetActualTime(): string {
    global $zone;
    $dt = new DateTime("now", $zone);
    return $dt->format("Y-m-d h:i:s");
}

function GetInterval($time): string {
    global $zone;
    $past = new DateTime($time, $zone);
    $now = new DateTime(GetActualTime(), $zone);
    $diff = $now->getTimestamp() - $past->getTimestamp();
    if($diff < 60){
        return $diff." segundos";
    }
    else if($diff < 3600){
        return floor($diff/60)." minutos";
    }
    else if($diff < 216000) {
        return floor($diff/3600)." horas";
    }
    return floor($diff/216000)." dias";
}

?>
