<?php
/**
 * Plugin Name: Aaron's Joke Site Plugin
 * Description: 
 * Version: 1.0.0
 * Author: Aaron Olkin
 * License: MIT
 * 
 * Copyright (C) 2014, Aaron Olkin
 * 
 */

function init_ao_joke_pages() {
  $menu_hookname = add_object_page( 'Joke Site', 'Joke Site', 'manage_options', 'joke-management-settings', 'joke_management_settings');
}

wp_enqueue_script( 'ao-joke', plugins_url( 'joke.js', __FILE__ ), array("jquery") );

if (is_admin()) {
  add_action( 'admin_menu', 'init_ao_joke_pages');

  function validate_current_joke($val) {
    return ($val ? true : false);
  }

  function validate_joke_data($val) {
    
  }

  function joke_management_settings()
  {
    ?>
    <div class="section panel">
      <h1>Joke Management &ndash; Settings</h1>
      <form method="post" enctype="multipart/form-data" action="options.php">
	<?php 
	   settings_fields('joke-management-settings');
	   
	   do_settings_sections('joke-management-settings');
	   ?>
	<p class="submit">  
	  <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
	</p>  
	
      </form>
    </div>
    <?php
   }

function joke_management_settings_display($args)
{
  extract( $args );

  $option_name = $name;

  $options = get_option( $option_name );
  $value = esc_attr( $options[$id] );

  switch ( $type ) {
  case 'text':
    echo "<textarea class='regular-text$class' id='$id' name='" . $option_name . "[$id]' style='min-width: 50em; min-height: 20em; font-family: monospace;'>$value</textarea>";  
    echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
    break;  
  case 'bool':
    $checked = $options[$id] ? "checked" : "";
    echo "<input class='regular-text$class' type='checkbox' id='$id' name='" . $option_name . "[$id]' $checked />";  
    echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
    break;
  }
}

add_action("admin_init", "joke_management_settings_init");

function joke_management_settings_init() {
  register_setting( "joke-management-settings", "joke-json" );
  register_setting( "joke-management-settings", "joke-enabled", "validate_current_joke" );

  add_settings_section( 'joke-defaults', 'Joke Site Settings', 'joke_management_settings_sdisplay', 'joke-management-settings' );

  $field_args = array(
		      'type'      => 'text',
		      'id'        => 'joke-json',
		      'name'      => 'joke-json',
		      'desc'      => 'JSON Replacement Data, see Readme for example',
		      'std'       => '',
		      'label_for' => 'joke-json',
		      'class'     => 'css_class'
		      );
  add_settings_field( 'joke-json', 'Joke JSON', 'joke_management_settings_display', 'joke-management-settings', 'joke-defaults', $field_args );
  $field_args = array(
		      'type'      => 'bool',
		      'id'        => 'joke-enabled',
		      'name'      => 'joke-enabled',
		      'desc'      => 'Whether to show jokes',
		      'std'       => '',
		      'label_for' => 'joke-enabled',
		      'class'     => 'css_class'
		      );
  add_settings_field( 'joke-enabled', 'Jokes Enabled', 'joke_management_settings_display', 'joke-management-settings', 'joke-defaults', $field_args );
}

}

function get_joke_data() {
  header('Content-Type: application/json');
  if (get_option("joke-enabled")) {
    echo get_option("joke-json")["joke-json"];
  }
  else {
    echo "null";
  }
  exit();
}

add_action("wp_ajax_nopriv_jokedata", "get_joke_data");
add_action("wp_ajax_jokedata", "get_joke_data");