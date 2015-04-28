<?php
$ads = get_posts( array(
  'meta_key'    => '_mtp_ad_code',
  'numberposts' => '-1',
  'post_status' => 'publish',
  'post_type'   => 'mtp_ad'
) );

if ( $ads ) : ?>
  <p><?php _e( 'Select wich ads you want to display on this article: ', 'mtp_plugin' ); ?></p>

  <?php foreach ( $ads as $ad ) : ?>
    <?php $visible = get_post_meta( $post->ID, '_mtp_ad_' . $ad->ID, true ); ?>
    <?php $checked = ( ! empty( $visible ) ) ? 'checked="checked"' : ''; ?>
    <input id="mtp_ad_<?php echo $ad->ID; ?>" name="mtp_ad_<?php echo $ad->ID; ?>" type="checkbox" <?php echo $checked; ?> /> 
    <label for="mtp_ad_<?php echo $ad->ID; ?>"><?php _e( $ad->post_title, 'mtp_plugin' ); ?></label><br>
  <?php endforeach; ?>

<?php else : ?>
  <p><?php _e( 'You have not created any ads yet.', 'mtp_plugin' ); ?></p>
<?php endif;