<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php if ( isset( $instance['title'] ) ) { echo esc_attr( $instance['title'] ); } ?>" />
</p>
<hr style="boder:none;border-top:1px solid #dfdfdf;" />
<p>
    <label for="<?php echo $this->get_field_id( 'recent_title' ); ?>"><?php _e( 'Recent Title:' ) ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'recent_title' ); ?>" name="<?php echo $this->get_field_name( 'recent_title' ); ?>" type="text" value="<?php if ( isset( $instance['recent_title'] ) ) { echo esc_attr( $instance['recent_title'] ); } ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'recent_number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
    <input id="<?php echo $this->get_field_id( 'recent_number' ); ?>" name="<?php echo $this->get_field_name( 'recent_number' ); ?>" size="3" type="text" value="<?php echo $recent_number; ?>" />
</p>
<p>
    <input <?php checked( $recent_show_thumbnail ); ?> class="checkbox" id="<?php echo $this->get_field_id( 'recent_show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'recent_show_thumbnail' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'recent_show_thumbnail' ); ?>"><?php _e( 'Display post thumbnail?' ); ?></label>
</p>
<p>
    <input <?php checked( $recent_show_date ); ?> class="checkbox" id="<?php echo $this->get_field_id( 'recent_show_date' ); ?>" name="<?php echo $this->get_field_name( 'recent_show_date' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'recent_show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label>
</p>
<hr style="boder:none;border-top:1px solid #dfdfdf;" />
<p>
    <label for="<?php echo $this->get_field_id( 'tags_title' ); ?>"><?php _e( 'Tags Title:' ) ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'tags_title' ); ?>" name="<?php echo $this->get_field_name( 'tags_title' ); ?>" type="text" value="<?php if ( isset( $instance['tags_title'] ) ) { echo esc_attr( $instance['tags_title'] ); } ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'tags_taxonomy' ); ?>"><?php _e( 'Taxonomy:' ) ?></label>
    <select class="widefat" id="<?php echo $this->get_field_id( 'tags_taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'tags_taxonomy' ); ?>">
        <?php foreach ( get_taxonomies() as $taxonomy ) {
            $tax = get_taxonomy( $taxonomy );

            if ( !$tax->show_tagcloud || empty( $tax->labels->name ) )
                continue;
            ?>
            <option <?php selected( $taxonomy, $current_taxonomy ) ?> value="<?php echo esc_attr( $taxonomy ) ?>"><?php echo $tax->labels->name; ?></option>
            <?php
        } ?>
    </select>
</p>
<hr style="boder:none;border-top:1px solid #dfdfdf;" />
<p>
    <label for="<?php echo $this->get_field_id( 'popular_title' ); ?>"><?php _e( 'Popular Title:' ) ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'popular_title' ); ?>" name="<?php echo $this->get_field_name( 'popular_title' ); ?>" type="text" value="<?php if ( isset( $instance['popular_title'] ) ) { echo esc_attr( $instance['popular_title'] ); } ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'popular_number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
    <input id="<?php echo $this->get_field_id( 'popular_number' ); ?>" name="<?php echo $this->get_field_name( 'popular_number' ); ?>" size="3" type="text" value="<?php echo $popular_number; ?>" />
</p>
<p>
    <input <?php checked( $popular_show_thumbnail ); ?> class="checkbox" id="<?php echo $this->get_field_id( 'popular_show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'popular_show_thumbnail' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'popular_show_thumbnail' ); ?>"><?php _e( 'Display post thumbnail?' ); ?></label>
</p>
<p>
    <input <?php checked( $popular_show_date ); ?> class="checkbox" id="<?php echo $this->get_field_id( 'popular_show_date' ); ?>" name="<?php echo $this->get_field_name( 'popular_show_date' ); ?>" type="checkbox" />
    <label for="<?php echo $this->get_field_id( 'popular_show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label>
</p>