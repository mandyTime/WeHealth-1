<?php

require_once( trailingslashit( get_template_directory() ) . 'includes/functions-admin.php' );
require_once( trailingslashit( get_template_directory() ) . 'includes/functions.php' );


// 评论模板
function mytheme_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    global $commentcount;
    if(!$commentcount) {
        $page = get_query_var('cpage')-1;
        $cpp=get_option('comments_per_page');
        $commentcount = $cpp * $page;
    }
?>
<?php global $salong; ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
  <article id="div-comment-<?php comment_ID() ?>" class="comment-art">
    <?php $add_below = 'div-comment'; ?>
      <div  class="comment-author">
      <?php $user_id = $comment->user_id; $user_name = $comment->comment_author; echo salong_get_avatar($user_id,$user_name); ?>
      <div class="comment-info">
          <?php if ($salong['switch_link_go']) {commentauthor();} else {comment_author_link();} ?>&nbsp;<?php _e( '发布于：', 'salong' ); ?>&nbsp;<time class="datetime"><?php comment_date('Y-m-d') ?>&nbsp;<?php comment_time('H:i:s') ?></time>
      <?php edit_comment_link(__('编辑','salong'),'+',''); ?>
       <span class="reply-del right">
           <?php if ( get_option( 'comment_registration') && !is_user_logged_in() ) { ?>
           <a href="#login" title="<?php _e( '点击登录留言或回复评论', 'salong' ); ?>"><?php _e( '登录回复', 'salong' ); ?></a>
           <?php } else { ?>
           <?php comment_reply_link(array_merge( $args, array('reply_text' => __('回复','salong'), 'add_below' =>$add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
           <?php } ?>
           <?php
    if ( is_user_logged_in() ) {
        $url = get_bloginfo('url');
        echo '<a id="delete-'. $comment->comment_ID .'" href="' . wp_nonce_url("$url/wp-admin/comment.php?action=deletecomment&amp;p=" . $comment->comment_post_ID . '&amp;c=' . $comment->comment_ID, 'delete-comment_' . $comment->comment_ID) . '"" >'.__('删除','salong').'</a>';
    }
           ?>
      </span>
      </div>
      <div class="comment-text"><?php comment_text() ?></div>
    <?php if ( $comment->comment_approved == '0' ) : ?><p class="check"><?php _e( '您的评论正在等待审核中...', 'salong' ); ?></p>
      </div >
    <?php endif; ?>
  </article>
  <?php } function mytheme_end_comment() {
    echo '</li>';
}
