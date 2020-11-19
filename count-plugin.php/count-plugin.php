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

// defined('ABSPATH') or die ("Nope.");

// function is_seven_letters($str){
//     if (strlen($str) === 7) {
//         Echo "The string contains 7 letters!";
//     } else {
//         echo "The string contains more o.";
//     }
// }

// is_seven_letters('okrtysd');


defined('ABSPATH') or die ("Nope.");


class countPlugin {

        function activate() {
            echo "The plugin was activated.";
    }
        function deactivate() {
            echo "The plugin was deactivated.";
    }
        function uninstall() {
        
    }
        function checks_string_letters($str){
            if (strlen($str) === 7) {
                echo "The string contains 7 characters!";
            } else {
                echo "The string contains more or less characters than 7.";
            }
}
}

//Checks for the class 'countPlugin', then creates a new plugin instance and doesn't display content on Dashboard.
if (class_exists('countPlugin')) { 
        $newCountPlugin = new countPlugin();
        
//activation
register_activation_hook( __FILE__, array($newCountPlugin, 'activate'));

//deactivation
register_deactivation_hook( __FILE__, array($newCountPlugin, 'deactivate'));

//uninstall

}
    if(!is_admin()) { // !is_admin makes content not display on Dashboard by returning false while on Dashboard
            $newCountPlugin->checks_string_letters('1234567'); // Enqueues styles and scripts (style.css and script.js)
    }
;
