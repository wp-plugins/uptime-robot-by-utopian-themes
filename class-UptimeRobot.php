<?php

/**
 * Uptime Robot class.
 *
 * @class		UptimeRobot
 * @version		1.0.0
 * @package		Uptime Robot
 * @author 		Brian Welch
 */

if ( ! class_exists( 'UptimeRobot' ) ) {

	class UptimeRobot {

		private $options;
		private $v = '1.0.0';

		public function __construct() {

 			add_action( 'admin_enqueue_scripts', array( $this, 'uptimerobot_css' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'uptimerobot_add_dashboard_widget' ) );
			add_action( 'admin_menu', array( $this, 'uptime_robot_menu' ) );
			add_action( 'admin_init', array( $this, 'uptime_robot_page_init' ) );
			add_action( 'plugins_loaded',array( $this, 'uptime_robot_load_textdomain' ) );
 		}

		/**
		 * Load plugin textdomain.
		 * @since 1.0.0
		 * @return [type] [description]
		 */
		function uptime_robot_load_textdomain() {
			load_plugin_textdomain(
				'uptime-robot',
				false,
				dirname( plugin_basename( __FILE__ ) ) . '/lang/'
			);
		}

 		/**
 		 * Add CSS to the WordPress Dashboard
 		 * @since  1.0.0
 		 * @return [type] [description]
 		 */
		function uptimerobot_css() {
		    wp_register_style(
				'uptimerobot-css',
				plugins_url() . '/uptime-robot/css/style.css',
				null,
				time(),
				'all'
		    );
		    wp_enqueue_style( 'uptimerobot-css' );
		}

		/**
		 * Add WordPress Dashboard Settings Menu
		 * @since  1.0.0
		 * @return [type] [description]
		 */
		function uptime_robot_menu() {
	        add_options_page(
	            'Settings Admin',
	            'Uptime Robot',
	            'manage_options',
	            'uptime-robot',
	            array( $this, 'create_uptime_robot_settings_page' )
	        );
	    }

	    /**
	     * Create WordPress Dashboard Settings Page
	     * @since  1.0.0
	     * @return [type] [description]
	     */
		function create_uptime_robot_settings_page() {
	        $this->options = get_option( 'uptime_robot_option_name' );
	        ?>
	        <div class="wrap">
	            <?php screen_icon(); ?>
	            <h2><?php _e( 'Uptime Robot','uptime-robot' );?></h2>
	            <form method="post" action="options.php">
	            <?php
	                settings_fields( 'uptime_robot_option_group' );
	                do_settings_sections( 'uptimerobot-setting-admin' );
	                submit_button( 'Save Key' );
	            ?>
	            </form>
	        </div>
	        <?php
	    }

	    /**
	     * Add Content to WordPress Dashboard Settings Page
	     * @since  1.0.0
	     * @return [type] [description]
	     */
		function uptime_robot_page_init() {
	        register_setting(
	            'uptime_robot_option_group',
	            'uptime_robot_option_name',
	            array( $this, 'sanitize' )
	        );

	        add_settings_section(
	            'setting_section_id',
	            ' ',
	            array( $this, 'uptime_robot_section_info' ),
	            'uptimerobot-setting-admin'
	        );

	        add_settings_field(
	            'api_key',
	            'API Key',
	            array( $this, 'api_key_callback' ),
	            'uptimerobot-setting-admin',
	            'setting_section_id'
	        );

	    }

	    /**
	     * Sanitize API Key Input
	     * @since  1.0.0
	     * @param  [type] $input [description]
	     * @return [type]        [description]
	     */
		function sanitize( $input ) {

			$new_input = array();
			if ( isset ( $input['api_key'] ) )
		            $new_input['api_key'] = $input['api_key'];
			return $new_input;
	    }

	    /**
	     * Settings Description
	     * @since  1.0.0
	     * @return [type] [description]
	     */
		function uptime_robot_section_info() {
	        print 'Enter your Uptime Robot API key.  If you do not have an API key, you will need to visit <a href="https://uptimerobot.com/dashboard#mySettings" target="_blank">Uptime Robot</a> to acquire one.';
	    }

	    /**
	     * API Key Callback
	     * @since  1.0.0
	     * @return [type] [description]
	     */
		function api_key_callback() {
	        printf(
	            '<input type="text" id="api_key" name="uptime_robot_option_name[api_key]" value="%s" />',
			isset ( $this->options['api_key'] ) ? esc_attr( $this->options['api_key'] ) : ''
	        );
	    }

		/**
		 * Get the uptime data in JSON using CURL
		 * @since  1.0.0
		 * @return [type] [description]
		 */
		public function get_uptime_data() {
			$api_key = get_option( 'uptime_robot_option_name' );

			$url = 'http://api.uptimerobot.com/getMonitors?apiKey=' . $api_key['api_key'] . '&logs=1&showTimezone=1&format=json&noJsonCallback=1';
			$c = curl_init( $url );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			$responseJSON = curl_exec( $c );
			curl_close( $c );
			$xml = json_decode( $responseJSON );

			return $xml;
		}

		/**
		 * Return a value based on the monitors status.
		 * @since  1.0.0
		 * @param  [type] $status [description]
		 * @return [type]         [description]
		 */
		function status_type( $status ) {
			switch ( $status ) {
				case 0:
					$stat = __( 'Paused' , 'uptimerobot' );
					break;
				case 1:
					$stat = __( 'Unchecked', 'uptimerobot' );
					break;
				case 2:
					$stat = __( 'Up', 'uptimerobot' );
					break;
				case 8:
					$stat = __( 'Appears Down', 'uptimerobot' );
					break;
				case 9:
					$stat = __( 'Down', 'uptimerobot' );
					break;
				default:
					$stat = __( 'Unknown', 'uptimerobot' );
			}
			return $stat;
		}

		/**
		 * Add a class to the table based on the monitors status.
		 * @since  1.0.0
		 * @param  [type] $status [description]
		 * @return [type]         [description]
		 */
		function status_class( $status ) {
			switch ( $status ) {
				case 0:
					$class = __( 'warning', 'uptimerobot' );
					break;
				case 1:
					$class = __( 'warning', 'uptimerobot' );
					break;
				case 2:
					$class = __( 'success', 'uptimerobot' );
					break;
				case 8:
					$class = __( 'danger', 'uptimerobot' );
					break;
				case 9:
					$class = __( 'danger', 'uptimerobot' );
					break;
				default:
					$class = __( 'info', 'uptimerobot' );
			}
			return $class;
		}

		/**
		 * Set a description based on the monitor type.
		 * @since  1.0.0
		 * @param  [type] $type [description]
		 * @return [type]       [description]
		 */
		function monitor_type( $type ) {
			switch ( $type ) {
				case 1:
					$type = __( 'HTTP(S)', 'uptimerobot' );
					break;
				case 2:
					$type = __( 'Keyword', 'uptimerobot' );
					break;
				case 3:
					$type = __( 'Ping', 'uptimerobot' );
					break;
				case 4:
					$type = __( 'Port', 'uptimerobot' );
					break;
			}
			return $type;
		}

		/**
		 * Set a description based on the monitor type.
		 * @since  1.0.0
		 * @param  [type] $type [description]
		 * @return [type]       [description]
		 */
		function monitor_subtype( $subtype ) {
			switch ( $subtype ) {
				case 1:
					$subtype = __( 'HTTP', 'uptimerobot' );
					break;
				case 2:
					$subtype = __( 'HTTPS', 'uptimerobot' );
					break;
				case 3:
					$subtype = __( 'FTP', 'uptimerobot' );
					break;
				case 4:
					$subtype = __( 'SMTP', 'uptimerobot' );
					break;
				case 5:
					$subtype = __( 'POP3', 'uptimerobot' );
					break;
				case 6:
					$subtype = __( 'IMAP', 'uptimerobot' );
					break;
				case 99:
					$subtype = __( 'Custom Port', 'uptimerobot' );
					break;
			}
			return $subtype;
		}

		/**
		 * Add the widget to the WordPress Dashboard.
		 * @since  1.0.0
		 * @return [type] [description]
		 */
		function uptimerobot_add_dashboard_widget() {
			wp_add_dashboard_widget(
				'uptimerobot_add_dashboard_widget',
				'Uptime Robot',
				array($this, 'uptimerobot_widget_function')
			);
		}

		/**
		 * Print out all the data and return the content in the Wordpress Dashboard widget.
		 * @since  1.0.0
		 * @return [type] [description]
		 */
		function uptimerobot_widget_function() {

			$json = $this->get_uptime_data();

				$html .= '<div class="table-responsive">';
				$html .= '<table class="table table-hover table-condensed">';
				$html .= '<thead>';

			if ( empty( $json->monitors->monitor ) ) {
					$html .= '<tr class="info">';
					$html .= '<td>You have not entered your API key in the <a href="/wp-admin/options-general.php?page=uptime-robot">options section</a>. Please do so to see your available site monitors.</td';
					$html .= '</tr>';
			}
			else {
					$html .= '<tr>';
					$html .= '<th>'.__( 'ID', 'uptimerobot' ).'</th>';
					$html .= '<th>'.__( 'Status', 'uptimerobot' ).'</th>';
					$html .= '<th>'.__( 'Name', 'uptimerobot' ).'</th>';
					$html .= '<th>'.__( 'Type', 'uptimerobot' ).'</th>';
					$html .= '<th>'.__( 'Subtype', 'uptimerobot' ).'</th>';
					$html .= '<th>'.__( 'Ratio', 'uptimerobot' ).'</th>';
					$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';

				foreach ( $json->monitors->monitor as $monitor ) {
					$html .= '<tr class="'.$this->status_class( $monitor->status ).'">';
					$html .= '<td class="monitor_id">'.$monitor->id.'</td>';
					$html .= '<td class="monitor_status">'.$this->status_type( $monitor->status ).'</td>';
					$html .= '<td class="monitor_name"><a href="'.$monitor->url.'" target="_blank">'.$monitor->friendlyname.'</a></td>';
					$html .= '<td class="monitor_type">'.$this->monitor_type( $monitor->type ).'</td>';
					$html .= '<td class="monitor_type">'.$this->monitor_subtype( $monitor->subtype ).'</td>';
					$html .= '<td class="monitor_ratio">'.$monitor->alltimeuptimeratio.'</td>';
					$html .= '</tr>';

				}
			}

			$html .= '</tbody>';
			$html .= '</table>';
			$html .= '</div>';

			printf( $html );

		}
		//End Functions
 	}//End Class
 }//End If
