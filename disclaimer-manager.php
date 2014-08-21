<?php
/*
Plugin Name: Disclaimer Manager
Plugin URI: http://sitepoint.com
Description: Easy Disclaimer Manager for Multi-author blogs.
Version: 1.0
Author: Agbonghama Collins
Author URI: http://w3guy.com
License: GPL2
*/


// Add the admin options page
add_action( 'admin_menu', 'dm_settings_page' );

function dm_settings_page() {
	add_options_page( 'Disclaimer Manager', 'Disclaimer Manager', 'manage_options', 'disclaimer-manager', 'dm_options_page' );
}

// Draw the options page
function dm_options_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2> Disclaimer Manager for Authors </h2>

		<form action="options.php" method="post">
			<?php settings_fields( 'disclaimer_manager_options' ); ?>
			<?php do_settings_sections( 'disclaimer-manager' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}

// Register and define the settings
add_action( 'admin_init', 'dm_admin_init' );
function dm_admin_init() {
	register_setting( 'disclaimer_manager_options', 'disclaimer_manager_options',
		'' );

	add_settings_section( 'dm_main', 'Plugin Settings',
		'', 'disclaimer-manager' );

	add_settings_field( 'dm_textarea-id', 'Enter Disclaimer Text',
		'disclaimer_text_textarea', 'disclaimer-manager', 'dm_main' );

	add_settings_field( 'dm_select-id', 'Disclaimer Position',
		'disclaimer_text_position', 'disclaimer-manager', 'dm_main' );
}

// Display and fill the form field
function disclaimer_text_textarea() {
	// get option 'disclaimer_text' value from the database
	$options         = get_option( 'disclaimer_manager_options' );
	$disclaimer_text = $options['disclaimer_text'];

	// echo the field
	echo "<textarea rows='8' cols='50' id='disclaimer_text' name='disclaimer_manager_options[disclaimer_text]' >$disclaimer_text</textarea>";
}

function disclaimer_text_position() {
	// get option 'disclaimer_position' value from the database
	$options             = get_option( 'disclaimer_manager_options' );
	$disclaimer_position = $options['disclaimer_position'];

	echo '<select name="disclaimer_manager_options[disclaimer_position]">';
	echo '<option value="top"' . selected( $disclaimer_position, 'top' ) . '>Top</option>';
	echo '<option value="bottom"' . selected( $disclaimer_position, 'bottom' ) . '>Bottom</option>';
	echo '</select>';
}

function add_disclaimer_to_post( $content ) {

	$options = get_option( 'disclaimer_manager_options' );

	// get disclaimer text form DB
	$disclaimer_text = $options['disclaimer_text'];

	// get disclaimer position from DB
	$disclaimer_position = $options['disclaimer_position'];

	// ensure we are in a post and not a page
	if ( is_single() ) : {

		// if disclaimer position is set to top
		if ( $disclaimer_position == 'top' ) {
			$content = $disclaimer_text . $content;
		}

		// if disclaimer position is set to bottom
		if ( $disclaimer_position == 'bottom' ) {
			$content .= $disclaimer_text;
		}
	}

	endif;

	return $content;
}

add_filter( 'the_content', 'add_disclaimer_to_post' );

