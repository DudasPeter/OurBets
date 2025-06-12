<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function formatTime($time){
        return Carbon::parse($time)->locale('sk')->isoFormat('DD.MM.  ddd   HH:mm');
    }
}
