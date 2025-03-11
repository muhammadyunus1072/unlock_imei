<?php

use Livewire\Component;

if (!function_exists('consoleLog')) {
    function consoleLog(Component $component, $data)
    {
        $component->dispatch('consoleLog', $data);
    }
}

if (!function_exists('imaskToValue')) {
    function imaskToValue($data)
    {
        return str($data)->replace('.', '')->replace(',', '.')->toFloat();
    }
}

if (!function_exists('valueToImask')) {
    function valueToImask($data)
    {
        return str($data)->replace('.', ',')->toString();
    }
}

if (!function_exists('numberFormat')) {
    function numberFormat($number, $decimalPoin = 2)
    {
        $decimalPoin = fmod($number, 1) !== 0.00 ? $decimalPoin : 0;
        return number_format($number, $decimalPoin, ",", ".");
    }
}

if (!function_exists('denominator')) {
    function denominator($data)
    {
        $nilai = abs($data);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = denominator($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = denominator($nilai / 10) . " Puluh" . denominator($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . denominator($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = denominator($nilai / 100) . " Ratus" . denominator($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . denominator($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = denominator($nilai / 1000) . " Ribu" . denominator($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = denominator($nilai / 1000000) . " Juta" . denominator($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = denominator($nilai / 1000000000) . " Milyar" . denominator(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = denominator($nilai / 1000000000000) . " Trilyun" . denominator(fmod($nilai, 1000000000000));
        }
        return $temp;
    }
}
