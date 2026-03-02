<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\Month;
use Carbon\WeekDay;
use DateTimeInterface;

class TimeHelper
{
    public static function formatTime(DateTimeInterface|WeekDay|Month|string|int|float|null $time)
    {
        return Carbon::parse($time)->locale('sk')->isoFormat('DD.MM.  ddd   HH:mm');
    }
}
