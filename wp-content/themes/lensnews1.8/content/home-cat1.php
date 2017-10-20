<?php global $salong; ?>
<?php if ($salong[ 'cat1_list1'] || $salong[ 'cat1_list2']){ ?>
<section class="cat wrapper scroll1">
    <section class="cat-wrap<?php if(!wp_is_mobile()) { ?> left<?php } ?>">
        <!--分类列表1-->
        <?php if ($salong[ 'cat1_list1']){
        foreach ($salong['cat1_list1'] as $cat1list1) {?>
        <section class="box<?php triangle();wow(); ?>">
            <!--标题-->
            <section class="home_title">
                <h3 class="left"><?php echo get_cat_name($cat1list1);?></h3>
                <?php if($salong[ 'cat1_tag1']) { ?>
                <section class="title-tag right">
                    <ul>
                        <?php $hometagsorderby=$salong[ 'home_tag_orderby']; $hometagsorder=$salong[ 'home_tag_order']; $cat1tag1=implode( ',',$salong[ 'cat1_tag1']); $args=array( 'include'=> $cat1tag1,'orderby'=>$hometagsorderby, 'order'=>$hometagsorder);$tags = get_tags($args);foreach ($tags as $tag) { ?>
                        <li>
                            <a href="<?php echo get_tag_link( $tag->term_id ); ?>" title="<?php _e( '查看','salong'); ?> <?php echo $tag->name;?> <?php _e( '标签下的文章','salong'); ?>">
                                <?php echo $tag->name; ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                    <a href="<?php echo get_category_link($cat1list1);?>" class="home_button" title="<?php _e( '查看更多', 'salong' ); ?><?php echo get_cat_name($cat1list1);?>"><i class="icon-plus-circled"></i></a>
                </section>
                <?php } ?>
            </section>
            <!--标题end-->
            <section class="cat1_list1">
                <ul class="layout_ul">
                    <?php $cat1count1=$salong[ 'cat1_count1']; $args=array( 'post_type'=> 'post','posts_per_page' => $cat1count1,'ignore_sticky_posts' => 1,'cat'=> $cat1list1 );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : $count = 1; while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                    <?php if($count==1 || $count==2 ) { ?>
                    <li class="layout_li first">
                        <article class="postgrid">
                            <figure>
                                <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                                    <?php post_thumbnail(); ?>
                                </a>
                            </figure>
                            <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                            <?php get_template_part( 'content/home', 'info'); ?>
                            <!-- 摘要 -->
                            <div class="excerpt">
                                <?php if (has_excerpt()) { ?>
                                <?php echo wp_trim_words(get_the_excerpt(),56); ?>
                                <?php } else{ echo wp_trim_words(get_the_content(),56); } ?>
                            </div>
                            <!-- 摘要end -->
                        </article>
                    </li>
                    <?php } else { ?>
                    <li class="layout_li">
                        <?php get_template_part( 'content/post', 'list'); ?>
                    </li>
                    <?php } $count++; ?>
                    <?php endwhile;endif; ?>
                    <?php wp_reset_query(); ?>
                </ul>
            </section>
        </section>
        <?php } ?>
        <?php } ?>
        <!--分类列表1-->
        <!--分类列表2-->
        <?php if ($salong[ 'cat1_list2']){
        foreach ($salong['cat1_list2'] as $cat1list2) {?>
        <section class="box<?php triangle();wow(); ?>">
            <!--标题-->
            <section class="home_title">
                <h3 class="left"><?php echo get_cat_name($cat1list2);?></h3>
                <?php if($salong[ 'cat1_tag2']) { ?>
                <section class="title-tag right">
                    <ul>
                        <?php $hometagsorderby=$salong[ 'home_tag_orderby']; $hometagsorder=$salong[ 'home_tag_order']; $cat1tag2=implode( ',',$salong[ 'cat1_tag2']); $args=array( 'include'=> $cat1tag2,'orderby'=>$hometagsorderby, 'order'=>$hometagsorder);$tags = get_tags($args);foreach ($tags as $tag) { ?>
                        <li>
                            <a href="<?php echo get_tag_link( $tag->term_id ); ?>" title="<?php _e( '查看','salong'); ?> <?php echo $tag->name;?> <?php _e( '标签下的文章','salong'); ?>">
                                <?php echo $tag->name; ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                    <a href="<?php echo get_category_link($cat1list2);?>" class="home_button" title="<?php _e( '查看更多', 'salong' ); ?><?php echo get_cat_name($cat1list2);?>"><i class="icon-plus-circled"></i></a>
                </section>
                <?php } ?>
            </section>
            <!--标题end-->
            <section class="cat1_list2">
                <ul class="layout_ul">
                    <?php $cat1count2=$salong[ 'cat1_count2']; $args=array( 'post_type'=> 'post','posts_per_page' => $cat1count2,'ignore_sticky_posts' => 1,'cat'=> $cat1list2 );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : $count = 1; while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                    <?php if($count==1 || $count==6 || $count==11 ) { ?>
                    <li class="layout_li first">
                        <article class="postgrid">
                            <figure>
                                <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
                                    <?php post_thumbnail(); ?>
                                </a>
                            </figure>
                            <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                            <?php get_template_part( 'content/home', 'info'); ?>
                            <!-- 摘要 -->
                            <div class="excerpt">
                                <?php if (has_excerpt()) { ?>
                                <?php echo wp_trim_words(get_the_excerpt(),78); ?>
                                <?php } else{ echo wp_trim_words(get_the_content(),78); } ?>
                            </div>
                            <!-- 摘要end -->
                        </article>
                    </li>
                    <?php } else { ?>
                    <li class="layout_li">
                        <?php get_template_part( 'content/post', 'list'); ?>
                    </li>
                    <?php } $count++; ?>
                    <?php endwhile;endif; ?>
                    <?php wp_reset_query(); ?>
                </ul>
            </section>
        </section>
        <?php } ?>
        <?php } ?>
        <!--分类列表2-->
        <!-- 广告 -->
        <?php ad_cat1(); ?>
    </section>
    <!--边栏-->
    <?php if(!wp_is_mobile()) { ?>
    <?php if(is_active_sidebar('sidebar-1')) { ?>
    <aside class="sidebar right">
        <section class="cat1_sidebar">
            <?php dynamic_sidebar( __( '首页分类模块1', 'salong') ); ?>
        </section>
        <?php if(wp_is_mobile()){ $mb=0;} else { $mb=32;} ?>
        <script>
            // 边栏随窗口移动
            $(function () {
                if ($(".cat1_sidebar").length > 0) {
                    $('.cat1_sidebar').scrollChaser({
                        wrapper: '.scroll1',
                        offsetTop: 40
                    });
                }
            });
        </script>
    </aside>
    <!--边栏end-->
    <?php } else { ?>
    <aside class="sidebar right"><article class="sidebar_widget widget_salong_init box<?php triangle();wow(); ?>"><div class="sidebar_title"><h3><?php _e('温馨提示','salong'); ?></h3></div><div class="init"><a href="<?php echo get_home_url(); ?>/wp-admin/widgets.php"><?php _e('请到后台外观——小工具中添加小工具到<br><strong>首页分类模块1</strong>边栏中。','salong'); ?></a></div></article></aside>
    <?php } } ?>
</section>
<?php } ?>