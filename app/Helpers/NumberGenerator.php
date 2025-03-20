<?php

namespace App\Helpers;

use Carbon\Carbon;

class NumberGenerator
{
    const COMPANY_CODE = "BOOK";
    const RESET_TYPE_YEARLY = 1;
    const RESET_TYPE_MONTHLY = 2;
    const RESET_TYPE_DAILY = 3;
    const SEPARATOR = "/";

    public static function generate(
        $className,
        $code,
        $zeroPad = 5,
        $resetType = self::RESET_TYPE_DAILY,
    ) {
        $dateTime = Carbon::now();
        $year_now = $dateTime->year;
        $month_now = $dateTime->month;
        $day_now = $dateTime->day;

        $lastModel = $className::withTrashed()->select('number')
            ->when($resetType == self::RESET_TYPE_YEARLY, function ($query) use ($year_now) {
                $query->whereYear('created_at', '=', $year_now);
            })
            ->when($resetType == self::RESET_TYPE_MONTHLY, function ($query) use ($year_now, $month_now) {
                $query->whereMonth('created_at', '=', $month_now)
                    ->whereYear('created_at', '=', $year_now);
            })
            ->when($resetType == self::RESET_TYPE_DAILY, function ($query) {
                $query->whereDate('created_at', '=', now()->toDateString());
            })
            ->orderBy('id', 'DESC')
            ->first();

        if (!empty($lastModel)) {
            $lastNumber = intval(explode(self::SEPARATOR, $lastModel->number)[1]);
        } else {
            $lastNumber = 0;
        }

        // Get Current Number
        $currentNumber = strval($lastNumber + 1);
        $currentNumber = str_pad($currentNumber, $zeroPad, "0", STR_PAD_LEFT);

        // Roman Month
        $roman_month = RomanConverter::toRoman($month_now);

        // Roman Day
        $roman_day = RomanConverter::toRoman($day_now);

        // Generate Format Number
        $formats = [
            self::RESET_TYPE_YEARLY => "$code/$currentNumber/$year_now",
            self::RESET_TYPE_MONTHLY => "$code/$currentNumber/$roman_month/$year_now",
            self::RESET_TYPE_DAILY => "$code/$currentNumber/$roman_day/$roman_month/$year_now",
        ];
    
        return $formats[$resetType];
    }


    public static function simpleYearCode(
        $className,
        $code,
        $date,
        $zeroPad = 6,
    ) {
        $dateTime = Carbon::parse($date);
        $year = substr($dateTime->year, 2);

        $lastModel = $className::withTrashed()->select('number')
            ->orderBy('id', 'DESC')
            ->first();

        if (!empty($lastModel)) {
            $lastNumber = intval(substr($lastModel->number, 4));
        } else {
            $lastNumber = 0;
        }

        // Get Current Number
        $currentNumber = strval($lastNumber + 1);
        $currentNumber = str_pad($currentNumber, $zeroPad, "0", STR_PAD_LEFT);

        return "{$code}{$year}{$currentNumber}";
    }
}
