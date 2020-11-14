<?php
/*
Plugin Name: Weather Plugin
Author: Eliza Albert
Description: A Plugin made for a school project that displays weather data.
*/

/** 1 Väderdata 
 * Ett plugin som hämtar väderdata för vald plats, som presenteras med lämpliga bilder och färger.
 * Detta plugin har en inställningssida med minst fem olika alternativ för var på sidan det ska placeras:
 * Produktsida, shop-sida, cart, checkout eller på en specifik produkt.
 * Se till att cachea den hämtade datan så att inte api:et tillfrågas mer än ca en gång per timme, oavsett hur många sidträffar som sker.
 * Tips: acf_options/api/remote_get/krokar/transienter
*/ 

//Echo something out PHP OOP style
class weatherPlugin {

    function __construct($arg)
    {
        echo "<br>" . $arg;
    }
    function registerEnqueue(){
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
    }
    function enqueue(){
        wp_enqueue_style('style', plugin_dir_url(__FILE__) . "assets/style.css");
        wp_enqueue_script('script', plugin_dir_url(__FILE__) . "assets/script.js");
    }
}
 
if (class_exists('weatherPlugin')) {
    if(!is_admin()) { // !is_admin makes content not display on Dashboard
    $pluginInstance = new weatherPlugin('en längre mening utan mening'); // Echos $args 
    $pluginInstance->registerEnqueue(); // Enqueues styles and scripts (style.css and script.js)
    }
};


