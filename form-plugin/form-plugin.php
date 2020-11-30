<?php
/*
Plugin Name: Form Plugin
Author: Eliza Albert
Description: A Contact Form Plugin made for a school project.
*/

/** 3 Kontaktformulär 
* Detta plugin skall kunna skriva ut ett kontaktformulär. Vid inskickat svar så sparas svaret i en lista som går att nå ifrån wp admin.
* (Ungefär som pluginet Contactform7 med Flamingo.)
* Tips: Formulär/ajax/register_posttype/insert_post/post_meta
* VG: Pluginet låter användaren skapa ett fritt antal egna formulär med olika slags fält, som nya custom-post-type-poster eller som ACF-options-fält. 
* Det framgår i i listningen av inskickade formulärsvar vilket formulär just detta är skickat ifrån och detta går att sortera på:
* Ex. Offertförfrågan vs.Skadeanmälan vs. Jobbansökningar. 
*/

/** Säkerhet
 * All data lämnat av användare skall behandlas som potentiellt skadlig, använd funktioner som validate, sanitize och escape för att hålla lurigt material borta ifrån server och klient
 * Se till att obehöriga ej har rätt att göra ändringar
*/


class ContactForm
{
  function contact()
  { 
      
      if(!is_admin()) {?>
    <div class="container">
    <!-- admin-ajax.php is located in wp-admin and is the point where data is received through forms -->
      <form action="<?php echo admin_url('admin-ajax.php'); ?>"> 
            <label for="name">Name: </label>
            <input type="text" name="name" id="name"><br><br>
            <label for="email">Email: </label>
            <input type="text" name="email" id="email"><br></br>
            <label for="message">Message: </label>
            <input type="textarea" name="textarea" id="textarea">
            <input type="submit" value="Send" id="button">

            <!-- When data is sent to admin-ajax, it looks for this WordPress special Get Parameter "action", takes the value and create a hook -->
            <input type="hidden" name="action" value="my_contactform"> 
      </form>
    </div>

      <?php }
  }

  // Method that inserts the data received through the contact form and its hook, to the wp_posts in the database and into the Custom Post Type "Messages". 
  function insertpost()
  {
    $post_id = wp_insert_post(
      [
        'post_title' => $_REQUEST['name'],
        'post_content' => $_REQUEST['textarea'],
        'post_type' => 'meddelanden',
        'post_status' => 'publish'
      ]
  
    );
    update_post_meta($post_id, 'email', $_REQUEST['email']); // Stores data received from the email input field in the wp_postmeta in the database.
    wp_redirect($_SERVER['HTTP_REFERER'] . '?sent=true'); // Changes URL address to a safe one. 
    die();
  }

  // Gets and display Custom Post Data.
  function get_CPT_data(){
        if(!is_admin()){
    $args = array( 
    'post_type'   => 'meddelanden',
    'meta_key'    => 'email'
  );
  $scheduled = new WP_Query( $args );

      //Lägg loop inside while
      while ( $scheduled->have_posts()) : $scheduled->the_post();
      $post_id = get_the_ID(); // Gets wp_postmeta IDs
      
      // SANITIZE EMAILS
      $email = get_post_meta($post_id, 'email', true); // Contains all values of the meta_key value "email" 
          if (isset($_REQUEST['sent'])) {
              if ( is_email($email) ) {
                echo 'Your message has been sent! <br></br>' ;
                echo '<br></br>';
                update_post_meta($email, 'email', sanitize_email( $email )); // Stores data received from the email input field in the wp_postmeta in the database.
            } else {
                echo "Please enter valid information.";
            };


      // SANITIZE MESSAGE FIELD
                $input_content = get_the_content();
          if (!empty($input_content)) {
              if (  sanitize_text_field( $input_content) ) {
                echo sanitize_text_field( $input_content);
                update_post_meta($input_content, 'post_content', sanitize_text_field( $input_content)); // Stores data received from the post_content input field in the wp_posts in the database.
              }
            }
          

          
      // SANITIZE NAME FIELD
              $input_title = get_the_title();
          if (isset($_REQUEST['sent'])) {
                $input_content = get_the_content();
              if (  sanitize_text_field( $input_content) ) {
                echo sanitize_text_field( $input_content);
                update_post_meta($input_content, 'post_content', sanitize_text_field( $input_content)); // Stores data received from the post_content input field in the wp_posts in the database.
              }
          }
        }
  endwhile;
      }
      wp_reset_postdata();
      }

  function contacttest(){
    $this->get_CPT_data();
    }

// Deactivates the plugin.
  function deactivate(){
    wp_delete_post($this->post_id);
  }

// Creating CPT Messages.
  function messages()
  {
    register_post_type('meddelanden', [
        'labels' => [
        'name' => 'Messages',
        'singular_name' => 'Message'
      ],
      'public' => true,
      'has_archive' => true
    ]);
    }

// Enqueues scripts and styles. 
    function enqueue(){ // Function that enqueues scripts and styles (gets activated by function registerEnqueue)
        wp_enqueue_style('style', plugin_dir_url(__FILE__) . "assets/style.css", array(), rand(111,9999), 'all');
    }
    function __construct()
  {
    add_action('init', [$this, 'messages']);
    add_action('woocommerce_after_main_content', [$this, 'contact']);
    add_action('wp_enqueue_scripts', array($this, 'enqueue'));
    add_action('wp_ajax_my_contactform', [$this, 'insertpost']); // Hooks action with method. Basically information received from my_contactform input field is proccessed through insertpost() method. 
    //activation
    register_activation_hook( __FILE__, [$this, 'activate']);
    //deactivation
    register_deactivation_hook( __FILE__, array($this, 'deactivate'));
    add_action('init', [$this, 'get_CPT_data']);
    // add_action('init', [$this, 'get_meta_data']);
  }
}
$contact = new ContactForm();
$contact->contact();
?>
