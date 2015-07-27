<?php

/**
 * Uptime Robot Widget class.
 *
 * @class		UptimeRobot
 * @version		1.1.1
 * @package		Uptime Robot
 * @author 		Brian Welch
 */

if ( class_exists( 'UptimeRobot' ) ) {

	/**
	 * Uptime Robot Widget Class
	 */
	class UptimeRobotWidget extends WP_Widget {

		/**
		 * Class Constructor
		 * @since  1.0.9
		 */
		function __construct() {
			parent::__construct(
				'UptimeRobotWidget',
				__( 'Uptime Robot', 'uptimerobot' ),
				array(
					'description' => __( 'Adds an Uptime Robot widget to the front end.', 'uptimerobot' ),
				)
			);
		}

		/**
		 * Uptime Robot Widget
		 * @since  1.1.1
		 * @param  [type] $args     [description].
		 * @param  [type] $instance [description].
		 */
		public function widget( $args, $instance ) {

			$title = apply_filters( 'widget_title', $instance['title'] );
			echo wp_kses( $args['before_widget'], null );

			if ( ! empty( $title ) ) {
				echo wp_kses( $args['before_title'] . $title . $args['after_title'], null );
				$widget = new UptimeRobot;
				$widget->uptimerobot_widget_function();
			}

			echo wp_kses( $args['after_widget'], null );

		}

		/**
		 * Widget settings
		 * @since  1.0.9
		 * @param  [type] $instance [description].
		 */
		public function form( $instance ) {

			if ( isset( $instance['title'] ) ) {
				$title = $instance['title'];
			} else {
				$title = __( 'Uptime Robot', 'uptimerobot' );
			}

			?>
			<p>
				<label for="<?php esc_html_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
				<input class="widefat" id="<?php esc_html_e( $this->get_field_id( 'title' ) ); ?>" name="<?php esc_html_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php esc_html_e( $title ); ?>" />
			</p>
			<?php

		}

		/**
		 * Update widget settings with a new instance if changed.
		 * @since  1.0.9
		 * @param  [type] $new_instance [description].
		 * @param  [type] $old_instance [description].
		 * @return [type]               [description]
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

			return $instance;

		} // End Functions
	} //End Class

	/**
	 * Register and load the Uptime Robot widget.
	 */
	function uptime_robot_load_widget() {
		register_widget( 'UptimeRobotWidget' );
	}
	add_action( 'widgets_init', 'uptime_robot_load_widget' );

} // End If
