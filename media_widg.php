<?php
/*
Plugin Name: Atube Embed
Description: Embeds the latest from Atube
*/

define('MEDIA_PATH', plugin_dir_path( __FILE__ ) );

// Widget functions page
require_once( MEDIA_PATH . '/mediacore_content.php' );

class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'wpb_widget', 
            __('Atube Embed', 'atube_embedder'), 
            array( 'description' => __( 'Embeds the top four videos from Atube', 'atube_embedder' ), ) 
        );
        // embeds widget anywhere
        add_shortcode( 'atube_embed', 'mediacore_test_content' );
        // embeds single vid
        add_shortcode( 'atube_embed_single', 'mediacore_single_content' );
        // embeds linked title
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