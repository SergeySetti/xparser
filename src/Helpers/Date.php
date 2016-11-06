<?php


namespace Xparser\Helpers;


class Date
{

    public static function rus2eng($dateString)
    {
        $localizedMonths = [
            'Января' => 'January',
            'Февраля' => 'February',
            'Марта' => 'March',
            'Апреля' => 'April',
            'Мая' => 'May',
            'Июня' => 'June',
            'Июля' => 'July',
            'Августа' => 'August',
            'Сентября' => 'September',
            'Октября' => 'October',
            'Ноября' => 'November',
            'Декабря' => 'December',
        ];
        
        $date = str_replace(array_keys($localizedMonths), array_values($localizedMonths), $dateString);
        
        return $date;
    }
    
}