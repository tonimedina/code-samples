<?php
$title  = strip_tags( esc_attr( $instance['title'] ) );
$mtp_ad = strip_tags( esc_attr( $instance['mtp_ad'] ) );
$ads    = get_posts( array(
  'meta_key'    => '_mtp_ad_position',
  'meta_value'  => 'sidebar',
  'numberposts' => '-1',
  'post_status' => 'publish',
  'post_type'   => 'mtp_ad'
) );

if ( $ads ) : ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'mtp_ad' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>">
  </p>

  <select class="widefat" id="<?php echo $this->get_field_id( 'mtp_ad' ); ?>" name="<?php echo $this->get_field_name( 'mtp_ad' ); ?>">
    <?php foreach ( $ads as $ad ) : ?>
    <?php $selected = ( $mtp_ad == '_mtp_ad_' . $ad->ID ) ? 'selected="selected"' : ''; ?>
    <option value="_mtp_ad_<?php echo esc_attr( $ad->ID ) ?>" <?php echo $selected; ?>><?php echo esc_attr( $ad->post_title ); ?></option>
    <?php endforeach; ?>
  </select>

<?php wp_reset_postdata(); else : ?>
  <p><?php _e( 'You have not created any ads yet.', 'mtp_plugin' ); ?></p>
<?php endif;