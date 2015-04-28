<div class="mtp-tabs">
    <ul>
        <li><a href="#tabs-recent"><?php $recent_title = apply_filters( 'widget_title', $recent_title, $instance, $this->id_base ); echo $recent_title; ?></a></li>
        <li><a href="#tabs-tags"><?php $tags_title = apply_filters( 'widget_title', $tags_title, $instance, $this->id_base ); echo $tags_title; ?></a></li>
        <li><a href="#tabs-popular"><?php $popular_title = apply_filters( 'widget_title', $popular_title, $instance, $this->id_base ); echo $popular_title; ?></a></li>
    </ul>
    <div id="tabs-recent">
        <?php
        $recent_the_query = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $recent_number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true
        ) ) );
        if ( $recent_the_query->have_posts() ) : ?>
        <ul>
            <?php while ( $recent_the_query->have_posts() ) : $recent_the_query->the_post(); ?>
            <li>
                <?php if ( $recent_show_thumbnail && has_post_thumbnail() ) : ?>
                <p class="alignleft post-thumbnail"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php the_post_thumbnail( 'thumbnail', array( 'class' => 'scale' ) ); ?></a></p>
                <?php endif; ?>
                <p class="post-title"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php if ( get_the_title() ) : the_title(); else : the_ID(); endif; ?></a></p>
                <?php if ( $recent_show_date ) : ?>
                <p class="post-date"><?php echo get_the_date(); ?></p>
                <?php endif; ?>
            </li>
            <?php endwhile; ?>
        </ul>
        <?php wp_reset_query(); wp_reset_postdata(); else : _e( 'It looks like there are no recent posts to show at this moment', 'mtp_plugin' ); endif; ?>
    </div><!-- END #tabs-recent -->
    <div id="tabs-tags">
        <?php
        if ( !empty( $instance['tags_title'] ) ) :
            $tags_title = $instance['tags_title'];
        else :
            if ( $current_taxonomy == 'post_tag' ) :
                $tags_title = __( 'Tags' );
            else :
                $tax        = get_taxonomy( $current_taxonomy );
                $tags_title = $tax->labels->name;
            endif;
        endif;
        ?>
        <div class="tabs-tagcloud"><?php wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array( 'taxonomy' => $current_taxonomy ) ) ); ?></div>
    </div><!-- END #tabs-tags -->
    <div id="tabs-popular">
        <?php
        $popular_the_query = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $popular_number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'orderby'             => 'meta_value',
            'meta_key'            => 'post_stats'
        ) ) );
        if ( $popular_the_query->have_posts() ) : ?>
        <ul>
            <?php while ( $popular_the_query->have_posts() ) : $popular_the_query->the_post(); ?>
            <li>
                <?php if ( $popular_show_thumbnail && has_post_thumbnail() ) : ?>
                <p class="alignleft post-thumbnail"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php the_post_thumbnail( 'thumbnail', array( 'class' => 'scale' ) ); ?></a></p>
                <?php endif; ?>
                <p class="post-title"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php if ( get_the_title() ) : the_title(); else : the_ID(); endif; ?></a></p>
                <?php if ( $popular_show_date ) : ?>
                <p class="post-date"><?php echo get_the_date(); ?></p>
                <?php endif; ?>
            </li>
            <?php endwhile; ?>
        </ul>
        <?php wp_reset_query(); wp_reset_postdata(); else : _e( 'It looks like there are no recent posts to show at this moment', 'mtp_plugin' ); endif; ?>
    </div><!-- END #tabs-popular -->
</div><!-- END .mtp-tabs -->