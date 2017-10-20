<?php
/*
Template Name: 所有视频
*/ 
get_header(); ?>
<?php global $salong; ?>
<section class="container wrapper">
    <?php if(!wp_is_mobile()){ get_template_part( 'content/content', 'crumbs'); } ?>
    <section class="video video_list">
        <?php if($salong[ 'switch_video_list']){ ?>
        <?php get_template_part( 'content/video', 'cat-list'); ?>
        <?php }else{ ?>
        <ul class="layout_ul ajaxposts">
            <?php $paged=( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> 'video','ignore_sticky_posts' => 1,'paged' => $paged );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
            <li class="layout_li ajaxpost">
                <?php get_template_part( 'content/list', 'video'); ?>
            </li>
            <?php endwhile; else: ?>
            <p class="box<?php triangle();wow(); ?>">
                <?php _e( '非常抱歉，没有相关视频。', 'salong'); ?>
            </p>
            <?php endif; ?>
            <!-- 分页 -->
            <?php the_posts_pagination(array( 'mid_size'=> 1,'prev_text' => __( '上一页', 'salong' ),'next_text' => __( '下一页', 'salong' ),) ); ?>
        </ul>
        <?php } ?>
    </section>
</section>
<?php get_footer(); ?>