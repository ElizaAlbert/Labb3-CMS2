<?php
/*
Plugin Name: Counter Plugin
Author: Eliza Albert
Description: A Plugin made for a school project that takes a string as a parameter and echos specific strings based on if the string parameter is seven characters long.
*/


defined('ABSPATH') or die ("Nope.");

// countPlugin is a class that checks a string for its length and returns another string based on if the length is seven characters or not.
class countPlugin {
        function is_seven_letters_long($str){
            if (strlen($str) === 7) {
                return true;
            } else {
                return false;

            }
    }
    function __construct()
  {
    add_action('wp_head', [$this, 'is_seven_letters_long']);
  }
}

    //Checks for the class 'countPlugin', then creates a new plugin instance and doesn't display content on Dashboard.
    if (class_exists('countPlugin')) { 
            $newCountPlugin = new countPlugin();
    }
;

?>
<style>

.result{
    margin: 1em;
}

</style>