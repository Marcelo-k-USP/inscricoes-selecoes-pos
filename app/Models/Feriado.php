<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feriado extends Model
{
    use HasFactory;

    public static function adicionarDiasUteis($date, int $offset) {
        $carbonDate = Carbon::parse($date);
        $holidays = \DB::table('feriados')->pluck('data')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        for ($i = 0; $i < $offset; $i++) {
            $carbonDate->addDay();
            while ($carbonDate->isWeekend() || in_array($carbonDate->format('Y-m-d'), $holidays))
                $carbonDate->addDay();
        }

        return $carbonDate;
    }
}
