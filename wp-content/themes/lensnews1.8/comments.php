<?php global $salong; if ( post_password_required() ) {return;} ?>
<?php if ( 'open'==$post->comment_status) { ?>
<section id="respond" class="box<?php triangle();wow(); ?>">
    <section class="home_title">
        <h3><?php _e( '发表评论', 'salong' ); ?></h3>
    </section>
    <!--取消回复-->
    <?php cancel_comment_reply_link(); ?>
    <?php if ( get_option( 'comment_registration') && !$user_ID ){ ?>
    <p class="need_login">
        <?php _e( '您必须', 'salong' ); ?><a href="#login">[<?php _e( '登录', 'salong' ); ?>]</a>
        <?php _e( '才能发表留言！', 'salong' ); ?>
    </p>
    <?php } else { ?>
    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="commentform">
        <?php if ( $user_ID ) { ?>
        <section class="login_user">
            <?php print __( '登录用户：', 'salong' ); ?>
            <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php">
                <?php echo $user_identity; ?>
            </a>.
            <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e( '退出', 'salong' ); ?>">
                <?php print __( '登出', 'salong' ); ?>
            </a>
        </section>
        <?php } else if ( '' !=$comment_author ) { ?>
        <div class="author">
            <?php _e( '您好，', 'salong' ); ?>
            <?php printf(__( '<strong>%s</strong>'), $comment_author); ?>
            <?php _e( '欢迎来到', 'salong' ); ?>
            <?php bloginfo( 'name' ); ?>！ <a href="javascript:toggleCommentAuthorInfo();" id="toggle-comment-author-info">[<?php _e( '更改', 'salong' ); ?>]</a>
            <script type="text/javascript" charset="utf-8">
                var changeMsg = "[<?php _e( '更改', 'salong' ); ?>]";
                var closeMsg = "[<?php _e( '关闭', 'salong' ); ?>]";

                function toggleCommentAuthorInfo() {
                    jQuery("#comment-author-info").slideToggle("slow", function() {
                        if (jQuery("#comment-author-info").css("display") == "none") {
                            jQuery("#toggle-comment-author-info").text(changeMsg)
                        } else {
                            jQuery("#toggle-comment-author-info").text(closeMsg)
                        }
                    })
                }
                jQuery(document).ready(function() {
                    jQuery("#comment-author-info").hide()
                });
            </script>
        </div>
        <?php } ?>
        <?php if ( !$user_ID ){ ?>
        <div id="comment-author-info">
            <p class="form-author">
                <input type="text" name="author" id="author" placeholder="<?php _e( '昵称', 'salong' ); ?>" required value="<?php echo $comment_author; ?>" size="12" tabindex="1" />
            </p>
            <p class="form-email">
                <input type="email" name="email" id="email" placeholder="<?php _e( 'Email', 'salong' ); ?>" required value="<?php echo $comment_author_email; ?>" size="24" tabindex="2" />
            </p>
            <p class="form-url">
                <input type="text" name="url" id="url" placeholder="<?php _e( '网址', 'salong' ); ?>" value="<?php echo $comment_author_url; ?>" size="24" tabindex="3" />
            </p>
        </div>
        <?php } ?>

        <p class="form-textarea">
            <textarea name="comment" placeholder="<?php _e( '请输入内容…', 'salong' ); ?>" id="comment" cols="40" rows="6" required tabindex="4"></textarea>
        </p>
        <div class="form-submit">
            <input class="submit" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e( '提交留言', 'salong' ); ?>" />
            <input class="reset" name="reset" type="reset" id="reset" tabindex="6" value="<?php esc_attr_e(_e( '重写', 'salong' )); ?>" />
            <?php comment_id_fields(); ?>
        </div>
        <script type="text/javascript">
            //Crel+Enter
            $(document).keypress(function(e) {
                if (e.ctrlKey && e.which == 13 || e.which == 10) {
                    $(".submit").click();
                    document.body.focus();
                } else if (e.shiftKey && e.which == 13 || e.which == 10) {
                    $(".submit").click();
                }
            })
        </script>
        <?php do_action( 'comment_form', $post->ID); ?>
    </form>
    <?php } ?>
</section>
<?php } ?>
<?php if ($comments) { ?>
<section id="comments" class="box<?php triangle();wow(); ?>">
    <?php foreach ($comments as $comment) if (get_comment_type() !="comment" ) $numPingBacks++; else $numComments++; ?>
    <section class="home_title">
        <h3 class="title">
            <?php _e('评论：','salong');
                         $my_email  = get_bloginfo ( 'admin_email' );
                         $str       = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_approved = '1' AND comment_type = '' AND comment_author_email";
                         $count_t   = $post->comment_count;
                         $count_v   = $wpdb->get_var("$str != '$my_email'");
                         $count_h   = $wpdb->get_var("$str = '$my_email'");
            ?>
            <span class="right">
               <?php if ( 'open'!=$post->comment_status) { ?>
                <p class="nocomments">
                <?php _e( '抱歉，评论已关闭。当前评论：', 'salong' ); ?>
                </p>
                <?php } ?>
                <?php printf( __( '%s 条评论，访客：%s 条，博主：%s 条' , 'salong' ), esc_attr($count_t), esc_attr($count_v), esc_attr($count_h) ); ?>
                <?php if($numPingBacks>0) { ?><?php printf( __( '，当前引用：%s 条' , 'salong' ), esc_attr($numPingBacks)); ?><?php } ?>
            </span>
        </h3>
    </section>
    <!-- 评论列表 -->
    <ul class="commentlist">
        <?php wp_list_comments( 'type=comment&callback=mytheme_comment&end-callback=mytheme_end_comment'); ?>
    </ul>
    <!-- 评论列表end -->
    <!-- 引用 -->
    <?php if($numPingBacks>0) { ?>
    <section id="trackbacks">
    <section class="home_title">
    <h3 class="title"><?php printf( __( '当前外部引用：%s 条' , 'salong' ), esc_attr($numPingBacks)); ?></h3>
    </section>
    <ul>
        <?php foreach ($comments as $comment) : ?>
        <?php $comment_type = get_comment_type(); ?>
        <?php if($comment_type != 'comment') { ?>
        <li><?php comment_author() ?></li>
        <?php } ?>
        <?php endforeach; ?>
    </ul>
    </section>
    <?php } ?>
    <!-- 引用end -->
    <!-- 评论分页 -->
    <?php $comment_pages=paginate_comments_links( 'echo=0'); if (get_option( 'page_comments') && $comment_pages) { ?>
    <div class="pagination">
        <?php echo $comment_pages; ?>
    </div>
    <?php } ?>
</section>
<?php } ?>