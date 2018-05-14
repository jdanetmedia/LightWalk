<?php
/*
Plugin Name:  Lightwalk
Plugin URI:   http://jdanet.dk/lightwalk
Description:  Simple plugin that removes desired funcitonality from the Wordpress dahsboard.
Version:      1.0.0
Author:       http://jdanet.dk
Author URI:   http://jdanet.dk
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  lightwalk
*/

defined( 'ABSPATH' ) or die( 'Hey! There is nothing going on here if you are human' );

class LightWalk {
	function __construct() {
		add_action("admin_menu", array($this, "menu_item"));
		add_action("admin_init", array($this, "lightwalk_settings_page"));
		add_action("admin_menu", array($this, "remove_menus"));
		add_action("wp_dashboard_setup", array($this, "newDashboard"));
		add_action('wp_dashboard_setup', array($this, "remove_dashboard_widgets"));
	}
	
	// Add settingspage content and funtionality
	function lightwalk_settings_page() {
	    // Add settings sections in settings page (Only one section in this case)
	    add_settings_section(
	    	"menuhide",
	    	"Hide from menu",
	    	null,
	    	"lightwalk"
	    );
	    // Register setting
	    register_setting(
	    	// Section
	    	"menuhide",
	    	// Name
	    	"user-role",
	    	// Sanitation callback
	    	array($this, "textSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-dashboard",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-posts",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-media",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-pages",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-comments",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-appearance",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-plugins",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-users",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-tools",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"hide-settings",
	    	array($this, "numberSanitation")
	    );
	    
	    register_setting(
	    	"menuhide",
	    	"widget-content",
	    	array($this, "textSanitation")
	    );
	    // Add form field to settings page
	    add_settings_field(
	    	// Field ID
	    	"user-role",
	    	// Field label
	    	"Select user role to effect",
	    	// Callback function, to define markup to be displayed
	    	array($this, "select_userrole"),
	    	// The settings page. Ex: /wp-admin/options-general.php?page=lightwalk
	    	"lightwalk",
	    	// Section the field belongs to
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-dashboard",
	    	"Hide dashboard",
	    	array($this, "hide_dashboard"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-posts",
	    	"Hide posts",
	    	array($this, "hide_posts"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-media",
	    	"Hide media",
	    	array($this, "hide_media"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-pages",
	    	"Hide pages",
	    	array($this, "hide_pages"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-comments",
	    	"Hide comments",
	    	array($this, "hide_comments"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-appearance",
	    	"Hide appearance",
	    	array($this, "hide_appearance"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-plugins",
	    	"Hide plugins",
	    	array($this, "hide_plugins"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-users",
	    	"Hide users",
	    	array($this, "hide_users"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-tools",
	    	"Hide tools",
	    	array($this, "hide_tools"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"hide-settings",
	    	"Hide settings",
	    	array($this, "hide_settings"),
	    	"lightwalk",
	    	"menuhide"
	    );
	    
	    add_settings_field(
	    	"widget-content",
	    	"Dashboard widget content",
	    	array($this, "widget_content"),
	    	"lightwalk",
	    	"menuhide"
	    );
	}
	
	
	// Callback function for role select input
	function select_userrole() {
		?>
	        <select name="user-role">
	          <option value="administrator" <?php selected(get_option('user-role'), "administrator"); ?>>Administrator</option>
	          <option value="editor" <?php selected(get_option('user-role'), "editor"); ?>>Editor</option>
	          <option value="author" <?php selected(get_option('user-role'), "author"); ?>>Author</option>
	          <option value="contributer" <?php selected(get_option('user-role'), "contributer"); ?>>Contributer</option>
	          <option value="subscriber" <?php selected(get_option('user-role'), "subscriber"); ?>>Subscriber</option>
	        </select>
	   <?php
	}
	
	// Callback function for checkbox that hides the dashboard
	function hide_dashboard() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-dashboard" value="1" <?php checked(1, get_option('hide-dashboard'), true); ?> /> 
	   <?php
	}
	
	function hide_posts() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-posts" value="1" <?php checked(1, get_option('hide-posts'), true); ?> /> 
	   <?php
	}
	
	function hide_pages() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-pages" value="1" <?php checked(1, get_option('hide-pages'), true); ?> /> 
	   <?php
	}
	
	function hide_media() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-media" value="1" <?php checked(1, get_option('hide-media'), true); ?> /> 
	   <?php
	}
	
	function hide_comments() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-comments" value="1" <?php checked(1, get_option('hide-comments'), true); ?> /> 
	   <?php
	}
	
	function hide_appearance() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-appearance" value="1" <?php checked(1, get_option('hide-appearance'), true); ?> /> 
	   <?php
	}
	
	function hide_plugins() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-plugins" value="1" <?php checked(1, get_option('hide-plugins'), true); ?> /> 
	   <?php
	}
	
	function hide_users() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-users" value="1" <?php checked(1, get_option('hide-users'), true); ?> /> 
	   <?php
	}
	
	function hide_tools() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-tools" value="1" <?php checked(1, get_option('hide-tools'), true); ?> /> 
	   <?php
	}
	
	function hide_settings() {
		?>
	        <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
	        <input type="checkbox" name="hide-settings" value="1" <?php checked(1, get_option('hide-settings'), true); ?> /> 
	   <?php
	}
	
	function widget_content() {
		?>
			<textarea name="widget-content" cols="100" rows="2"><?php echo get_option('widget-content'); ?></textarea>
		<?php
	}
	
	// Sanitation callback for checkboxes
	function numberSanitation($option) {
	  //sanitize
	  if($option == 1 || $option == "") {
		  $option = htmlspecialchars($option);
		  return $option;
	  } else {
		  die("Input type not allowed!");
	  }
	}
	
	// Sanitation callback for textinput
	function textSanitation($option) {
	  //sanitize
	  $option = sanitize_text_field($option);
	  $option = htmlspecialchars($option);
	  
	  return $option;
	}
	
	// Markup for the settingspage. Callback for 'menu-item' method.
	function settings_page() {
	  ?>
	      <div class="wrap">
	         <h1>LightWalk Settings</h1>
	  
			 <!-- options.php is a Wordpress file that does most logic -->
	         <form method="post" action="options.php">
	            <?php 
	            	// Inset section, fields and save button
	               do_settings_sections("lightwalk");
	               settings_fields("menuhide");
	                 
	               submit_button(); 
	            ?>
	         </form>
	      </div>
	   <?php
	}
	
	// Adds the settingspage as subpage to general options.
	function menu_item() {
	  add_submenu_page("options-general.php", "LightWalk Settings", "LightWalk", "manage_options", "lightwalk", array($this, "settings_page")); 
	}
	
	// Method for changin the dashboard, relative to the selected options.
	function remove_menus(){
		// Check what user role to effect
		$user_role = get_option("user-role");
		
		// Get the role of the logged in user
		$user = wp_get_current_user();
		
		// Check if the userroles above match
		if ( in_array( $user_role, (array) $user->roles ) ) {
			    // If option checkbok is checked - hide dashboard menu item
			    if(get_option("hide-dashboard") == 1) {
					remove_menu_page("index.php");
				}
				// Posts
				if(get_option("hide-posts") == 1) {
					remove_menu_page("edit.php");
				}
				// Pages
				if(get_option("hide-pages") == 1) {
					remove_menu_page("edit.php?post_type=page");
				}
				// Media	
				if(get_option("hide-media") == 1) {
					remove_menu_page("upload.php");
				}
				// Comments	
				if(get_option("hide-comments") == 1) {
					remove_menu_page("edit-comments.php");
				}
				// Appearance
				if(get_option("hide-appearance") == 1) {
					remove_menu_page("themes.php");
				}
				// Plugins
				if(get_option("hide-plugins") == 1) {
					remove_menu_page("plugins.php");
				}
				// Users
				if(get_option("hide-users") == 1) {
					remove_menu_page("users.php");
				}
				// Tools
				if(get_option("hide-tools") == 1) {
					remove_menu_page("tools.php");
				}
				// Settings
				if(get_option("hide-settings") == 1) {
					remove_menu_page("options-general.php");
				}
			}
	    
	}
	
	// Method to add widget to the dashboard
	function newDashboard() {
		
		wp_add_dashboard_widget(
			// Widget ID
			'welcome_to_wordress',
			// Widget Title
			'Welcome to your wordpress site',
			// Callback function getting what to display
			array($this, "dashboardWidget")
		);
	}
	

	function dashboardWidget() {
		// Gets the text entered in the dashboard textarea
		$widgetText = get_option("widget-content");
		echo $widgetText;
	}
	
	// Remove the standard dashboard widgets
	function remove_dashboard_widgets() {
	    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_action('welcome_panel', 'wp_welcome_panel');
	}
}

// Instansiate the class
$lightwalk = new LightWalk();