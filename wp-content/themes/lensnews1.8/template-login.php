<?php
/*
Template Name: 登录注册
*/ 
get_header();
global $salong;
?>
<header class="header">
    <a href="<?php echo get_home_url(); ?>" class="logo" title="<?php bloginfo('name'); ?>-<?php bloginfo('description'); ?>"><img src="<?php echo $salong['logo']['url']; ?>" alt="<?php bloginfo( 'name' ); ?>"></a>
</header>
<section class="wrapper">
    <!-- 内容 -->
    <!-- 获取文章 -->
    <article class="login_register box<?php triangle();wow(); ?>">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
        <!-- 文章end -->
        <?php endwhile; endif; ?>
        <!-- 获取文章end -->
    </article>
    <!-- 内容end -->
</section>
<!-- 主体内容end -->
<?php get_footer(); ?>
