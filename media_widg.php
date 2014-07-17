<?php
/*
Plugin Name: Mediacore Top Four
Description: Embeds the top four videos from Atube
*/

define('MEDIA_PATH', plugin_dir_path( __FILE__ ) );

// Widget functions page
require_once( MEDIA_PATH . '/mediacore_content.php' );

class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'wpb_widget', 
            __('Mediacore Top Four', 'mediacore_top_four'), 
            array( 'description' => __( 'Embeds the top four videos from Atube', 'mediacore_top_four' ), ) 
        );
        add_shortcode( 'atube_embed', 'mediacore_test_content' );
        add_shortcode( 'atube_embed_single', 'mediacore_single_content' );
        add_shortcode( 'atube_embed_single_name', 'mediacore_single_content_name' );
    }

    public function widget( $args, $instance ) {
        mediacore_test_content(NULL, TRUE);
    }

    public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}


}

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
/* Stop Adding Functions Below this Line */
?>