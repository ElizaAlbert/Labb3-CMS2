<?php
/*
Plugin Name: Counter Plugin
Author: Eliza Albert
Description: A Plugin made for a school project that takes a string as a parameter and returns 'true' if the string is seven letters long.
*/

/** 2 Räkneplugin 
 * Detta är ett plugin som erbjuder en räknefunktion: "is_seven_letters_long"
 * Denna funktion tar en sträng som parameter och returnerar true om strängen är precis sju tecken lång.
*/

defined('ABSPATH') or die ("Nope.");

function is_seven_letters($str){
    if (strlen($str) === 7) {
        Echo "The string contains 7 letters!";
    } else {
        echo "The string contains more or less letters than 7.";
    }
}

is_seven_letters('Not seven letters.');