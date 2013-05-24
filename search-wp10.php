<?php
/*
Plugin Name: Search for WP10
Plugin URI: http://wordpress.org
Description: Searching the meetups
Version: 1.0
Author: George Stephanis
Author URI: http://stephanis.info
License: WTFPL
*/

class Search_WP10 {
	static $instance;

	function __construct() {
		self::$instance = $this;

		add_filter( 'admin_init' , array( $this , 'admin_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );
		if( isset( $_REQUEST['search_wp10'] ) )
			add_action( 'init', array( $this, 'find_meetups' ) );
	}

	function admin_init() {
		register_setting( 'general', 'meetup_api_key', 'esc_attr' );
		add_settings_field( 'meetup_api_key', '<label for="meetup_api_key">Meetup API Key</label>' , array( $this, 'meetup_api_key_cb' ) , 'general' );
		register_setting( 'general', 'meetup_container_id', 'esc_attr' );
		add_settings_field( 'meetup_container_id', '<label for="meetup_container_id">Meetup Container ID</label>' , array( $this, 'meetup_container_id_cb' ) , 'general' );
	}

	function meetup_api_key_cb() {
		$value = get_option( 'meetup_api_key' );
		echo '<input type="text" id="meetup_api_key" name="meetup_api_key" value="' . esc_attr( $value ) . '" />';
	}

	function meetup_container_id_cb() {
		$value = get_option( 'meetup_container_id' );
		echo '<input type="text" id="meetup_container_id" name="meetup_container_id" value="' . esc_attr( $value ) . '" />';
	}

	function wp_enqueue_scripts() {
		wp_enqueue_script( 'search-wp10', plugins_url( 'search-wp10.js', __FILE__ ), array( 'jquery' ) );
	}

	function wp_head() {
		?>
		<script>var wp10_ajax_url = <?php echo json_encode( site_url() ); ?>;</script>
		<?php
	}

	function find_meetups() {
		require_once( dirname(__FILE__) . '/meetup.php' );
		$meetup = new Meetup( array(
			'key' => get_option( 'meetup_api_key' ),
		) );

		$params = array(
			'container_id' => intval( get_option( 'meetup_container_id' ) ),
			'lat' => floatval( $_REQUEST['latitude'] ),
			'lon' => floatval( $_REQUEST['longitude'] ),
		);

		wp_die( json_encode( $meetup->get( '/ew/events', $params ) ) );
	}

}

new Search_WP10;
