<?php
/**
 * Plugin Name: Test-counter-plugin/addon
 * Description: Test-counter-plugin innehåller en testfunktion för att testa räknefunktionen "is_seven_letters_long".
 * Version: 1.0
 * Author: Eliza Albert
 **/

defined('ABSPATH') or die ("Nope.");

/** 4 Testplugin/addon 
 * Test-pluginet innehåller en testfunktion för att testa räknefunktionen "is_seven_letters_long" ifrån uppgift 2.
 * Denna funktion tar emot två parametrar: $strängen_som_ska_testas och (bool) $förväntat_returvärde
 * Om räknefunktionen returnerar det förväntade värdet så skriver testfunktionen ut: "Lyckat test"
 * Om räknefunktionen inte returnerar det förväntade värdet så skriver testfunktionen ut: "Test misslyckades", gärna i rött.
 * I bägge fall skriver testfunktionen ut teststrängen, förväntade värdet samt räknefunktionens returnerade värde.
 * 
 * Testpluginet innehåller också tre hårdkodade strängar på respektive 6, 7 och 9 tecken.
 * Dessa skall alla testas med varsitt anrop till testfunktionen.
 * Gör dessa anrop så att två stycken genererer "Lyckat test" och den sista genererer "Misslyckat test"
 * 
 * Anropen till testfunktionen körs endast om url-parametern "testrun" är satt till "yes"
 * 
*/

class TestCounter
{
  function testfunction($strangen_som_ska_testas, $forvantat_returvarde)
  {
    $testcount = new countPlugin(); //Creates new instance of Count-plugin.
    $works = $testcount->is_seven_letters_long($strangen_som_ska_testas); // Runs the Count-Plugin instance with $strangen_som_ska_testas as a parameter.

    $isworking_string_format = $works ? 'True' : 'False'; // Shorthand If statement that checks if $works is true or false.
    $forvantat_returvarde = $isworking_string_format;

    echo "<h2> The string that is being tested is: $strangen_som_ska_testas, the correct answer is: $isworking_string_format, therefore: </h2>";

    if ($forvantat_returvarde === "True") { // If the string returned is true, test succeeded, if not, it failed.
      echo '<h3 style="color: green;"> Test succeeded gracefully!</h3>';
    } else {
      echo '<h3 style="color: red;"> Test failed miserably... </h3>';
    }
  }

  function test_run()
  {
    $six = 'abcdef';
    $seven = 'abcdefg';
    $nine = 'abcdefg!!';
    $this->testfunction($six, false);
    $this->testfunction($seven, true);
    $this->testfunction($nine, true); // Returns a fail.
  }

  public function __construct()
  {
    add_action('wp_head', [$this, 'test_run']);
  }
}

if (isset($_GET['testrun']) && $_GET['testrun'] === 'yes') { // Testing only works if the right URL parameters are set.
  $test = new TestCounter();
  $test->test_run();
}