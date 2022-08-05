<?php

// Change Number to Month Name
function changeNumberToMonth($number, $lang = null, $type = 'full')
{
    if ($lang == null) {
        $lang = config('app.locale');
    }

    $number = intval($number);

    $month_data = [
        'id' => [
            '1' => ['full' => 'Januari', 'med' => 'Jan'],
            '2' => ['full' => 'Februari', 'med' => 'Feb'],
            '3' => ['full' => 'Maret', 'med' => 'Mar'],
            '4' => ['full' => 'April', 'med' => 'Apr'],
            '5' => ['full' => 'Mei', 'med' => 'Mei'],
            '6' => ['full' => 'Juni', 'med' => 'Jun'],
            '7' => ['full' => 'Juli', 'med' => 'Jul'],
            '8' => ['full' => 'Agustus', 'med' => 'Agu'],
            '9' => ['full' => 'September', 'med' => 'Sep'],
            '10' => ['full' => 'October', 'med' => 'Oct'],
            '11' => ['full' => 'November', 'med' => 'Nov'],
            '12' => ['full' => 'Desember', 'med' => 'Des'],
        ],
        'en' => [
            '1' => ['full' => 'January', 'med' => 'Jan'],
            '2' => ['full' => 'February', 'med' => 'Feb'],
            '3' => ['full' => 'March', 'med' => 'Mar'],
            '4' => ['full' => 'April', 'med' => 'Apr'],
            '5' => ['full' => 'May', 'med' => 'May'],
            '6' => ['full' => 'June', 'med' => 'Jun'],
            '7' => ['full' => 'July', 'med' => 'Jul'],
            '8' => ['full' => 'August', 'med' => 'Aug'],
            '9' => ['full' => 'September', 'med' => 'Sep'],
            '10' => ['full' => 'Oktober', 'med' => 'Okt'],
            '11' => ['full' => 'November', 'med' => 'Nov'],
            '12' => ['full' => 'December', 'med' => 'Dec'],
        ],
    ];

    $month_name = "-";
    if (!array_key_exists($lang, $month_data)) {
        $lang = 'en';
    }

    if (array_key_exists($number, $month_data[$lang])) {
        if (!array_key_exists($type, $month_data[$lang][$number])) {
            $type = 'full';
        }

        $month_name = $month_data[$lang][$number][$type];
    }

    return $month_name;
}
