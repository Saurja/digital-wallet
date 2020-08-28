<?php

function switchquartets($n){
    return ((0x0000000F & $n) << 4) + ((0x000000F0& $n)>>4)
        + ((0x00000F00 & $n) << 4) + ((0x0000F000& $n)>>4)
        + ((0x000F0000 & $n) << 4) + ((0x00F00000& $n)>>4)
        + ((0x0F000000 & $n) << 4) + ((0xF0000000& $n)>>4);
}

$en = 51;
$en2 = 1011;

$en = switchquartets($en);
$en2 = switchquartets($en2);
var_dump($en);
var_dump($en2);
$en = switchquartets($en);
$en2 = switchquartets($en2);
var_dump($en);
var_dump($en2);
$en = $en + $en2;
var_dump($en);
var_dump(switchquartets($en));

?>