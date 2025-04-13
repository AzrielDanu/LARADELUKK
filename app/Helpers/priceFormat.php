<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'RP. ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('removeFormatRupiah')) {
    function removeFormatRupiah($angka)
    {
        return (int) str_replace(['RP. ', '.', ','], '', $angka);
    }
}
