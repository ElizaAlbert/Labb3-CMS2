<?php
/*
Plugin Name: Weather Plugin
Author: Eliza Albert
Description: A Plugin made for a school project that displays weather data.
*/

/** 1 Väderdata / Instructions
 * Ett plugin som hämtar väderdata för vald plats, som presenteras med lämpliga bilder och färger.
 * Detta plugin har en inställningssida med minst fem olika alternativ för var på sidan det ska placeras:
 * Produktsida, shop-sida, cart, checkout eller på en specifik produkt.
 * Se till att cachea den hämtade datan så att inte api:et tillfrågas mer än ca en gång per timme, oavsett hur många sidträffar som sker.
 * Tips: acf_options/api/remote_get/krokar/transienter
*/ 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class weatherPlugin {

    function __construct($arg)
    {
        echo "<h1 class='testArg'>" . $arg . "</h1>";
    }

        function activate() {
            echo "The plugin was activated.";
    }
        function deactivate() {
            echo "The plugin was deactivated.";
    }
        function uninstall() {
        
    }
    function registerEnqueue(){ // Function that register the action of enqueueing scripts and styles (activates function enqueue)
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
    }
    function enqueue(){ // Function that enqueues scripts and styles (gets activated by function registerEnqueue)
        wp_enqueue_style('style', plugin_dir_url(__FILE__) . "assets/style.css");
        wp_enqueue_script('script', plugin_dir_url(__FILE__) . "assets/script.js");
    }
     function weather($weather_data)
     {
         echo "<p>Temperatur: {$weather_data['data']['instant']['details']['air_temperature']} </p>";
         echo "<p>Vindhastighet: {$weather_data['data']['instant']['details']['wind_speed']} </p>";
         echo "<p>Next 1 Hour: {$weather_data['data']['next_1_hours']['summary']['symbol_code']} </p>";
         echo "<p>Next 12 Hour: {$weather_data['data']['next_12_hours']['summary']['symbol_code']} </p>";
         echo "<p>Precipitation Amount: {$weather_data['data']['next_1_hours']['details']['precipitation_amount']} </p>";

            // Switch statement that controls weather symbols (rainy weather: rainy icon, cloudy weather: cloudy icon etc...)
             switch ($weather_data['data']['next_12_hours']['summary']['symbol_code']) {
                 case 'cloudy':
                        echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/cloud.png width="100" height="auto" >';
                     break;
                 case 'lightrain':
                        echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/lightrain.png width="100" height="auto" >';
                     break;
                case 'rain':
                        echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/heavyrain.png width="100" height="auto" >';
                    break;
                case 'lightrainshowers_day':
                        echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/lightrainshowers_day.png width="100" height="auto" >';
                    break;
                case 'rainshowers_night':
                        echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/rainshowers_night.png width="100" height="auto" >';
                    break;
                case 'partlycloudy_night':
                    echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/partlycloudy_night.png width="100" height="auto" >';
                    break;
                case 'partlycloudy_day':
                    echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/partlycloudy_day.png width="100" height="auto" >';
                    break;
                case 'fair_night':
                    echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/fair_night.png width="100" height="auto" >';
                    break;
                case 'clearsky_night':
                    echo "<img src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/clearsky_night.png width="100" height="auto" >';
                    break;
                 default:
                     # code...
                     break;
             }
                  echo "<p>Symbol translation: {$weather_data['data']['next_12_hours']['summary']['symbol_code']} </p>";
     }
     function get_weather(){ // Gets all data from first 'timeseries' array. 
        $response = wp_remote_get('https://api.met.no/weatherapi/locationforecast/2.0/compact?lat=57.7089&lon=11.9746');
        $body = wp_remote_retrieve_body($response); // Makes the weather data from array to string.
        $formated_body_array = json_decode($body, true);  // json_decode takes a JSON encoded string and converts it into a PHP variable. 
        return $formated_body_array['properties']['timeseries'][0]; // Returns data of first 'timeseries'.
     }
     function run_weather() { // Displays weather data received through get_weather(), caches the data, sets a time limit and checks if data is in database and if not it gets the data from the API 

             $database_data = get_transient('weather_data'); // Gets cached data (transient means staying only a short time)

             if ($database_data) {
             $wdata = get_transient('weather_data');
             } else {
             $wdata = $this->get_weather();
             set_transient('weather_data', $wdata, 3600); // Stores cached data, sets time limit
              }
         $this->weather($wdata);
     }
     }

add_action('wp_footer', 'run_weather');

if (class_exists('weatherPlugin')) { 
    if(!is_admin()) { // !is_admin makes content not display on Dashboard
         $pluginInstance = new weatherPlugin('Weather information:'); // Echos $args 
         $pluginInstance->registerEnqueue(); // Enqueues styles and scripts (style.css and script.js)
         $pluginInstance->run_weather();

//activation
register_activation_hook( __FILE__, array($pluginInstance, 'activate'));

//deactivation
register_deactivation_hook( __FILE__, array($pluginInstance, 'deactivate'));

//uninstall
    }
};



