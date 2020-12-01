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
                echo "<h1 class='result'> The string contains 7 characters! </h1>";
            } else {
                echo "<h1 class='result'> The string contains more or less characters than 7. </h1>";

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
    margin: 5em;
}

</style>