<?php
$global_id   = $GLOBALS['post']->ID;
$instance_ID = str_replace( '_mtp_ad_', '', $instance['mtp_ad']);
$the_query   = new WP_Query( array(
  'numberposts' => 1,
  'p'           => $instance_ID,
  'post_status' => 'publish',
  'post_type'   => 'mtp_ad'
) );

if ( $the_query->have_posts() )
{
  while ( $the_query->have_posts() )
  {
    $the_query->the_post();

    $post_ID  = get_the_ID();
    $code     = get_post_meta( $post_ID, '_mtp_ad_code', true );
    $category = get_post_meta( $post_ID, '_mtp_ad_category', true );
    $home     = get_post_meta( $post_ID, '_mtp_ad_home', true );
    $page     = get_post_meta( $post_ID, '_mtp_ad_page', true );
    $single   = get_post_meta( $post_ID, '_mtp_ad_single', true );
    $tag      = get_post_meta( $post_ID, '_mtp_ad_tag', true );
    $visible  = get_post_meta( $global_id, '_mtp_ad_' . $post_ID, true );

    if ( $category && is_category() || $home && is_home() || $home && $visible && is_front_page() || $page && $visible && is_page() || $single && $visible && is_single() || $tag && is_tag() ) :
      if ( $instance['title'] ) :
        echo $before_title . $instance['title'] . $after_title;
      endif; ?>
      <div class="mtp-ad" style="text-align: center"><?php echo stripslashes( $code ); ?></div>
      <?php
    endif;
  }
}

wp_reset_postdata();
