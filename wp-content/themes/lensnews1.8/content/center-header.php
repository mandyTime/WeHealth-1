<?php global $salong;

$register_page  = get_permalink($salong['register_page']);
$login_page = get_permalink($salong['login_page']).'?redirect_to='.$_SERVER['REQUEST_URI'];
?>
<!--用户信息-->
<?php if (!wp_is_mobile()) { ?>
<article class="crumbs_page">
    <h2><?php echo get_the_title(); ?></h2>
    <div class="bg"></div>
</article>
<?php } ?>
<!--用户信息end-->
<!-- 主体内容 -->
<section class="container">
    <section class="wrapper wpuf">
        <?php if(!wp_is_mobile()) { ?>
        <!-- 边栏 -->
        <?php if(is_user_logged_in()) { if(is_active_sidebar( 'sidebar-8')) { dynamic_sidebar(__( '用户中心', 'salong')); }else{ ?>
        <section class="sidebar_widget box widget_salong_init<?php wow(); ?>">
            <div class="init"><a href="<?php echo get_home_url(); ?>/wp-admin/widgets.php"><?php _e('请到后台外观——小工具中添加小工具到<br><strong>用户中心</strong>边栏中。','salong'); ?></a></div>
        </section>
        <?php } } else { ?>
        <section class="sidebar_widget box widget_nav_menu<?php wow(); ?>">
            <div class="menu-wpuf">
                <ul class="menu">
                    <li class="icon-login menu-item">
                        <a href="<?php echo $login_page; ?>" title="<?php _e('登录','salong'); ?>">
                            <?php _e( '登录', 'salong'); ?>
                        </a>
                    </li>
                    <li class="icon-plus-circled menu-item">
                        <a href="<?php echo $register_page; ?>" title="<?php _e('注册一个新的帐户','salong'); ?>">
                            <?php _e( '注册', 'salong'); ?>
                        </a>
                    </li>
                    <li class="icon-lock menu-item">
                        <a href="<?php echo wp_lostpassword_url(); ?>" title="<?php _e('忘记密码','salong'); ?>">
                            <?php _e( '忘记密码', 'salong'); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </section>
        <?php } } ?>
        <!-- 边栏end -->