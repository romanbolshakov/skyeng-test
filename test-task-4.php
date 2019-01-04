<?php
function sum($a='', $b=''){
    $a = str_split(strrev($a));
    $b = str_split(strrev($b));
    $adv = 0;
    $result = array_map(function($item1, $item2) use (&$adv){
        $digit = $item1 + $item2 + $adv;
        if ($digit >= 10){
            $adv = 1;
            $digit -= 10;
        } else { $adv = 0; } 
        return $digit;
    }, $a, $b);
    if (1 === $adv) $result[] = $adv;
    return implode('', array_reverse($result));
}