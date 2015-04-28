<?php

if ( !defined( 'ABSPATH' ) )
	exit();

global $newsletter_options;

//register widget only if enabled
if( isset($newsletter_options['enable_widget']) && $newsletter_options['enable_widget'] )
	add_action( 'widgets_init', create_function( '', 'register_widget( "mtp_widget_newsletter" );' ) );

/*** WIDGET CLASS ***/
class mtp_widget_newsletter extends WP_Widget
{	
	public function __construct()
	{
		$widget_options = array(
			'classname'   => 'mtp_newsletter_widget',
			'description' => __( 'Insert a newsletter form subscription.', 'framework' )
		);

		parent::__construct( 'newsletter', __( 'MTP Newsletter' ), $widget_options );
	}

	public function form( $instance )
	{
		$instance = wp_parse_args( (array) $instance, array(
			'title'       => '',
			'disclaimer'  => ''
		) );
		$title      = strip_tags( $instance['title'] ); 
		$disclaimer = isset( $instance['disclaimer'] ) ? $instance['disclaimer'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'framework' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'disclaimer' ); ?>"><?php _e( 'Include disclaimer text:', 'framework' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'disclaimer' ); ?>" name="<?php echo $this->get_field_name( 'disclaimer' ); ?>" type="checkbox" <?php checked( isset( $instance['disclaimer'] ) ? $instance['disclaimer'] : 0 ); ?> />
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['disclaimer'] = isset( $new_instance['disclaimer'] );

		return $instance;
	}

	public function widget( $args, $instance )
	{
		extract( $args );

		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$disclaimer = empty( $instance['disclaimer'] ) ? false : true;

		echo $before_widget;

		if ( $title ) : echo $before_title . $title . $after_title; endif;

		mtp_newsletter_form($disclaimer);

		echo $after_widget;
	}
}
?>