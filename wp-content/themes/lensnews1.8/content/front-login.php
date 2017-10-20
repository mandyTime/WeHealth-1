<!--前台弹窗登录-->
<a href="#ln" class="overlay" id="login"></a>
<section class="front_login popup">
    <section class="login_header">
        <h3><?php _e('用户登录','salong'); ?></h3>
        <a href="#ln" title="<?php _e('关闭','salong'); ?>"><i class="icon-cancel-circled"></i></a>
    </section>
    <?php echo salong_frontend_login(); ?>
</section>