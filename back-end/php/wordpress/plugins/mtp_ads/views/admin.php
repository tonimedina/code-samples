<?php
$code     = get_post_meta( $post->ID, '_mtp_ad_code', true );
$category = get_post_meta( $post->ID, '_mtp_ad_category', true );
$home     = get_post_meta( $post->ID, '_mtp_ad_home', true );
$page     = get_post_meta( $post->ID, '_mtp_ad_page', true );
$single   = get_post_meta( $post->ID, '_mtp_ad_single', true );
$tag      = get_post_meta( $post->ID, '_mtp_ad_tag', true );
$position = get_post_meta( $post->ID, '_mtp_ad_position', true );
$number   = get_post_meta( $post->ID, '_mtp_ad_number', true );

$category_checked = ( ! empty( $category ) ) ? 'checked="checked"' : '';
$home_checked     = ( ! empty( $home ) ) ? 'checked="checked"' : '';
$page_checked     = ( ! empty( $page ) ) ? 'checked="checked"' : '';
$single_checked   = ( ! empty( $single ) ) ? 'checked="checked"' : '';
$tag_checked      = ( ! empty( $tag ) ) ? 'checked="checked"' : '';

$options = array(
  'before_html'        => __( 'Background', 'mtp_plugin' ),
  'before_title'       => __( 'Before Site Title', 'mtp_plugin' ),
  'after_title'        => __( 'After Site Title', 'mtp_plugin' ),
  'byline_item'        => __( 'After Post Headline', 'mtp_plugin' ),
  'before_content'     => __( 'Before Content', 'mtp_plugin' ),
  'before_sidebars'    => __( 'Before Sidebars', 'mtp_plugin' ),
  'after_post'         => __( 'After Post', 'mtp_plugin' ),
  'before_footer'      => __( 'Before Footer', 'mtp_plugin' ),
  'between_paragraphs' => __( 'Between Paragraphs', 'mtp_plugin' ),
  'after_post_box'     => __( 'Between Posts', 'mtp_plugin' ),
  'after_teasers_box'  => __( 'Between Teasers', 'mtp_plugin' ),
  'sidebar'            => __( 'Sidebar', 'mtp_plugin' )
); ?>
<textarea class="widefat" cols="40" id="mtp_ad_code" name="mtp_ad_code" rows="10"><?php echo stripslashes( esc_attr( $code ) ); ?></textarea>

<p>
  <input id="mtp_ad_category" name="mtp_ad_category" type="checkbox" <?php echo $category_checked; ?> />
  <label for="mtp_ad_category"><?php _e( 'Show in categories', 'mtp_plugin' ); ?></label>

  <input id="mtp_ad_home" name="mtp_ad_home" type="checkbox" <?php echo $home_checked; ?> />
  <label for="mtp_ad_home"><?php _e( 'Show in home page', 'mtp_plugin' ); ?></label>

  <input id="mtp_ad_page" name="mtp_ad_page" type="checkbox" <?php echo $page_checked; ?> />
  <label for="mtp_ad_page"><?php _e( 'Show in pages', 'mtp_plugin' ); ?></label>

  <input id="mtp_ad_single" name="mtp_ad_single" type="checkbox" <?php echo $single_checked; ?> />
  <label for="mtp_ad_single"><?php _e( 'Show in posts', 'mtp_plugin' ); ?></label>

  <input id="mtp_ad_tag" name="mtp_ad_tag" type="checkbox" <?php echo $tag_checked; ?> />
  <label for="mtp_ad_tag"><?php _e( 'Show in tags', 'mtp_plugin' ); ?></label>
</p>

<select class="widefat" id="mtp_ad_position" name="mtp_ad_position">
  <?php foreach ( $options as $key => $value ) : ?>
    <?php $selected = ( $position == $key ) ? 'selected="selected"' : ''; ?>
    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
  <?php endforeach; ?>
</select>

<?php if ( $position == 'between_paragraphs' || $position ==  'after_post_box' || $position == 'after_teasers_box' ) : ?>
  <p>
    <input class="widefat" id="mtp_ad_number" min="2" max="10" name="mtp_ad_number" placeholder="<?php _e( 'After number', 'mtp_plugin' ); ?>" step="2" type="number" value="<?php echo esc_attr( $number ); ?>" />
  </p>
<?php endif;