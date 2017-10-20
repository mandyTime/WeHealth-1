<?php get_header(); ?>
<?php global $salong; ?>
<section class="container wrapper">
    <?php if(!wp_is_mobile()){ get_template_part( 'content/content', 'crumbs'); } ?>
    <?php if($salong[ 'switch_cat_post'] && !wp_is_mobile()) { get_template_part( 'content/blog', 'top'); } ?>
    <section class="content<?php if(!wp_is_mobile()) { echo ' content_left'; } ?>">
        <section class="content-wrap ajaxposts">
            <?php blog_ad(); if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article class="ajaxpost box<?php triangle();wow(); ?>">
                <?php get_template_part( 'content/list', 'post'); ?>
            </article>
            <?php endwhile; else: ?>
            <p class="box<?php triangle();wow(); ?>">
                <?php _e( '非常抱歉，没有相关文章。'); ?>
            </p>
            <?php endif; ?>
            <!-- 分页 -->
            <?php the_posts_pagination(array( 'mid_size'=> 1,'prev_text' => __( '上一页', 'salong' ),'next_text' => __( '下一页', 'salong' ),) ); ?>
        </section>
        <!-- 博客边栏 -->
        <?php get_sidebar(); ?>
        <!-- 博客边栏end -->
    </section>
</section>
<?php get_footer(); ?>