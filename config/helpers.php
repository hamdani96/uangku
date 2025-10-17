<?php

use Carbon\Carbon;
use Google\Client;
use Illuminate\Support\Facades\Http;

if (!function_exists('TanggalID')) {

    /**
     * TanggalID
     *
     * @param $format
     * @param mixed $tanggal
     * @param string $bahasa
     * @return false|string|string[]
     */
    function TanggalID($format, $tanggal = "now", $bahasa = "id")
    {
        if ($tanggal) {
            $en = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
            $id = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
            // mengganti kata yang berada pada arrayen dengan arrayid, fr (defaultid)

            if (strpos($format, 'H:i') !== false) {
                $jam = $tanggal->format('H:i');
                if ($jam >= '04:00' && $jam < '10:00') {
                    $salam = 'pagi';
                } elseif ($jam >= '10:00' && $jam < '15:00') {
                    $salam = 'siang';
                } elseif ($jam >= '15:00' && $jam < '18:00') {
                    $salam = 'sore';
                } else {
                    $salam = 'malam';
                }

                return str_replace($en, $id, date($format, strtotime($tanggal))) . " " . $salam;
            }

            return str_replace($en, $id, date($format, strtotime($tanggal)));
        } else {
            return '-';
        }
    }
}

if (!function_exists('formatUang')) {

    /**
     * formatUang
     *
     * @param $angka
     * @return string
     */
    function formatUang($angka): string
    {
        return number_format($angka, 0, ',', '.');
    }
}

