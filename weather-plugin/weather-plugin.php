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

// Same as defined('ABSPATH') or die ("Nope."); , it kills the plugin if it's accessed by someone or something that doesn't have permission by WordPress
if(!defined('ABSPATH')){
    die;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class weatherPlugin {

        function activate() {
            echo "The plugin was activated.";
                // update_option('Weather', 'Weather Plugin');
    }
        function deactivate() {
            echo "The plugin was deactivated.";
                // delete_option('Weather');
    }
        function uninstall() {
            echo "The plugin was uninstalled.";
    }
        function register_weather_plugin_settingspage(){
            add_action('acf/init', array($this, 'weather_plugin_settingspage'));
    }
        function weather_plugin_settingspage(){
            // acf_add_options_page('Weather');
            // Check function exists.
        if( function_exists('acf_add_options_page') ) {

        // Register options page.
        $option_page = acf_add_options_page(array(
            'page_title'    => __('Weather Settings'),
            'menu_title'    => __('Weather'),
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'icon_url'      => 'dashicons-smiley',
            'redirect'      => false
        ));
    }
    }
    function enqueue(){ // Function that enqueues scripts and styles (gets activated by function registerEnqueue)
        wp_enqueue_style('style', plugin_dir_url(__FILE__) . "assets/style.css");
        wp_enqueue_script('script', plugin_dir_url(__FILE__) . "assets/script.js");
    }
    function registerEnqueue(){ // Function that register the action of enqueueing scripts and styles (activates function enqueue)
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
    }

    // Displaying weather data
     function weather($weather_data)
    {
        echo "<section class='weather-plugin-area'>";
        echo "<h1> Weather information in Gothenburg: </h1>";
        echo "<p>Temperatur: {$weather_data['data']['instant']['details']['air_temperature']} </p>";
        echo "<p>Vindhastighet: {$weather_data['data']['instant']['details']['wind_speed']} </p>";
        echo "<p>Precipitation Amount: {$weather_data['data']['next_1_hours']['details']['precipitation_amount']} </p>";
        echo "Weather in the next 1 hour: <br>";

            // Switch statement that controls weather symbols (rainy weather: rainy icon, cloudy weather: cloudy icon etc...)
             switch ($weather_data['data']['next_1_hours']['summary']['symbol_code']) {
                 case 'cloudy':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/cloud.png width="100" height="auto" >';
                     break;
                 case 'lightrain':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/lightrain.png width="100" height="auto" >';
                     break;
                case 'rain':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/heavyrain.png width="100" height="auto" >';
                    break;
                case 'lightrainshowers_day':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/lightrainshowers_day.png width="100" height="auto" >';
                    break;
                case 'rainshowers_night':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/rainshowers_night.png width="100" height="auto" >';
                    break;
                case 'partlycloudy_night':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/partlycloudy_night.png width="100" height="auto" >';
                    break;
                case 'partlycloudy_day':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/partlycloudy_day.png width="100" height="auto" >';
                    break;
                case 'fair_night':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/fair_night.png width="100" height="auto" >';
                    break;
                case 'clearsky_night':
                    echo "<img style='margin: 2em'; src=" . plugin_dir_url( __DIR__ ). 'weather-plugin/assets/weather-icons/clearsky_night.png width="100" height="auto" >';
                    break;
                 default:
                    echo "Oops, couldn't obtain weather symbols.";
                     break;
             }
                  echo "<p>Symbol translation: {$weather_data['data']['next_1_hours']['summary']['symbol_code']} </p>";               
     }
    
   // PLACEMENT OF WEATHER DATA ON WEBSITE  
  function display_weather($pluginInstance){   // Method that connects data to specific places based on what radiobutton is checked in options page "Weather" in WordPress.

    $placement_weather_data = get_field('weather_data_location', 'option'); // Returns the value of the option that is checked in the radiobox of the Weather Plugin Settings Page 
        
        if ($placement_weather_data === 'Shop') {
            add_action('woocommerce_after_shop_loop_item', [$this, 'run_weather'], 50);
        }
        if ($placement_weather_data === 'Cart') {
            add_action('woocommerce_before_cart_contents', [$this, 'run_weather']);
        }
        if ($placement_weather_data === 'Checkout') {
            add_action('woocommerce_before_checkout_billing_form', [$this, 'run_weather']);
        }
        if ($placement_weather_data === 'Product') {
              add_action('woocommerce_product_thumbnails', [$this, 'run_weather']);
        }
        if ($placement_weather_data === 'Account') {
              add_action('woocommerce_before_account_navigation', [$this, 'run_weather']);
        }
    }
  function register_weather_location(){ // Method that actually displays the weather data based on which if statement is true in the display_weather() method.
            add_action('wp_head', array($this, 'display_weather'));
    }


  // ACQUIRING WEATHER DATA
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
            
//Checks for the class 'weatherPlugin', then creates a new plugin instance and doesn't display content on Dashboard.
if (class_exists('weatherPlugin')) { 
        
        $pluginInstance = new weatherPlugin();
        
//activation
register_activation_hook( __FILE__, array($pluginInstance, 'activate'));

//deactivation
register_deactivation_hook( __FILE__, array($pluginInstance, 'deactivate'));

//uninstall
// register_uninstall_hook( __FILE__, array($pluginInstance, 'uninstall'));
}
    if(!is_admin()) { // !is_admin makes content not display on Dashboard by returning false while on Dashboard
            $pluginInstance->registerEnqueue(); // Enqueues styles and scripts (style.css and script.js)
            $pluginInstance->register_weather_location();
        } elseif(is_admin()){
                $pluginInstance->register_weather_plugin_settingspage(); // add_action hook 
                $pluginInstance->weather_plugin_settingspage(); // Creates a new settingspage (smiley)
     }
    