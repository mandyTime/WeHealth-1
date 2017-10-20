<?php
global $salong;
header("Content-type: text/html; charset=utf-8");
//前台登录注册与忘记密码
require_once get_template_directory() . '/includes/ajax-auth.php';
//微博链接
if ($salong['switch_weibo_login']) {
	require_once get_template_directory() . '/includes/weibo.php';
}
//商城代码
if (class_exists('woocommerce')) {
	//商城代码
	require_once get_template_directory() . '/woocommerce/config.php';
}
// 小工具
require_once get_template_directory() . '/includes/sidebars.php';
require_once get_template_directory() . '/includes/widgets/widget-post.php';
require_once get_template_directory() . '/includes/widgets/widget-bulletin.php';
require_once get_template_directory() . '/includes/widgets/widget-gallery.php';
require_once get_template_directory() . '/includes/widgets/widget-video.php';
require_once get_template_directory() . '/includes/widgets/widget-about.php';
require_once get_template_directory() . '/includes/widgets/widget-user.php';
require_once get_template_directory() . '/includes/widgets/widget-tag.php';
require_once get_template_directory() . '/includes/widgets/widget-slide.php';
require_once get_template_directory() . '/includes/widgets/widget-comments.php';
require_once get_template_directory() . '/includes/widgets/widget-contact.php';
require_once get_template_directory() . '/includes/widgets/widget-word.php';
require_once get_template_directory() . '/includes/widgets/widget-qqqun.php';
if (salong_is_weixin()) {
	//微信分享 JDK
	require_once get_template_directory() . '/includes/jssdk.php';
	//微信分享数量
	add_action('wp_ajax_nopriv_wechat_share', 'wechat_share_callback');
	add_action('wp_ajax_wechat_share', 'wechat_share_callback');
	function wechat_share_callback()
	{
		$postid = $_POST["postid"];
		$post_bookmarks = get_post_meta($postid, "wechat_share_num", true) ? get_post_meta($postid, "wechat_share_num", true) + 1 : 1;
		update_post_meta($postid, "wechat_share_num", $post_bookmarks);
		exit;
	}
}
// 自定义菜单
register_nav_menus(array('header-menu' => __('导航菜单', 'salong'), 'top-menu' => __('顶部菜单', 'salong'), 'mobile-menu' => __('移动导航菜单', 'salong'), 'user-menu' => __('移动用户菜单', 'salong')));
//菜单回调函数
function Salong_header_nav_fallback()
{
	echo '<div class="header-menu"><ul class="empty"><li><a href="' . get_option('home') . '/wp-admin/nav-menus.php?action=locations">' . __('请在 "后台——外观——菜单" 添加导航菜单', 'salong') . '</a></ul></li></div>';
}
function Salong_top_nav_fallback()
{
	echo '<div class="top-menu"><ul class="empty"><li><a href="' . get_option('home') . '/wp-admin/nav-menus.php?action=locations">' . __('请在 "后台——外观——菜单" 添加顶部菜单', 'salong') . '</a></ul></li></div>';
}
function Salong_mobile_nav_fallback()
{
	echo '<div class="mobile-menu  left"><ul class="empty"><li><a href="' . get_option('home') . '/wp-admin/nav-menus.php?action=locations">' . __('请在 "后台——外观——菜单" 添加移动菜单', 'salong') . '</a></ul></li></div>';
}
function Salong_user_nav_fallback()
{
	echo '<div class="mobile-menu  left"><ul class="empty"><li><a href="' . get_option('home') . '/wp-admin/nav-menus.php?action=locations">' . __('请在 "后台——外观——菜单" 添加用户菜单', 'salong') . '</a></ul></li></div>';
}
//使用WP自带的标题函数
function theme_slug_setup()
{
	add_theme_support('title-tag');
}
add_action('after_setup_theme', 'theme_slug_setup');
//移动菜单上添加退出
function add_logout_to_wp_menu($items, $args)
{
	if ('user-menu' === $args->theme_location) {
		global $salong;
		$items .= '<li class="menu-item">';
		$items .= '<a href="' . wp_logout_url(home_url()) . '" title="' . __('登出', 'salong') . '">' . __('退出登录', 'salong') . '</a>';
		$items .= '</li>';
	}
	return $items;
}
add_filter('wp_nav_menu_items', 'add_logout_to_wp_menu', 10, 2);
//向菜单中添加搜索
function add_search_to_wp_menu($items, $args)
{
	global $salong;
	if ('header-menu' === $args->theme_location && $salong['switch_search_menu']) {
		$items .= '<li class="menu-item menu-item-search">';
		$items .= '<a href="#search" title="' . __('点击搜索', 'salong') . '"><i class="icon-search-1"></i></a>';
		$items .= '</li>';
	}
	return $items;
}
add_filter('wp_nav_menu_items', 'add_search_to_wp_menu', 10, 2);
//授权
if (!function_exists('genuine_allow_domain')) {
	return;
}
//只在前台加载
if (!is_admin()) {
	// 加载前端脚本及样式
	if (!function_exists('salong')) {
		function salong()
		{
			global $salong;
			wp_enqueue_style('style', get_stylesheet_uri(), array(), '2017.03.18');
			wp_enqueue_style('main', get_template_directory_uri() . '/css/main.css', false, '1.0', false);
			wp_deregister_script('jquery');
			wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery.min.js', false, '3.1.1', false);
			if (class_exists('woocommerce')) {
				wp_enqueue_style('woocommerce', get_template_directory_uri() . '/woocommerce/css/woocommerce.css', false, '1.0', false);
			}
			if (!wp_is_mobile()) {
				wp_enqueue_script('wow', get_template_directory_uri() . '/js/wow.min.js', false, '1.0', true);
				wp_enqueue_style('animate', get_template_directory_uri() . '/css/animate.css', false, '1.0', false);
			}
			if (wp_is_mobile()) {
				wp_enqueue_style('response', get_template_directory_uri() . '/css/response.css', false, '1.0', false);
				wp_enqueue_script('snap', get_template_directory_uri() . '/js/snap.js', false, '1.9.3', false);
			}
			wp_enqueue_script('swiper', get_template_directory_uri() . '/js/swiper.jquery.min.js', false, '3.3.1', false);
			wp_enqueue_script('ias', get_template_directory_uri() . '/js/jquery-ias.min.js', false, '2.2.2', true);
			wp_enqueue_script('scrollchaser', get_template_directory_uri() . '/js/jquery.scrollchaser.min.js', false, '0.0.6', true);
			if ($salong['switch_lazyload']) {
				wp_enqueue_script('lazyload', get_template_directory_uri() . '/js/jquery.lazyload.min.js', false, '1.9.3', true);
			}
			wp_enqueue_script('fancybox', get_template_directory_uri() . '/js/jquery.fancybox.min.js', false, '3.0.6', true);
			wp_enqueue_style('fancybox', get_template_directory_uri() . '/css/jquery.fancybox.min.css', false, '3.0.6', 'screen');
			if (in_array('gb2big5', $salong['side_metas'])) {
				wp_enqueue_script('gb2big5', get_template_directory_uri() . '/js/gb2big5.js', false, '1.0', true);
			}
			wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom.js', false, '1.0', true);
			// 样式切换
			global $salong;
			if ($salong['switch_style'] == 'have') {
				$color = $salong['style_options'];
				wp_enqueue_style('colorphp', get_template_directory_uri() . '/includes/color.php?main_color=' . $color . '.css');
			} else {
				if ($salong['switch_style'] == 'custom') {
					wp_enqueue_style('colorphp', get_template_directory_uri() . '/includes/color.php');
				}
			}
			// 自定义CSS样式
			if (!function_exists('salong_css_code')) {
				function salong_css_code()
				{
					global $salong;
					$custom_css_code = $salong['css_code'];
					if (!empty($custom_css_code)) {
						$custom_css_trim = preg_replace('/\s+/', ' ', $custom_css_code);
						$custom_css_out = "<!-- Dynamic css -->\n<style type=\"text/css\">\n" . $custom_css_trim . "\n</style>";
						echo $custom_css_out;
					}
				}
			}
			add_action('wp_head', 'salong_css_code');
		}
		add_action('init', 'salong');
	}
	//文章与页面脚本及样式
	if (!function_exists('singular')) {
		function singular()
		{
			if (is_singular()) {
				wp_enqueue_script('comments-ajax', get_template_directory_uri() . '/js/comments-ajax.js', false, '1.3', true);
				global $salong;
				if ($salong['switch_highlight']) {
					wp_enqueue_style('highlight', get_template_directory_uri() . '/css/highlight.css', false, '3.0.3', 'screen');
				}
			}
		}
		add_action('wp_enqueue_scripts', 'singular');
	}
}
//只在前台加载end
//输入文章类型名称
function post_name()
{
	if ('video' == get_post_type()) {
		$output .= __('视频', 'salong');
	} else {
		if ('gallery' == get_post_type()) {
			$output .= __('画廊', 'salong');
		} else {
			if ('product' == get_post_type()) {
				$output .= __('产品', 'salong');
			} else {
				$output .= __('文章', 'salong');
			}
		}
	}
	return $output;
}
//获取头像
function salong_get_avatar($user_id, $user_name)
{
	global $salong, $wp_query, $comment;
	$GLOBALS['comment'] = $comment;
	$weixin_avatar = get_user_meta($user_id, 'weixin_avatar', true);
	//微信头像
	$qq_avatar = get_user_meta($user_id, 'qq_avatar', true);
	//QQ头像
	$qq_openid = get_user_meta($user_id, 'qq_openid', true);
	//QQ OPEN ID
	$user_avatar = get_user_meta($user_id, 'user_avatar', true);
	//自定义上传头像
	$sina_uid = get_user_meta($user_id, 'sina_uid', true);
	//新浪微博ID
	$user_date = get_userdata($user_id);
	//用户数据
	$user_email = $user_date->user_email;
	//用户邮箱
	$comment_email = $comment->comment_author_email;
	$avatar_loading = $salong['avatar_loading']['url'];
	//默认头像
	$qqlogin_id = $salong['qqlogin_id'];
	//判断邮箱是否注册全球通用头像
	//    $hash               = md5(strtolower(trim($user_email)));
	//    $uri                = 'https://secure.gravatar.com/avatar/' . $hash . '?d=404';
	//    $headers            = @get_headers($uri);
	if ($user_avatar) {
		$avatar = $user_avatar;
	} else {
		if ($weixin_avatar) {
			$avatar = $weixin_avatar;
		} else {
			if ($qq_avatar) {
				if ($qqlogin_id && $salong['switch_social_login']) {
					$avatar = 'https://q.qlogo.cn/qqapp/' . $qqlogin_id . '/' . $qq_openid . '/100';
				} else {
					$avatar = $qq_avatar;
				}
			} else {
				if ($sina_uid) {
					$avatar = 'https://tp3.sinaimg.cn/' . $sina_uid . '/180/1.jpg';
				} else {
					if ($user_email) {
						$avatar = 'https://secure.gravatar.com/avatar/' . md5($user_email) . '?s=120';
					} else {
						if ($comment_email) {
							$avatar = 'https://secure.gravatar.com/avatar/' . md5($comment_email) . '?s=120';
						} else {
							$avatar = $avatar_loading;
						}
					}
				}
			}
		}
	}
	return '<img class="avatar" src="' . $avatar_loading . '" data-original="' . $avatar . '" alt="' . $user_name . '" />';
}
///////////////////////////////////////点赞
//我们需要将函数绑定到WordPress钩子
add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');
//声明变量
wp_enqueue_script('like_post', get_template_directory_uri() . '/js/post-like.js', array('jquery'), '1.0', true);
wp_localize_script('like_post', 'ajax_var', array('url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ajax-nonce')));
//添加post_like函数
function post_like()
{
	// Check for nonce security
	$nonce = $_POST['nonce'];
	if (!wp_verify_nonce($nonce, 'ajax-nonce')) {
		exit('Busted!');
	}
	if (isset($_POST['post_like'])) {
		// Retrieve user IP address
		$ip = $_SERVER['REMOTE_ADDR'];
		$post_id = $_POST['post_id'];
		// Get voters'IPs for the current post
		$meta_IP = get_post_meta($post_id, "voted_IP");
		$voted_IP = $meta_IP[0];
		if (!is_array($voted_IP)) {
			$voted_IP = array();
		}
		// Get votes count for the current post
		$meta_count = get_post_meta($post_id, "votes_count", true);
		// Use has already voted ?
		if (!hasAlreadyVoted($post_id)) {
			$voted_IP[$ip] = time();
			// Save IP and increase votes count
			update_post_meta($post_id, "voted_IP", $voted_IP);
			update_post_meta($post_id, "votes_count", ++$meta_count);
			//点赞增加积分
			if (function_exists('woocommerce_points_rewards_my_points')) {
				$points = get_option('wc_points_rewards_like_points');
				WC_Points_Rewards_Manager::increase_points(get_current_user_id(), $points, 'like', $post_id);
			}
			// Display count (ie jQuery return value)
			echo $meta_count;
		} else {
			echo "already";
		}
	}
	exit;
}
global $salong;
//定义用户多久才能重新投票
$timebeforerevote = stripslashes($salong['like_time']);
// = 1 年
//检查用户是否已经投票
function hasAlreadyVoted($post_id)
{
	global $timebeforerevote;
	// Retrieve post votes IPs
	$meta_IP = get_post_meta($post_id, "voted_IP");
	$voted_IP = $meta_IP[0];
	if (!is_array($voted_IP)) {
		$voted_IP = array();
	}
	// Retrieve current user IP
	$ip = $_SERVER['REMOTE_ADDR'];
	// If user has already voted
	if (in_array($ip, array_keys($voted_IP))) {
		$time = $voted_IP[$ip];
		$now = time();
		// Compare between current time and vote time
		if (round(($now - $time) / 60) > $timebeforerevote) {
			return false;
		}
		return true;
	}
	return false;
}
//创建函数输出文章HTML代码
function getPostLikeLink($post_id)
{
	global $salong;
	$vote_count = get_post_meta($post_id, "votes_count", true);
	if (hasAlreadyVoted($post_id)) {
		$output .= '<span title="' . __('您已经点赞了！', 'salong') . '" class="like alreadyvoted"><i class="icon-thumbs-up-alt">' . __('已赞', 'salong') . '</i>(<span class="count">';
		if ($vote_count == 0) {
			$output .= '0';
		} else {
			$output .= $vote_count;
		}
		$output .= '</span>)</span>';
	} else {
		$output .= '<a href="#" data-post_id="' . $post_id . '" title="' . sprintf(__('喜欢该%s，请点赞！', 'salong'), esc_attr(post_name())) . '"><i class="icon-thumbs-up">' . __('赞', 'salong') . '</i>(<span class="count">';
		if ($vote_count == 0) {
			$output .= '0';
		} else {
			$output .= $vote_count;
		}
		$output .= '</span>)</a>';
	}
	return $output;
}
//创建函数输出文章列表HTML代码
function getPostLikeLinkList($post_id)
{
	global $salong;
	$vote_count = get_post_meta($post_id, "votes_count", true);
	if (hasAlreadyVoted($post_id)) {
		$output .= '<span title="' . __('您已经点赞了！', 'salong') . '" class="like alreadyvoted">';
		if (is_singular(array('video', 'gallery', 'product'))) {
			$output .= __('点赞：', 'salong');
		} else {
			$output .= '<i class="icon-thumbs-up-alt"></i>';
		}
	} else {
		$output .= '<span  title="' . sprintf(__('请先浏览本%s，再确定是否点赞！', 'salong'), esc_attr(post_name())) . '"class="like">';
		if (is_singular(array('video', 'gallery', 'product'))) {
			$output .= __('点赞：', 'salong');
		} else {
			$output .= '<i class="icon-thumbs-up"></i>';
		}
	}
	if ($vote_count == 0) {
		$output .= '0';
	} else {
		$output .= '<span class="count">' . $vote_count . '</span>';
	}
	$output .= '</span>';
	return $output;
}
///////////////////////////////////////点赞end
///////////////////////////////////////广告
//博客广告
function blog_ad()
{
	global $salong;
	if ($salong['ad_blog']) {
		echo '<article class="ad box';
		triangle();
		wow();
		echo '">';
		if (!wp_is_mobile()) {
			echo $salong['ad_blog'];
		} else {
			if ($salong['ad_blog_mobile']) {
				echo $salong['ad_blog_mobile'];
			} else {
				echo $salong['ad_blog'];
			}
		}
		echo '</article>';
	}
}
//分类列表1广告
function ad_cat1()
{
	global $salong;
	if ($salong['ad_cat1']) {
		echo '<section class="ad box';
		triangle();
		wow();
		echo '">';
		if (!wp_is_mobile()) {
			echo $salong['ad_cat1'];
		} else {
			if ($salong['ad_cat1_mobile']) {
				echo $salong['ad_cat1_mobile'];
			} else {
				echo $salong['ad_cat1'];
			}
		}
		echo '</section>';
	}
}
//分类列表2广告
function ad_cat2()
{
	global $salong;
	if ($salong['ad_cat2']) {
		echo '<section class="ad box';
		triangle();
		wow();
		echo '">';
		if (!wp_is_mobile()) {
			echo $salong['ad_cat2'];
		} else {
			if ($salong['ad_cat2_mobile']) {
				echo $salong['ad_cat2_mobile'];
			} else {
				echo $salong['ad_cat2'];
			}
		}
		echo '</section>';
	}
}
//分类列表3广告
function ad_cat3()
{
	global $salong;
	if ($salong['ad_cat3']) {
		echo '<section class="ad box';
		triangle();
		wow();
		echo '">';
		if (!wp_is_mobile()) {
			echo $salong['ad_cat3'];
		} else {
			if ($salong['ad_cat3_mobile']) {
				echo $salong['ad_cat3_mobile'];
			} else {
				echo $salong['ad_cat3'];
			}
		}
		echo '</section>';
	}
}
//文章内容上的广告
function ad_single()
{
	global $salong, $post;
	if (!get_post_meta($post->ID, "no_sidebar", true)) {
		if (!wp_is_mobile()) {
			if ($salong['ad_single']) {
				echo '<div class="ad ad-single">' . $salong['ad_single'] . '</div>';
			}
		} else {
			if ($salong['ad_single_mobile']) {
				echo '<div class="ad ad-single">' . $salong['ad_single_mobile'] . '</div>';
			} else {
				echo '<div class="ad ad-single">' . $salong['ad_single'] . '</div>';
			}
		}
	}
}
//相关文章下的广告
function ad_related()
{
	global $salong, $post;
	if ($salong['ad_related'] && !get_post_meta($post->ID, "no_sidebar", true)) {
		echo '<section class="ad box';
		triangle();
		wow();
		echo '">';
		if (!wp_is_mobile()) {
			echo $salong['ad_related'];
		} else {
			if ($salong['ad_related_mobile']) {
				echo $salong['ad_related_mobile'];
			} else {
				echo $salong['ad_related'];
			}
		}
		echo '</section>';
	}
}
///////////////////////////////////////广告end
//WOW动画标签
function wow()
{
	global $salong;
	if ($salong['switch_wow'] && !wp_is_mobile()) {
		echo ' wow bounceInUp';
	}
}
//三角图标
function triangle()
{
	global $salong;
	if ($salong['switch_triangle']) {
		echo ' triangle';
	}
}
// 网站图标
if (!function_exists('salong_favicon') && function_exists('genuine_allow_domain')) {
	function salong_favicon()
	{
		global $salong;
		$favicon = $salong['custom_favicon']['url'];
		$ios_favicon = $salong['custom_ios_favicon']['url'];
		if ($favicon) {
			echo '<link rel="shortcut icon" href="' . $favicon . '" />', "\n";
		}
		if ($ios_favicon) {
			echo '<link rel="apple-touch-icon" sizes="120*120" href="' . $ios_favicon . '" />', "\n";
		}
	}
	add_action('wp_head', 'salong_favicon');
}
// 获取文章第一张图片，这里获取的是图片的原始大小
function get_content_first_image($content)
{
	if ($content === false) {
		$content = get_the_content();
	}
	preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $content, $images);
	if ($images) {
		return $images[1][0];
	} else {
		return false;
	}
}
//为弹窗自动添加标签属性
add_filter('the_content', 'fancybox_replace');
function fancybox_replace($content)
{
	global $post;
	$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
	$replacement = '<a$1href=$2$3.$4$5 data-fancybox="gallery"$6>$7</a>';
	$content = preg_replace($pattern, $replacement, $content);
	return $content;
}
/* 设置自定义文章类型固定链接 */
if ($salong['switch_post_type_slug'] && function_exists('genuine_allow_domain')) {
	global $salong;
	$posttypes = array('bulletin' => 'bulletin', 'gallery' => 'gallery', 'video' => 'video', 'product' => 'product');
	add_filter('post_type_link', 'custom_salong_link', 1, 3);
	function custom_salong_link($link, $post = 0)
	{
		global $posttypes;
		if (in_array($post->post_type, array_keys($posttypes))) {
			global $salong;
			if ($salong['post_type_slug'] == 'Postname') {
				$postlink = 'post_name';
			} else {
				$postlink = 'ID';
			}
			return home_url($posttypes[$post->post_type] . '/' . $post->{$postlink} . '.html');
		} else {
			return $link;
		}
	}
	add_action('init', 'custom_salong_rewrites_init');
	function custom_salong_rewrites_init()
	{
		global $posttypes;
		foreach ($posttypes as $k => $v) {
			global $salong;
			if ($salong['post_type_slug'] == 'Postname') {
				add_rewrite_rule($v . '/([一-龥a-zA-Z0-9_-]+)?.html([\s\S]*)?$', 'index.php?post_type=' . $k . '&name=$matches[1]', 'top');
			} else {
				add_rewrite_rule($v . '/([0-9]+)?.html$', 'index.php?post_type=' . $k . '&p=$matches[1]', 'top');
			}
		}
	}
}
//////文章分页增加版
function salong_link_pages($args = '')
{
	$defaults = array('before' => '<p>' . __('分页', 'salong'), 'after' => '</p>', 'link_before' => '', 'link_after' => '', 'next_or_number' => 'number', 'nextpagelink' => __('下一页', 'salong'), 'previouspagelink' => __('上一页', 'salong'), 'pagelink' => '%', 'echo' => 1);
	$r = wp_parse_args($args, $defaults);
	$r = apply_filters('wp_link_pages_args', $r);
	extract($r, EXTR_SKIP);
	global $page, $numpages, $multipage, $more, $pagenow;
	$output = '';
	if ($multipage) {
		if ('number' == $next_or_number) {
			$output .= $before;
			for ($i = 1; $i < $numpages + 1; $i = $i + 1) {
				$j = str_replace('%', $i, $pagelink);
				$output .= ' ';
				if ($i != $page || !$more && $page == 1) {
					$output .= _wp_link_page($i);
					$output .= $link_before . $j . $link_after;
				} else {
					//加了个else语句，用来判断当前页，如果是的话输出下面的
					$output .= '<span class="page-numbers current">' . $j . '</span>';
				}
				//原本这里有一句，移到上面去了
				if ($i != $page || !$more && $page == 1) {
					$output .= '</a>';
				}
			}
			$output .= $after;
		} else {
			if ($more) {
				$output .= $before;
				$i = $page - 1;
				if ($i && $more && $previouspagelink) {
					//if里面的条件加了$previouspagelink也就是只有参数有“上一页”这几个字才显示
					$output .= _wp_link_page($i);
					$output .= $link_before . $previouspagelink . $link_after . '</a>';
				}
				$i = $page + 1;
				if ($i <= $numpages && $more && $nextpagelink) {
					//if里面的条件加了$nextpagelink也就是只有参数有“下一页”这几个字才显示
					$output .= _wp_link_page($i);
					$output .= $link_before . $nextpagelink . $link_after . '</a>';
				}
				$output .= $after;
			}
		}
	}
	if ($echo) {
		echo $output;
	}
	return $output;
}
//地图
function salong_map()
{
	require_once get_template_directory() . '/content/contact-map.php';
}
add_shortcode('map', 'salong_map');
//////面包屑
function salong_breadcrumbs()
{
	global $salong, $post;
	$delimiter = '&nbsp;' . $salong['delimiter'] . '&nbsp;';
	// 分隔符
	$before = '<span class="current">';
	// 在当前链接前插入
	$after = '</span>';
	// 在当前链接后插入
	if (!is_home() && !is_front_page() || is_paged()) {
		echo '<article class="crumbs">';
		global $post;
		$homeLink = home_url();
		echo ' <a itemprop="breadcrumb" href="' . $homeLink . '">' . __('首页', 'salong') . '</a>' . $delimiter . '';
		if (is_category()) {
			// 分类 存档
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_categories(array('include' => $thisCat, 'taxonomy' => 'any'));
			$thisPCat = $thisCat->parent;
			$parentCat = get_categories(array('include' => $thisPCat, 'taxonomy' => 'any'));
			$page_id = get_page_id_from_template('template-blog.php');
			echo '<a itemprop="breadcrumb" href="' . get_permalink($page_id) . '">' . get_the_title($page_id) . '</a>' . $delimiter;
			if ($thisCat->parent != 0) {
				$cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace('<a', '<a itemprop="breadcrumb"', $cat_code);
			}
			echo $before . '' . single_cat_title('', false) . '' . $after;
		} else {
			if (is_tax()) {
				// 视频分类 存档
				//得到当前分类
				$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				$parent = $term->parent;
				if (is_tax('video-cat') || is_tax('video-tag')) {
					$page_id = get_page_id_from_template('template-video.php');
				} else {
					if (is_tax('gallery-cat') || is_tax('gallery-tag')) {
						$page_id = get_page_id_from_template('template-gallery.php');
					}
				}
				echo '<a itemprop="breadcrumb" href="' . get_permalink($page_id) . '">' . get_the_title($page_id) . '</a>' . $delimiter;
				while ($parent) {
					$parents[] = $parent;
					$new_parent = get_term_by('id', $parent, get_query_var('taxonomy'));
					$parent = $new_parent->parent;
				}
				if (!empty($parents)) {
					$parents = array_reverse($parents);
					foreach ($parents as $parent) {
						$item = get_term_by('id', $parent, get_query_var('taxonomy'));
						$post_type_name = get_post_type();
						$cat_name = 'category';
						$url = get_bloginfo('url') . '/' . $post_type_name . '-' . $cat_name . '/' . $item->slug;
						echo '<a href="' . $url . '">' . $item->name . '</a>' . $delimiter;
					}
				}
				// Display the current term in the breadcrumb
				echo $before . $term->name . $after;
			} else {
				if (is_day()) {
					// 天 存档
					echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter . '';
					echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $delimiter . '';
					echo $before . get_the_time('d') . $after;
				} elseif (is_month()) {
					// 月 存档
					echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter . '';
					echo $before . get_the_time('F') . $after;
				} elseif (is_year()) {
					// 年 存档
					echo $before . get_the_time('Y') . $after;
				} elseif (is_single() && !is_attachment()) {
					// 文章
					if (is_singular('video')) {
						//视频文章
						global $salong;
						echo '<a itemprop="breadcrumb" href="' . get_page_link(get_page_id_from_template('template-video.php')) . '">' . get_page(get_page_id_from_template('template-video.php'))->post_title . '</a>' . $delimiter . '';
						echo the_terms($post->ID, 'video-cat', '') . $delimiter;
						echo $before . get_the_title() . $after;
					} else {
						if (is_singular('gallery')) {
							//画廊文章
							global $salong;
							echo '<a itemprop="breadcrumb" href="' . get_page_link(get_page_id_from_template('template-gallery.php')) . '">' . get_page(get_page_id_from_template('template-gallery.php'))->post_title . '</a>' . $delimiter . '';
							echo the_terms($post->ID, 'gallery-cat', '') . $delimiter;
							echo $before . get_the_title() . $after;
						} else {
							if (is_singular('product')) {
								//产品文章
								global $salong;
								echo '<a itemprop="breadcrumb" href="' . get_page_link(woocommerce_get_page_id('shop')) . '">' . get_page(woocommerce_get_page_id('shop'))->post_title . '</a>' . $delimiter . '';
								echo the_terms($post->ID, 'product_cat', '') . $delimiter;
								echo $before . get_the_title() . $after;
							} else {
								// 文章 post
								echo '<a itemprop="breadcrumb" href="' . get_page_link(get_page_id_from_template('template-blog.php')) . '">' . get_page(get_page_id_from_template('template-blog.php'))->post_title . '</a>' . $delimiter . '';
								$cat = get_the_category();
								$cat = $cat[0];
								$cat_code = get_category_parents($cat, TRUE, $delimiter);
								echo $cat_code = str_replace('<a', '<a itemprop="breadcrumb"', $cat_code);
								echo $before . get_the_title() . $after;
							}
						}
					}
				} elseif (is_attachment()) {
					// 附件
					$parent = get_post($post->post_parent);
					$cat = get_the_category($parent->ID);
					$cat = $cat[0];
					echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>' . $delimiter . '';
					echo $before . get_the_title() . $after;
				} else {
					if (is_page() && !$post->post_parent) {
						// 页面
						echo $before . get_the_title() . $after;
					} elseif (is_page() && $post->post_parent) {
						// 父级页面
						$parent_id = $post->post_parent;
						$breadcrumbs = array();
						while ($parent_id) {
							$page = get_page($parent_id);
							$breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
							$parent_id = $page->post_parent;
						}
						$breadcrumbs = array_reverse($breadcrumbs);
						foreach ($breadcrumbs as $crumb) {
							echo $crumb . '' . $delimiter . '';
						}
						echo $before . get_the_title() . $after;
					} elseif (is_search()) {
						// 搜索结果
						echo $before;
						printf(__('%s 的搜索结果', 'salong'), get_search_query());
						echo $after;
					} elseif (is_tag()) {
						//标签 存档
						echo $before;
						printf(__('%s 的标签存档', 'salong'), single_tag_title('', false));
						echo $after;
					} elseif (is_author()) {
						// 作者存档
						global $author;
						$userdata = get_userdata($author);
						echo $before;
						printf(__('%s 的个人中心', 'salong'), $userdata->display_name);
						echo $after;
					} elseif (is_404()) {
						// 404 页面
						echo $before;
						__('404公益页面', 'salong');
						echo $after;
					}
				}
			}
		}
		if (get_query_var('paged')) {
			// 分页
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				echo sprintf(__('（第%s页）', 'salong'), get_query_var('paged'));
			}
		}
		echo '</article>';
	}
}
//文章目录
function article_index($content)
{
	$index = '';
	$ol = '';
	$arr = array();
	$pattern = '/<h([2-6]).*?\>(.*?)<\/h[2-6]>/is';
	global $post, $salong;
	if (preg_match_all($pattern, $content, $arr) && get_post_meta($post->ID, "catalogue", true) && !wp_is_mobile()) {
		$count = count($arr[0]);
		foreach ($arr[1] as $k => $v) {
			//添加列表头
			if ($k <= 0) {
				$index = '<ol class="catalogue_list">';
			} else {
				if ($v > $arr[1][$k - 1]) {
					if ($v - $arr[1][$k - 1] == 1) {
						$index .= '<ol>';
					} elseif ($v == $arr[1][$k - 1]) {
					} else {
						$index .= __('文章目录层级不合法', 'salong');
						return false;
					}
				}
			}
			$title = strip_tags($arr[2][$k]);
			$content = str_replace($arr[0][$k], '<h' . $v . ' id="index-' . $k . '">' . $title . '</h' . $v . '>', $content);
			$index .= '<li class="h' . $v . '"><a rel="contents chapter" href="#index-' . $k . '">' . $title . '</a></li>';
			//输出本层li
			//列表封闭规则
			if ($k < $count - 1) {
				if ($v > $arr[1][$k + 1]) {
					//当前层大于下一层，本层结束，封底
					$c = $v - $arr[1][$k + 1];
					for ($i = 0; $i < $c; $i++) {
						$ol .= '</ol>';
						$index .= $ol;
						$ol = '';
					}
				}
			} else {
				$index .= '</ol>';
			}
		}
		$index = '<nav class="post_catalogue box" role="navigation"><h3>' . __('文章目录', 'salong') . '</h3>' . $index . '</nav>';
		$content = $content . $index;
	}
	return $content;
}
add_filter("the_content", "article_index");
//获取优酷视频缩略图
function getSslPage($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
//该函数获取优酷视频缩略图
function get_youku_video_thumb()
{
	global $post;
	global $salong;
	$client_id = $salong['client_id'];
	$values = get_post_meta($post->ID, "youku_id", true);
	if ($salong['switch_update_youku_thumb']) {
		//开发者的client_id，每个ID都有调用次数限制，所以这里可以申请多个使用
		//下面一行的2个 client_id 是无效的，请自己申请后，填入
		if ($values) {
			//获取到视频ID后，通过API读取缩略图
			$link = "https://api.youku.com/videos/show.json?video_id={$values}&client_id={$client_id}";
			$cexecute = getSslPage($link);
			if ($cexecute) {
				//转换内容以供php读取
				$result = json_decode($cexecute, true);
				$json = $result['data'][0];
				//调用大缩略图 bigThumbnail
				$video_thumb = $result['bigThumbnail'];
				if ($video_thumb != '') {
					//将调用都的缩略图地址存储到文章字段中，以供后面调用，不需反复调用API
					update_post_meta($post->ID, '_youku_thumb', $video_thumb);
				}
			}
		} else {
			//文章没有插入优酷视频的话，使用一个默认图片地址
			global $salong;
			$video_thumb = $salong['default_thumb']['url'];
		}
	} else {
		//检查该文章是否已经存储过优酷缩略图地址
		if (get_post_meta($post->ID, '_youku_thumb', true)) {
			//已存储，就直接调用
			$video_thumb = get_post_meta($post->ID, '_youku_thumb', true);
		} else {
			//开发者的client_id，每个ID都有调用次数限制，所以这里可以申请多个使用
			//下面一行的2个 client_id 是无效的，请自己申请后，填入
			if ($values) {
				//获取到视频ID后，通过API读取缩略图
				$link = "https://api.youku.com/videos/show.json?video_id={$values}&client_id={$client_id}";
				$cexecute = getSslPage($link);
				if ($cexecute) {
					//转换内容以供php读取
					$result = json_decode($cexecute, true);
					$json = $result['data'][0];
					//调用大缩略图 bigThumbnail
					$video_thumb = $result['bigThumbnail'];
					if ($video_thumb != '') {
						//将调用都的缩略图地址存储到文章字段中，以供后面调用，不需反复调用API
						update_post_meta($post->ID, '_youku_thumb', $video_thumb);
					}
				}
			} else {
				//文章没有插入优酷视频的话，使用一个默认图片地址
				global $salong;
				$video_thumb = $salong['default_thumb']['url'];
			}
		}
	}
	return $video_thumb;
}
//开启友情链接
add_filter('pre_option_link_manager_enabled', '__return_true');
//canonical标签
function salong_archive_link($paged = true)
{
	$link = false;
	if (is_front_page()) {
		$link = home_url('/');
	} else {
		if (is_home() && "page" == get_option('show_on_front')) {
			$link = get_permalink(get_option('page_for_posts'));
		} else {
			if (is_tax() || is_tag() || is_category()) {
				$term = get_queried_object();
				$link = get_term_link($term, $term->taxonomy);
			} else {
				if (is_post_type_archive()) {
					$link = get_post_type_archive_link(get_post_type());
				} else {
					if (is_author()) {
						$link = get_author_posts_url(get_query_var('author'), get_query_var('author_name'));
					} else {
						if (is_archive()) {
							if (is_date()) {
								if (is_day()) {
									$link = get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
								} else {
									if (is_month()) {
										$link = get_month_link(get_query_var('year'), get_query_var('monthnum'));
									} else {
										if (is_year()) {
											$link = get_year_link(get_query_var('year'));
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	if ($paged && $link && get_query_var('paged') > 1) {
		global $wp_rewrite;
		if (!$wp_rewrite->using_permalinks()) {
			$link = add_query_arg('paged', get_query_var('paged'), $link);
		} else {
			$link = user_trailingslashit(trailingslashit($link) . trailingslashit($wp_rewrite->pagination_base) . get_query_var('paged'), 'archive');
		}
	}
	return $link;
}
//外链自动nofollow
add_filter('the_content', 'v13_seo_wl');
function v13_seo_wl($content)
{
	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
	if (preg_match_all("/{$regexp}/siU", $content, $matches, PREG_SET_ORDER)) {
		if (!empty($matches)) {
			$srcUrl = get_option('siteurl');
			for ($i = 0; $i < count($matches); $i++) {
				$tag = $matches[$i][0];
				$tag2 = $matches[$i][0];
				$url = $matches[$i][0];
				$noFollow = '';
				$pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
				preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
				if (count($match) < 1) {
					$noFollow .= ' rel="nofollow" ';
				}
				$pos = strpos($url, $srcUrl);
				if ($pos === false) {
					$tag = rtrim($tag, '>');
					$tag .= $noFollow . '>';
					$content = str_replace($tag2, $tag, $content);
				}
			}
		}
	}
	$content = str_replace(']]>', ']]>', $content);
	return $content;
}
//获取用户积分
function salong_points_rewards()
{
	global $wc_points_rewards;
	if (function_exists('woocommerce_points_rewards_my_points')) {
		echo '<section class="my_points">';
		echo woocommerce_points_rewards_my_points();
		echo '</section>';
	} else {
		echo __('请安装WooCommerce Points and Rewards插件', 'salong');
	}
}
add_shortcode('points_rewards', 'salong_points_rewards');
//取消描述中的p标签
function deletehtml($description)
{
	$description = trim($description);
	$description = strip_tags($description, "");
	return $description;
}
add_filter('category_description', 'deletehtml');
//编辑器TinyMCE增强
function enable_more_buttons($buttons)
{
	$buttons[] = 'hr';
	$buttons[] = 'del';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'anchor';
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter("mce_buttons_3", "enable_more_buttons");
// 禁止后台加载谷歌字体
function wp_remove_open_sans_from_wp_core()
{
	wp_deregister_style('open-sans');
	wp_register_style('open-sans', false);
	wp_enqueue_style('open-sans', '');
}
add_action('init', 'wp_remove_open_sans_from_wp_core');
//解决头像被墙
if (is_admin()) {
	function get_ssl_avatar($avatar)
	{
		$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/', '<img src="https://secure.gravatar.com/avatar/$1?s=32" class="avatar avatar-32" height="32" width="32">', $avatar);
		return $avatar;
	}
} else {
	function get_ssl_avatar($avatar)
	{
		$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/', '<img src="https://secure.gravatar.com/avatar/$1?s=100" class="avatar avatar-100" height="100" width="100">', $avatar);
		return $avatar;
	}
}
add_filter('get_avatar', 'get_ssl_avatar');
// 禁止全英文和日文评论
function BYMT_comment_post($incoming_comment)
{
	$pattern = '/[一-龥]/u';
	$jpattern = '/[ぁ-ん]+|[ァ-ヴ]+/u';
	if (!preg_match($pattern, $incoming_comment['comment_content'])) {
		err("写点汉字吧，博主英文过了四级，但还是不认识英文！Please write some chinese words！");
	}
	if (preg_match($jpattern, $incoming_comment['comment_content'])) {
		err("日文滚粗！Japanese Get out！日本語出て行け！");
	}
	return $incoming_comment;
}
add_filter('preprocess_comment', 'BYMT_comment_post');
// 针对特定字符留言直接屏蔽
function in_comment_post_like($string, $array)
{
	foreach ($array as $ref) {
		if (strstr($string, $ref)) {
			return true;
		}
	}
	return false;
}
function drop_bad_comments()
{
	if (!empty($_POST['comment'])) {
		$post_comment_content = $_POST['comment'];
		$lower_case_comment = strtolower($_POST['comment']);
		$bad_comment_content = array('www.', '.com', '.cn', '.net', '.html', '.php', 'http:');
		if (in_comment_post_like($lower_case_comment, $bad_comment_content)) {
			$comment_box_text = wordwrap(trim($post_comment_content), 80, "\n  ", true);
			$txtdrop = fopen('/var/log/httpd/wp_post-logger/nullamatix.com-text-area_dropped.txt', 'a');
			fwrite($txtdrop, "  --------------\n  [COMMENT] = " . $post_comment_content . "\n  --------------\n");
			fwrite($txtdrop, "  [SOURCE_IP] = " . $_SERVER['REMOTE_ADDR'] . " @ " . date("F j, Y, g:i a") . "\n");
			fwrite($txtdrop, "  [USERAGENT] = " . $_SERVER['HTTP_USER_AGENT'] . "\n");
			fwrite($txtdrop, "  [REFERER  ] = " . $_SERVER['HTTP_REFERER'] . "\n");
			fwrite($txtdrop, "  [FILE_NAME] = " . $_SERVER['SCRIPT_NAME'] . " - [REQ_URI] = " . $_SERVER['REQUEST_URI'] . "\n");
			fwrite($txtdrop, '--------------**********------------------' . "\n");
			header("HTTP/1.1 406 Not Acceptable");
			header("Status: 406 Not Acceptable");
			header("Connection: Close");
			wp_die(__('砰 砰 砰…'));
		}
	}
}
add_action('init', 'drop_bad_comments');
//文章浏览统计
function getPostViews($postID)
{
	$count_key = 'views';
	//自定义域
	$count = get_post_meta($postID, $count_key, true);
	if ($count == '') {
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return "0";
	}
	return $count . '';
}
function setPostViews($postID)
{
	$count_key = 'views';
	//自定义域
	$count = get_post_meta($postID, $count_key, true);
	if ($count == '') {
		$count = 0;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
	} else {
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}
//隐藏版本信息
function change_footer_admin()
{
	return '';
}
add_filter('admin_footer_text', 'change_footer_admin', 9999);
function change_footer_version()
{
	return '&nbsp;';
}
add_filter('update_footer', 'change_footer_version', 9999);
//使用SMTP发送邮件
global $salong;
if ($salong['switch_smtp']) {
	function mail_smtp($phpmailer)
	{
		global $salong;
		$phpmailer->isHTML(true);
		$phpmailer->IsSMTP();
		$phpmailer->Mailer = "SMTP";
		$phpmailer->FromName = sanitize_text_field($salong['smtp_name']);
		//发件人
		$phpmailer->From = sanitize_text_field($salong['smtp_username']);
		//你的邮箱
		$phpmailer->AddReplyTo($phpmailer->From, $phpmailer->FromName);
		$phpmailer->Sender = $phpmailer->From;
		$phpmailer->Username = sanitize_text_field($salong['smtp_username']);
		//邮箱账户
		$phpmailer->Password = sanitize_text_field($salong['smtp_password']);
		//输入你对应的邮箱密码，这里使用了*代替
		$phpmailer->Host = sanitize_text_field($salong['smtp_host']);
		//修改为你使用的SMTP服务器
		$phpmailer->Port = intval($salong['smtp_port']);
		//SMTP端口，开启了SSL加密
		$phpmailer->SMTPAuth = true;
		if ($salong['switch_secure']) {
			$phpmailer->SMTPSecure = 'ssl';
		}
	}
	add_action('phpmailer_init', 'mail_smtp');
}
//禁止代码标点转换
remove_filter('the_content', 'wptexturize');
//判断是否在微信中打开网页
function salong_is_weixin()
{
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		return true;
	}
	return false;
}
//获取模板页面ID
function get_page_id_from_template($template)
{
	global $wpdb;
	// 多个页面使用同一个模板我就没辙了
	$page_id = $wpdb->get_var($wpdb->prepare("SELECT `post_id`
	                              FROM `{$wpdb->postmeta}`, `{$wpdb->posts}`
	                              WHERE `post_id` = `ID`
	                                    AND `post_status` = 'publish'
	                                    AND `meta_key` = '_wp_page_template'
	                                    AND `meta_value` = %s
	                                    LIMIT 1;", $template));
	return $page_id;
}
//添加新窗口打开链接
function new_open_link()
{
	global $salong;
	if ($salong['switch_new_open_link']) {
		$item .= ' target="_blank"';
	}
	return $item;
}
//前台登录与注册简码////////////////////////////////////////////////////
//前台登录
function salong_frontend_login()
{
	global $salong;
	$register_page = wp_registration_url();
	$items .= '<article class="front_form">';
	if (is_page_template('template-login.php')) {
		$items .= '<h1>' . get_the_title() . '</h1>';
	}
	$items .= '<form id="login" action="login" method="post">';
	$items .= '<p class="status"></p>';
	$items .= wp_nonce_field('ajax-login-nonce', 'security');
	$items .= '<p><label>' . __('用户名或邮箱', 'salong') . '</label><input id="username" type="text" class="required" name="username" placeholder="' . __('请输入用户名或邮箱！', 'salong') . '"></p>';
	$items .= '<p><label>' . __('密码', 'salong') . '</label><input id="password" type="password" class="required" name="password" placeholder="' . __('请输入密码！', 'salong') . '"></p>';
	$items .= '<p><input class="submit_button" type="submit" value="' . __('登录', 'salong') . '"></p>';
	$items .= '</form>';
	if (get_option('users_can_register') == 1) {
		if ($salong['switch_social_login']) {
			$items .= '<section class="social_login">';
			if ($salong['qqlogin_link']) {
				$items .= '<a href="' . $salong['qqlogin_link'] . '" class="qq" rel="nofollow"><i class="icon-qq"></i></a>';
			}
			if ($salong['switch_weibo_login']) {
				$items .= '<a href="' . weibo_oauth_url() . '" class="weibo" rel="nofollow"><i class="icon-weibo"></i></a>';
			}
			if (salong_is_weixin() && $salong['wechatinnerlogin_link']) {
				$items .= '<a href="' . $salong['wechatinnerlogin_link'] . '" class="wechat" rel="nofollow"><i class="icon-wechat"></i></a>';
			} else {
				if ($salong['wechatlogin_link']) {
					$items .= '<a href="' . $salong['wechatlogin_link'] . '" class="wechat" rel="nofollow"><i class="icon-wechat"></i></a>';
				}
			}
			$items .= '</section>';
		}
		$items .= '<section class="login_reg">';
		$items .= '<a href="' . $register_page . '" title="' . __('注册一个新的帐户', 'salong') . '"><i class="icon-login"></i>' . __('注册', 'salong') . '</a>｜<a href="' . wp_lostpassword_url() . '" title="' . __('忘记密码', 'salong') . '"><i class="icon-lock"></i>' . __('忘记密码', 'salong') . '</a>';
		$items .= '</section>';
	}
	$items .= '</article>';
	return $items;
}
add_shortcode('frontend_login', 'salong_frontend_login');
//前台注册
function salong_frontend_register()
{
	global $salong;
	$login_page = wp_login_url();
	$items .= '<article class="front_form">';
	if (is_page_template('template-login.php')) {
		$items .= '<h1>' . get_the_title() . '</h1>';
	}
	$items .= '<form id="register" action="register" method="post">';
	$items .= '<p class="status"></p>';
	$items .= wp_nonce_field('ajax-register-nonce', 'signonsecurity');
	$items .= '<p><label>' . __('用户名', 'salong') . '</label><input id="signonname" type="text" name="signonname" class="required" placeholder="' . __('请输入用户名！', 'salong') . '"></p>';
	$items .= '<p><label>' . __('邮箱', 'salong') . '</label><input id="email" type="text" class="required email" name="email" placeholder="' . __('请输入邮箱！', 'salong') . '"></p>';
	$items .= '<p><label>' . __('密码', 'salong') . '</label><input id="signonpassword" type="password" class="required" name="signonpassword" placeholder="' . __('请输入密码！', 'salong') . '"></p>';
	$items .= '<p><label>' . __('重复密码', 'salong') . '</label><input type="password" id="password2" class="required" name="password2" placeholder="' . __('重复密码！', 'salong') . '"></p>';
	$items .= '<p><input class="submit_button" type="submit" value="' . __('注册', 'salong') . '"></p>';
	$items .= '</form>';
	$items .= '<section class="front_btn">';
	$items .= sprintf(__('已经有帐户？<a href="%s">请登录</a>', 'salong'), $login_page);
	$items .= sprintf(__('<a href="%s">忘记密码</a>', 'salong'), wp_lostpassword_url());
	$items .= '</section></article>';
	return $items;
}
add_shortcode('frontend_register', 'salong_frontend_register');
//编辑资料
function salong_edit_porfile()
{
	global $salong, $wp_query, $current_user;
	$user_id = $current_user->ID;
	//当前用户 ID
	$user_name = $current_user->display_name;
	$user_url = $current_user->user_url;
	$user_email = $current_user->user_email;
	$description = $current_user->description;
	if (isset($_POST['update']) && wp_verify_nonce(trim($_POST['_wpnonce']), 'check-nonce')) {
		$message = __('没有发生变化', 'salong');
		$update = sanitize_text_field($_POST['update']);
		if ($update == 'info') {
			$update_user_id = wp_update_user(array('ID' => $user_id, 'display_name' => sanitize_text_field($_POST['display_name']), 'user_url' => esc_url($_POST['url']), 'description' => $_POST['description']));
			if (!is_wp_error($update_user_id)) {
				$message = __('基本信息已更新', 'salong');
			}
		}
		if ($update == 'pass') {
			$data = array();
			$data['ID'] = $user_id;
			$data['user_email'] = sanitize_text_field($_POST['email']);
			if (!empty($_POST['pass1']) && !empty($_POST['pass2']) && $_POST['pass1'] === $_POST['pass2']) {
				$data['user_pass'] = sanitize_text_field($_POST['pass1']);
			}
			$user_id = wp_update_user($data);
			if (!is_wp_error($user_id)) {
				$message = __('账号修改已更新', 'salong');
			}
		}
		$message .= ' <a href="">' . __('点击刷新', 'salong') . '</a>';
	}
	$items .= '<section class="form_secton">';
	if ($message) {
		$items .= '<p class="hint">' . $message . '</p>';
	}
	$items .= '<form id="info-form" class="contribute_form" role="form" method="POST" action="">';
	$items .= '<input type="hidden" name="update" value="info">';
	$items .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce('check-nonce') . '">';
	$items .= '<section class="page-header"><h3>' . __('基本信息', 'salong') . '</h3></section>';
	$items .= '<p><label for="display_name">' . __('昵称 (必填)', 'salong') . '</label><input type="text" id="display_name" name="display_name" value="' . $user_name . '" required></p>';
	$items .= '<p><label for="url">' . __('网站', 'salong') . '</label><input type="text" id="url" name="url" value="' . $user_url . '"></p>';
	$items .= '<p><label for="description">' . __('个人说明', 'salong') . '</label><textarea rows="3" name="description" id="description">' . $description . '</textarea></p>';
	$items .= '<p><input type="submit" value="保存更改" class="submit" /></p>';
	$items .= '</form><hr>';
	$items .= '<form id="pass-form" class="contribute_form" role="form" method="post">';
	$items .= '<input type="hidden" name="update" value="pass">';
	$items .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce('check-nonce') . '">';
	$items .= '<section class="page-header"><h3 id="pass">' . __('账号修改', 'salong') . '</h3></section>';
	$items .= '<p><label for="email">' . __('电子邮件 (必填)', 'salong') . '</label><input type="text" id="email" name="email" value="' . $user_email . '" required></p>';
	$items .= '<p><label for="pass1">' . __('新密码', 'salong') . '</label><input type="password" id="pass1" name="pass1"><span class="help-block">' . __('如果需要修改密码，请输入新的密码，不改则留空。', 'salong') . '</span></p>';
	$items .= '<p><label for="pass2">' . __('重复新密码', 'salong') . '</label><input type="password" id="pass2" name="pass2"><span class="help-block">' . __('再输入一遍新密码，提示：密码最好至少包含7个字符，为了保证密码强度，使用大小写字母、数字和符号结合。', 'salong') . '</span></p>';
	$items .= '<p><input type="submit" value="保存更改" class="submit" /></p>';
	$items .= '</form>';
	$items .= '</section>';
	return $items;
}
add_shortcode('edit_porfile', 'salong_edit_porfile');
//前台登录与注册简码////////////////////////////////////////////////////end
//用户文章简码////////////////////////////////////////////////////
function salong_user_posts($atts)
{
	global $salong, $current_user, $post, $wp_query;
	$user_id = $current_user->ID;
	//当前用户 ID
	extract(shortcode_atts(array("post_type" => 'post'), $atts));
	$get_trashed = $_GET['trashed'];
	$get_ids = $_GET['ids'];
	$current_url = get_permalink($wp_query->post->ID);
	//当前页面链接
	if ($post_type == 'post') {
		$post_count = count_user_posts($user_id, 'post');
		$post_name = __('文章', 'salong');
		$taxonomy_name = 'category';
	} else {
		if ($post_type == 'gallery') {
			$post_count = count_user_posts($user_id, 'gallery');
			$post_name = __('画廊', 'salong');
			$taxonomy_name = 'gallery-cat';
		} else {
			if ($post_type == 'video') {
				$post_count = count_user_posts($user_id, 'video');
				$post_name = __('视频', 'salong');
				$taxonomy_name = 'video-cat';
			} else {
				if ($post_type == 'product') {
					$post_count = count_user_posts($user_id, 'product');
					$post_name = __('产品', 'salong');
					$taxonomy_name = 'product_cat';
				}
			}
		}
	}
	echo '<section class="user_post">';
	echo sprintf(__('<p class="infobox">您已发布%s篇%s</p>', 'salong'), $post_count, $post_name);
	if ($get_trashed == 1) {
		echo sprintf(__('<p class="warningbox">您已成功删除%s：%s，<a href="%s">点击刷新</a></p>', 'salong'), $post_name, get_the_title($get_ids), $current_url);
	}
	echo '<ul class="ajaxposts">';
	$paged = $page = intval(get_query_var('paged'));
	$args = array('post_type' => $post_type, 'ignore_sticky_posts' => 1, 'paged' => $paged, 'author' => $user_id, 'post_status' => array('publish', 'pending', 'draft'));
	query_posts($args);
	if (have_posts()) {
		while (have_posts()) {
			the_post();
			//文章状态
			$post_status = $post->post_status;
			if ($post_status === 'draft') {
				$status = __('草稿', 'salong');
			} else {
				if ($post_status === 'pending') {
					$status = __('审核中', 'salong');
				} else {
					if ($post_status === 'publish') {
						$status = __('已发布', 'salong');
					}
				}
			}
			//自定义文章草稿或审核中文章链接
			if ($post_status === 'draft' || $post_status === 'pending') {
				if ($post_type == 'gallery' || $post_type == 'video' || $post_type == 'product') {
					$post_url = get_home_url() . '/?post_type=' . $post_type . '&p=' . $post->ID;
				} else {
					$post_url = get_the_permalink();
				}
			} else {
				$post_url = get_the_permalink();
			}
			echo '<li class="ajaxpost">';
			echo '<article class="user_post_main">';
			echo '<h2><a href="' . $post_url . '" title="' . get_the_title() . '"' . new_open_link() . '>' . get_the_title() . '</a></h2>';
			echo '<span class="post_status">' . $status . '</span>';
			echo '<div class="postinfo">';
			echo '<span class="category">';
			the_terms($post->ID, $taxonomy_name, '');
			echo '</span>';
			echo '<span class="date">' . get_the_date() . '</span>';
			echo '<span class="view"><i class="icon-eye"></i>' . getPostViews(get_the_ID()) . '</span>';
			if (current_user_can('level_2') && $salong['switch_edit_delete_post']) {
				echo '<span class="edit"><a href="' . get_edit_post_link() . '"' . new_open_link() . '><i class="icon-edit-1"></i></a></span>';
				?>
        <span class="delete"><a onclick="return confirm('<?php 
				echo sprintf(__('确定删除该 %s', 'salong'), $post_name);
				?>
')" href="<?php 
				echo get_delete_post_link($post->ID);
				?>
"><i class="icon-trash-empty"></i></a></span>
    <?php 
			}
			echo '</article>';
			echo '</li>';
		}
		echo the_posts_pagination(array('mid_size' => 1, 'prev_text' => __('上一页', 'salong'), 'next_text' => __('下一页', 'salong')));
	} else {
		echo '<p>';
		echo __('非常抱歉，没有相关文章。');
		echo '</p>';
	}
	echo '</ul>';
	wp_reset_query();
	echo '</section>';
}
add_shortcode('user_posts', 'salong_user_posts');
//用户文章简码////////////////////////////////////////////////////end
//投稿简码////////////////////////////////////////////////////
function salong_contribute_post($atts)
{
	global $salong, $current_user, $post, $wp_query, $wpdb;
	$user_id = $current_user->ID;
	//当前用户 ID
	$user_name = $current_user->display_name;
	$user_email = $current_user->user_email;
	$user_url = $current_user->user_url;
	$current_url = get_permalink($wp_query->post->ID);
	//当前页面链接
	extract(shortcode_atts(array("post_type" => 'post'), $atts));
	if ($post_type == 'post') {
		$post_name = __('文章', 'salong');
		$taxonomy_name = 'category';
		$tg_max = $salong['post_tg_max'];
		$tg_min = $salong['post_tg_min'];
	} else {
		if ($post_type == 'gallery') {
			$post_name = __('画廊', 'salong');
			$taxonomy_name = 'gallery-cat';
			$tg_max = $salong['gallery_tg_max'];
			$tg_min = $salong['gallery_tg_min'];
		} else {
			if ($post_type == 'video') {
				$post_name = __('视频', 'salong');
				$taxonomy_name = 'video-cat';
				$tg_max = $salong['video_tg_max'];
				$tg_min = $salong['video_tg_min'];
			}
		}
	}
	if (!isset($_SESSION)) {
		session_start();
		session_regenerate_id(TRUE);
	}
	if (isset($_POST['tougao_form']) && $_POST['tougao_form'] == 'send') {
		// 表单变量初始化
		$name = isset($_POST['tougao_authorname']) ? trim(htmlspecialchars($_POST['tougao_authorname'], ENT_QUOTES)) : '';
		$email = isset($_POST['tougao_authoremail']) ? trim(htmlspecialchars($_POST['tougao_authoremail'], ENT_QUOTES)) : '';
		$blog = isset($_POST['tougao_authorblog']) ? trim(htmlspecialchars($_POST['tougao_authorblog'], ENT_QUOTES)) : '';
		$from_name = isset($_POST['tougao_from_name']) ? trim(htmlspecialchars($_POST['tougao_from_name'], ENT_QUOTES)) : '';
		$from_link = isset($_POST['tougao_from_link']) ? trim(htmlspecialchars($_POST['tougao_from_link'], ENT_QUOTES)) : '';
		if ($post_type == 'video') {
			$youku_id = isset($_POST['tougao_youku_id']) ? trim(htmlspecialchars($_POST['tougao_youku_id'], ENT_QUOTES)) : '';
			$video_url = isset($_POST['tougao_video_url']) ? trim(htmlspecialchars($_POST['tougao_video_url'], ENT_QUOTES)) : '';
		}
		if ($post_type == 'gallery') {
			$slides = isset($_POST['tougao_slides']) ? trim(htmlspecialchars($_POST['tougao_slides'], ENT_QUOTES)) : '';
		}
		$title = isset($_POST['tougao_title']) ? trim(htmlspecialchars($_POST['tougao_title'], ENT_QUOTES)) : '';
		$category = isset($_POST['term_id']) ? (int) $_POST['term_id'] : 0;
		$content = isset($_POST['tougao_content']) ? $_POST['tougao_content'] : '';
		$last_post = $wpdb->get_var("SELECT `post_date` FROM `{$wpdb->posts}` ORDER BY `post_date` DESC LIMIT 1");
		$post_content = '昵称:' . $name . '<br />Email:' . $email . '<br />博客:' . $blog . '<br />内容:<br />' . $content;
		$tougao = array('post_title' => $title, 'post_content' => $post_content, 'post_author' => $user_id, 'post_type' => $post_type, 'ping_status' => 'closed', 'post_status' => 'pending', 'post_category' => array($category));
		if (empty($_POST['captcha_code']) || empty($_SESSION['salong_lcr_secretword']) || trim(strtolower($_POST['captcha_code'])) != $_SESSION['salong_lcr_secretword']) {
			echo '<span class="errorbox">' . sprintf(__('验证码不正确，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $current_url) . '</span>';
		} else {
			if (date_i18n('U') - strtotime($last_post) < $salong['tg_time']) {
				echo '<span class="warningbox">' . __('您投稿也太勤快了吧，先歇会儿！', 'salong') . '</span>';
			} else {
				if (empty($name) || mb_strlen($name) > 20) {
					echo '<span class="warningbox">' . sprintf(__('昵称必须填写，且长度不得超过20字，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $current_url) . '</span>';
				} else {
					if (empty($email) || strlen($email) > 60 || !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
						echo '<span class="warningbox">' . sprintf(__('Email必须填写，且长度不得超过60字，必须符合Email格式，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $current_url) . '</span>';
					} else {
						if (empty($title) || mb_strlen($title) > 100) {
							echo '<span class="warningbox">' . sprintf(__('标题必须填写，且长度不得超过100字，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $current_url) . '</span>';
						} else {
							if (empty($content)) {
								echo '<span class="warningbox">' . sprintf(__('内容必须填写，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $current_url) . '</span>';
							} else {
								if (mb_strlen($content) > $tg_max) {
									echo '<span class="warningbox">' . sprintf(__('内容长度不得超过%s字，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $tg_max, $current_url) . '</span>';
								} else {
									if (mb_strlen($content) < $tg_min) {
										echo '<span class="warningbox">' . sprintf(__('内容长度不得少于%s字，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $tg_min, $current_url) . '</span>';
									} else {
										if ($post_type == 'video' && empty($youku_id) && empty($video_url)) {
											echo '<span class="warningbox">' . sprintf(__('优酷视频 ID 或其它视频链接必须输入一个，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $current_url) . '</span>';
										} else {
											if ($post_type == 'video' && $youku_id && $video_url) {
												echo '<span class="warningbox">' . sprintf(__('优酷视频 ID 或其它视频链接只能输入一个，重新输入或者<a href="%s">点击刷新</a>', 'salong'), $current_url) . '</span>';
											} else {
												if ($tougao != 0) {
													// 将文章插入数据库
													$status = wp_insert_post($tougao);
													if ($post_type == 'video') {
														if (!empty($youku_id)) {
															add_post_meta($status, 'youku_id', $youku_id, true);
														}
														if (!empty($video_url)) {
															add_post_meta($status, 'video_url', $video_url, true);
														}
													}
													if ($post_type == 'gallery') {
														add_post_meta($status, 'slides', $slides, true);
													}
													if (!empty($from_name) && !empty($from_link)) {
														add_post_meta($status, 'from_name', $from_name, true);
														add_post_meta($status, 'from_link', $from_link, true);
													}
													//添加自定义分类
													wp_set_object_terms($status, $category, $taxonomy_name);
													// 投稿成功给博主发送邮件
													// somebody#example.com替换博主邮箱
													// My subject替换为邮件标题，content替换为邮件内容
													wp_mail(get_option('admin_email'), get_option('blogname') . __('投稿', 'salong'), get_option('blogname') . __('有投稿了，快去看看！', 'salong'));
													// 其中 salong_tougao_email 是自定义栏目的名称
													add_post_meta($status, 'salong_tougao_email', $email, TRUE);
													echo '<span class="successbox">' . __('投稿成功！感谢投稿！', 'salong') . '</span>';
												} else {
													echo '<span class="errorbox">' . __('投稿失败!', 'salong') . '</span>';
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	echo '<form class="contribute_form" method="post" action="' . $current_url . '">';
	echo '<p><label for="tougao_title"><b>*</b>' . __('文章标题', 'salong') . '</label><input type="text" value="" id="tougao_title" name="tougao_title" placeholder="' . __('请输入文章标题', 'salong') . '" required /><span>' . sprintf(__('标题长度不得超过%s字。', 'salong'), 100) . '</span></p>';
	echo '<p><label for="tougao_category"><b>*</b>' . __('文章分类', 'salong') . '</label>';
	wp_dropdown_categories('hide_empty=0&id=tougao_category&show_count=1&hierarchical=1&taxonomy=' . $taxonomy_name . '&name=term_id&id=term_id');
	echo '</p>';
	echo '<p>' . wp_editor(wpautop($post_content), 'tougao_content', array('media_buttons' => true, 'quicktags' => true, 'editor_class' => 'form-control')) . '<span>' . sprintf(__('内容必须填写，且长度不得超过 %s 字，不得少于 %s 字。', 'salong'), $tg_max, $tg_min) . '</span></p><hr>';
	if ($post_type == 'gallery') {
		echo '<p><label for="tougao_slides"><b>*</b>' . __('幻灯片图片', 'salong') . '</label><textarea name="tougao_slides" id="tougao_slides" cols="40" rows="6" required tabindex="4"></textarea><span>' . sprintf(__('输入图片链接，一行一个，如果需要为图片添加说明，格式：%s/1.jpg|%s图片说明，同样是一行一个。注意：“|”是图片链接与说明的分隔线，为英文输入法下的竖线。<br>图片可以通过『添加媒体』按钮，上传图片到媒体库，复制图片地址到此就可以。', 'salong'), get_home_url(), get_bloginfo('name')) . '</span></p><hr>';
	}
	if ($post_type == 'video') {
		echo '<p class="video_hint"><label for="tougao_slides">' . __('视频说明：', 'salong') . '</label><span>' . __('本站视频支持优酷、土豆等在线视频与本地视频两种，其中优酷视频请直接把视频 ID 输入到『优酷视频 ID』，可直接获取视频缩略图，其它在线视频和本地视频请输入视频链接到『其它视频链接』中，两者选其一。', 'salong') . '</span></p>';
		echo '<p><label for="tougao_youku_id">' . __('优酷视频ID', 'salong') . '</label><input type="text" value="" id="tougao_youku_id" name="tougao_youku_id" placeholder="' . __('请输入优酷视频ID', 'salong') . '" /></p>';
		echo '<p><label for="tougao_video_url">' . __('其它视频链接', 'salong') . '</label><input type="text" value="" id="tougao_video_url" name="tougao_video_url" placeholder="' . __('请输入优酷视频ID', 'salong') . '" /></p><hr>';
	}
	echo '<p><label for="tougao_authorname"><b>*</b>' . __('昵称', 'salong') . '</label><input type="text" value="' . $user_name . '" id="tougao_authorname" name="tougao_authorname" placeholder="' . __('请输入昵称', 'salong') . '" required /></p>';
	echo '<p><label for="tougao_authoremail"><b>*</b>' . __('邮箱', 'salong') . '</label><input type="text" value="' . $user_email . '" id="tougao_authoremail" name="tougao_authoremail" placeholder="' . __('请输入邮箱', 'salong') . '" required /></p>';
	echo '<p><label for="tougao_authorblog">' . __('博客', 'salong') . '</label><input type="text" value="' . $user_url . '" id="tougao_authorblog" name="tougao_authorblog" placeholder="' . __('请输入博客', 'salong') . '" /></p><hr>';
	echo '<p><label for="tougao_from_name">' . __('文章来源网站名称', 'salong') . '</label><input type="text" value="" id="tougao_from_name" name="tougao_from_name" /></p>';
	echo '<p><label for="tougao_from_link">' . __('文章来源网站链接', 'salong') . '</label><input type="text" value="" id="tougao_from_link" name="tougao_from_link" /></p><hr>';
	echo '<div class="captcha"><label for="captcha"><b>*</b>' . __('验证码', 'salong') . '</label><p><input id="captcha" class="input" type="text" value="" name="captcha_code" required />';
	?>
       <a href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='<?php 
	bloginfo('template_url');
	?>
/captcha/captcha.php?'+Math.random();document.getElementById('CAPTCHA').focus();return false;">
        <img id="captcha_img" src="<?php 
	bloginfo('template_url');
	?>
/captcha/captcha.php" /></a>
    <?php 
	echo '</p></div>';
	echo '<p class="hint">' . $salong['contribute_info'] . '</p>';
	echo '<p><input type="hidden" value="send" name="tougao_form" /><input type="submit" value="' . __('提交', 'salong') . '" class="submit" /><input type="reset" value="' . __('重填', 'salong') . '" class="reset" /></p>';
	echo '</form>';
}
add_shortcode('contribute_post', 'salong_contribute_post');
//在文章编辑页面的[添加媒体]只显示用户自己上传的文件
global $salong;
if ($salong['switch_user_media']) {
	function my_upload_media($wp_query_obj)
	{
		global $current_user, $pagenow;
		if (!is_a($current_user, 'WP_User')) {
			return;
		}
		if ('admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments') {
			return;
		}
		if (!current_user_can('manage_options') && !current_user_can('manage_media_library')) {
			$wp_query_obj->set('author', $current_user->ID);
		}
		return;
	}
	add_action('pre_get_posts', 'my_upload_media');
	//在[媒体库]只显示用户上传的文件
	function my_media_library($wp_query)
	{
		if (strpos($_SERVER['REQUEST_URI'], '/wp-admin/upload.php') !== false) {
			if (!current_user_can('manage_options') && !current_user_can('manage_media_library')) {
				global $current_user;
				$wp_query->set('author', $current_user->id);
			}
		}
	}
	add_filter('parse_query', 'my_media_library');
}
//上传图片文件自动重命名
global $salong;
if ($salong['switch_upload_filter']) {
	add_filter('wp_handle_upload_prefilter', 'custom_upload_filter');
	function custom_upload_filter($file)
	{
		$info = pathinfo($file['name']);
		$ext = $info['extension'];
		$filedate = date('YmdHis') . rand(10, 99);
		//为了避免时间重复，再加一段2位的随机数
		$file['name'] = $filedate . '.' . $ext;
		return $file;
	}
}
//允许投稿者上传媒体
global $salong;
if ($salong['switch_contributor_uploads']) {
	function salong_default_role()
	{
		if (get_option('default_role') != 'contributor') {
			update_option('default_role', 'contributor');
		}
	}
	add_action('admin_menu', 'salong_default_role');
	function salong_allow_contributor_uploads()
	{
		if (current_user_can('contributor') && !current_user_can('upload_files')) {
			$contributor = get_role('contributor');
			$contributor->add_cap('upload_files');
		}
	}
	add_action('admin_init', 'salong_allow_contributor_uploads');
}
//限制用户上传的媒体文件
global $salong;
if ($salong['switch_upload_mimes']) {
	add_filter('upload_mimes', 'custom_upload_mimes');
	function custom_upload_mimes($existing_mimes = array())
	{
		$existing_mimes = array('jpg|jpeg|jpe' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png');
		return $existing_mimes;
	}
}
//投稿成功给投稿者发送邮件
global $salong;
if ($salong['switch_tougao_notify']) {
	function tougao_notify($mypost)
	{
		$email = get_post_meta($mypost->ID, "salong_tougao_email", true);
		if (!empty($email)) {
			// 以下是邮件标题
			$subject = '您在' . get_option('blogname') . '的投稿已发布';
			// 以下是邮件内容
			$message = '
            <p><strong>' . get_option('blogname') . '</strong> 提醒您: 您投递的文章 <strong>' . $mypost->post_title . '</strong> 已发布</p>

            <p>您可以点击以下链接查看具体内容:<br />
            <a href="' . get_permalink($mypost->ID) . '">点此查看完整內容</a></p>
            <p>===================================================================</p>
            <p><strong>感谢您对 <a href="' . get_home_url() . '" target="_blank">' . get_option('blogname') . '</a> 的关注和支持</strong></p>
            <p><strong>该信件由系统自动发出, 请勿回复, 谢谢.</strong></p>';
			add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
			@wp_mail($email, $subject, $message);
		}
	}
	// 当投稿的文章从草稿状态变更到已发布时，给投稿者发提醒邮件
	add_action('draft_to_publish', 'tougao_notify', 6);
	add_action('pending_to_publish', 'tougao_notify', 6);
}
//投稿简码////////////////////////////////////////////////////end
//安全///////////////////////////////////////////////////////////////
//保护后台登录
if ($salong['switch_admin_link']) {
	add_action('login_enqueue_scripts', 'login_protection');
	function login_protection()
	{
		global $salong;
		if ($_GET['' . $salong['admin_word'] . ''] != '' . $salong['admin_press'] . '') {
			header('Location: ' . get_home_url() . '');
		}
	}
}
//禁止冒充管理员评论
if ($salong['switch_incoming_comment']) {
	function salong_usecheck($incoming_comment)
	{
		$isSpam = 0;
		global $salong;
		if (trim($incoming_comment['comment_author_email']) == '' . $salong['admin_email'] . '') {
			$isSpam = 1;
		}
		if (!$isSpam) {
			return $incoming_comment;
		}
		wp_die(__('请勿冒充博主发表评论', 'salong'));
	}
	if (!is_user_logged_in()) {
		add_filter('preprocess_comment', 'salong_usecheck');
	}
}
// 网站维护
if ($salong['switch_weihu']) {
	function wp_maintenance_mode()
	{
		if (!current_user_can('edit_themes') || !is_user_logged_in()) {
			wp_die('' . sprintf(__('%s临时维护中，请稍后访问，给您带来的不便，敬请谅解！', 'salong'), esc_attr(get_option('blogname'))) . '', '' . sprintf(__('%s维护中', 'salong'), esc_attr(get_option('blogname'))) . '', array('response' => '503'));
		}
	}
	add_action('get_header', 'wp_maintenance_mode');
}
//哪些权限的用户可以访问后台
function block_admin_access()
{
	global $pagenow, $salong;
	if (defined('WP_CLI')) {
		return;
	}
	$access_level = $salong['admin_access'];
	$valid_pages = array('admin-ajax.php', 'admin-post.php', 'async-upload.php', 'media-upload.php');
	$user_center = get_page_link(get_page_id_from_template('template-center.php'));
	if (!current_user_can($access_level) && !in_array($pagenow, $valid_pages)) {
		wp_redirect($user_center);
		exit;
	}
}
add_action('admin_init', 'block_admin_access');
//只对管理员显示工具栏
global $salong;
if ($salong['switch_admin_bar'] == 0) {
	add_filter('show_admin_bar', '__return_false');
}
//页面重定向
function salong_redirect_page()
{
	global $current_user, $salong, $pagenow;
	$scheme = is_ssl() && !is_admin() ? 'https' : 'http';
	$current_url = $scheme . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	$redirect_url = get_page_link($salong['user_add_email_page']);
	$user_center = get_page_link(get_page_id_from_template('template-center.php'));
	$login_page = get_permalink($salong['login_page']);
	$register_page = get_permalink($salong['register_page']);
	if (is_user_logged_in()) {
		if ($salong['switch_user_add_email'] && !$current_user->user_email && $current_url != $redirect_url) {
			wp_redirect($redirect_url);
			exit;
		} else {
			if (is_page_template('template-login.php')) {
				wp_redirect($user_center);
				exit;
			}
		}
	} else {
		if (is_page_template('template-wpuf.php') || is_page_template('template-center.php')) {
			wp_redirect($login_page);
			exit;
		} else {
			if (strtolower($pagenow) == 'wp-login.php' && $salong['switch_custom_login_register']) {
				if (strtolower($_GET['action']) == 'register') {
					wp_redirect($register_page);
					exit;
				} else {
					if (strtolower($_GET['action']) == 'logout') {
						wp_redirect($register_page);
						exit;
					} else {
						wp_redirect($login_page);
						exit;
					}
				}
			}
		}
	}
	if (get_option('users_can_register') == 0 && $register_page == $current_url) {
		wp_redirect(home_url());
		exit;
	}
}

//优化///////////////////////////////////////////////////////////////
global $salong;
if ($salong['switch_useradd_time']) {
	//后台显示注册用户时间
	class RRHE
	{
		// Register the column - Registered
		public static function registerdate($columns)
		{
			$columns['registerdate'] = __('注册时间', 'registerdate');
			return $columns;
		}
		// Display the column content
		public static function registerdate_columns($value, $column_name, $user_id)
		{
			if ('registerdate' != $column_name) {
				return $value;
			}
			$user = get_userdata($user_id);
			$registerdate = get_date_from_gmt($user->user_registered);
			return $registerdate;
		}
		public static function registerdate_column_sortable($columns)
		{
			$custom = array('registerdate' => 'registered');
			return wp_parse_args($custom, $columns);
		}
		public static function registerdate_column_orderby($vars)
		{
			if (isset($vars['orderby']) && 'registerdate' == $vars['orderby']) {
				$vars = array_merge($vars, array('meta_key' => 'registerdate', 'orderby' => 'meta_value'));
			}
			return $vars;
		}
	}
	// Actions
	add_filter('manage_users_columns', array('RRHE', 'registerdate'));
	add_action('manage_users_custom_column', array('RRHE', 'registerdate_columns'), 15, 3);
	add_filter('manage_users_sortable_columns', array('RRHE', 'registerdate_column_sortable'));
	add_filter('request', array('RRHE', 'registerdate_column_orderby'));
}
// 外链跳转
global $salong;
if ($salong['switch_link_go']) {
	add_filter('the_content', 'link_to_jump', 999);
	function link_to_jump($content)
	{
		preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/', $content, $matches);
		if ($matches) {
			foreach ($matches[2] as $val) {
				if (strpos($val, '://') !== false && strpos($val, home_url()) === false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i', $val) && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i', $val)) {
					$content = str_replace("href=\"{$val}\"", "href=\"" . get_home_url() . "/go.php?url={$val}\" ", $content);
				}
			}
		}
		return $content;
	}
	// 评论者链接跳转并新窗口打开
	function commentauthor($comment_ID = 0)
	{
		$url = get_comment_author_url($comment_ID);
		$author = get_comment_author($comment_ID);
		if (empty($url) || 'http://' == $url) {
			echo $author;
		} else {
			echo "<a href='" . get_home_url() . "/go.php?url={$url}' rel='external nofollow' target='_blank' class='url'>{$author}</a>";
		}
	}
	//版权外链
	function external_link($url)
	{
		if (strpos($url, '://') !== false && strpos($url, home_url()) === false && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i', $url)) {
			$url = str_replace($url, get_home_url() . "/go.php?url=" . $url, $url);
		}
		return $url;
	}
}
//找回上传设置
if ($salong['switch_upload_path']) {
	if (get_option('upload_path') == 'wp-content/uploads' || get_option('upload_path') == null) {
		update_option('upload_path', WP_CONTENT_DIR . '/uploads');
	}
}
//重置系统时间为北京时间
if ($salong['switch_date_default']) {
	date_default_timezone_set("Asia/Shanghai");
}
// 去除分类category
if ($salong['remove_category_slug']) {
	require_once get_template_directory() . '/includes/no-category.php';
}
//禁用RSS Feed防止rss采集
if ($salong['switch_feed']) {
	function salong_disable_feed()
	{
		wp_die(__('<h1>本博客不再提供 Feed，请访问网站<a href="' . get_bloginfo('url') . '">首页</a>！</h1>'));
	}
	add_action('do_feed', 'salong_disable_feed', 1);
	add_action('do_feed_rdf', 'salong_disable_feed', 1);
	add_action('do_feed_rss', 'salong_disable_feed', 1);
	add_action('do_feed_rss2', 'salong_disable_feed', 1);
	add_action('do_feed_atom', 'salong_disable_feed', 1);
}
if ($salong['switch_header_code']) {
	// 移除头部冗余代码
	remove_action('wp_head', 'wp_generator');
	// 移除WP版本号
	remove_action('wp_head', 'rsd_link');
	// 离线编辑器接口
	remove_action('wp_head', 'wlwmanifest_link');
	// 同上
	remove_action('wp_head', 'feed_links', 2);
	// 文章和评论feed
	remove_action('wp_head', 'feed_links_extra', 3);
	// 去除评论feed
	remove_action('wp_head', 'index_rel_link');
	//删除 head 中首页，上级，开始，相连的日志链接
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	//删除 head 中的 shortlink
	remove_action('template_redirect', 'wp_shortlink_header', 11, 0);
	//删除短链接通知，不知道这个是干啥的。
	remove_action('wp_head', 'wp_resource_hints', 2);
}
if ($salong['switch_capital_P_dangit']) {
	//移除 WordPress 自动修正 WordPress 大小写函数
	remove_filter('the_content', 'capital_P_dangit');
	remove_filter('the_title', 'capital_P_dangit');
	remove_filter('comment_text', 'capital_P_dangit');
}
if ($salong['switch_shortcode_unautop']) {
	//让 Shortcode 优先于 wpautop 执行
	remove_filter('the_content', 'shortcode_unautop');
	add_filter('the_content', 'shortcode_unautop', 13);
}
if ($salong['switch_rest_api']) {
	// 屏蔽 REST API
	remove_action('init', 'rest_api_init');
	remove_action('rest_api_init', 'rest_api_default_filters', 10);
	remove_action('parse_request', 'rest_api_loaded');
	add_filter('rest_enabled', '__return_false');
	add_filter('rest_jsonp_enabled', '__return_false');
	// 移除头部 wp-json 标签和 HTTP header 中的 link
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	remove_action('template_redirect', 'rest_output_link_header', 11);
}
if ($salong['switch_wp_oembed']) {
	//禁用 Auto Embeds 功能，Auto Embeds 基本不支持国内网站，禁用，加快页面解析速度。
	remove_filter('the_content', array($GLOBALS['wp_embed'], 'run_shortcode'), 8);
	remove_filter('the_content', array($GLOBALS['wp_embed'], 'autoembed'), 8);
	remove_action('pre_post_update', array($GLOBALS['wp_embed'], 'delete_oembed_caches'));
	remove_action('edit_form_advanced', array($GLOBALS['wp_embed'], 'maybe_run_ajax_cache'));
	//屏蔽文章 Embed 功能，添加带embed或视频链接到编辑器中，转不会被转换。
	remove_action('rest_api_init', 'wp_oembed_register_route');
	remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
	add_filter('embed_oembed_discover', '__return_false');
	remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
	remove_filter('oembed_response_data', 'get_oembed_response_data_rich', 10, 4);
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	remove_action('wp_head', 'wp_oembed_add_host_js');
	add_filter('tiny_mce_plugins', 'salong_disable_post_embed_tiny_mce_plugin');
	function salong_disable_post_embed_tiny_mce_plugin($plugins)
	{
		return array_diff($plugins, array('wpembed'));
	}
	add_filter('query_vars', 'salong_disable_post_embed_query_var');
	function salong_disable_post_embed_query_var($public_query_vars)
	{
		return array_diff($public_query_vars, array('embed'));
	}
}
if ($salong['switch_dashboard_widgets']) {
	//去除后台首页面板的功能
	add_action('wp_dashboard_setup', 'salong_remove_dashboard_widgets');
	function salong_remove_dashboard_widgets()
	{
		global $wp_meta_boxes;
		unset($wp_meta_boxes['dashboard']['normal']);
		unset($wp_meta_boxes['dashboard']['side']);
	}
}
if ($salong['switch_staticize_emoji']) {
	//禁止Emoji表情，提高网站加载速度
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
}
if ($salong['switch_wp_cron']) {
	//禁用 WP_CRON 文章定时发布功能，如果网站不需要定时发布功能可以禁用
	defined('DISABLE_WP_CRON');
	remove_action('init', 'wp_cron');
}
if ($salong['switch_xmlrpc_enabled']) {
	//禁用 XML-RPC 接口，离线发布功能，无需通过 APP 客户端发布日志就禁用
	add_filter('xmlrpc_enabled', '__return_false');
}
//彻底关闭 pingback
if ($salong['switch_pingback']) {
	add_filter('xmlrpc_methods', 'salong_xmlrpc_methods');
	function salong_xmlrpc_methods($methods)
	{
		$methods['pingback.ping'] = '__return_false';
		$methods['pingback.extensions.getPingbacks'] = '__return_false';
		return $methods;
	}
	//禁用 pingbacks, enclosures, trackbacks
	remove_action('do_pings', 'do_all_pings', 10, 1);
	//去掉 _encloseme 和 do_ping 操作。
	remove_action('publish_post', '_publish_post_hook', 5, 1);
}
if ($salong['switch_admin_color_schemes']) {
	//移除后台管理界面配色方案
	remove_action('admin_init', 'register_admin_color_schemes', 1);
	remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
}
if ($salong['switch_update_core']) {
	//屏蔽后台功能提示，移除后台核心，插件和主题的更新提示
	add_filter('pre_site_transient_update_core', '__return_null');
	remove_action('load-update-core.php', 'wp_update_plugins');
	add_filter('pre_site_transient_update_plugins', '__return_null');
	remove_action('load-update-core.php', 'wp_update_themes');
	add_filter('pre_site_transient_update_themes', '__return_null');
}
if ($salong['switch_post_revision']) {
	//文章修订版本
	add_filter('wp_revisions_to_keep', 'specs_wp_revisions_to_keep', 10, 2);
	function specs_wp_revisions_to_keep($num, $post)
	{
		return 0;
	}
}
if ($salong['switch_autosave']) {
	//禁用后台自动保存
	add_action('admin_print_scripts', create_function('$a', "wp_deregister_script('autosave');"));
}
if ($salong['switch_browse_happy']) {
	// Browse Happy
	add_action('admin_init', create_function('$a', "remove_action('in_admin_footer', 'browse_happy');"));
}
if ($salong['switch_recently_active_plugins']) {
	//显示最近启用过的插件
	add_action('admin_head', 'disable_recently_active_plugins');
	function disable_recently_active_plugins()
	{
		update_option('recently_activated', array());
	}
}
if ($salong['switch_max_srcset']) {
	//禁用 WordPress 4.4+ 的响应式图片功能
	add_filter('max_srcset_image_width', create_function('', 'return 1;'));
}
if ($salong['switch_login_errors']) {
	//隐藏面板登陆错误信息
	add_filter('login_errors', create_function('$a', "return null;"));
}
if ($salong['switch_redirect_single_post']) {
	//当搜索结果只有一篇时直接重定向到日志
	add_action('template_redirect', 'salong_redirect_single_post');
	function salong_redirect_single_post()
	{
		if (is_search()) {
			global $wp_query;
			if ($wp_query->post_count == 1) {
				wp_redirect(get_permalink($wp_query->posts['0']->ID));
			}
		}
	}
}
if ($salong['switch_search_by_title_only']) {
	//只搜索标题
	function __search_by_title_only($search, &$wp_query)
	{
		global $wpdb;
		if (empty($search)) {
			return $search;
		}
		// skip processing - no search term in query
		$q = $wp_query->query_vars;
		$n = !empty($q['exact']) ? '' : '%';
		$search = $searchand = '';
		foreach ((array) $q['search_terms'] as $term) {
			$term = esc_sql(like_escape($term));
			$search .= "{$searchand}({$wpdb->posts}.post_title LIKE '{$n}{$term}{$n}')";
			$searchand = ' AND ';
		}
		if (!empty($search)) {
			$search = " AND ({$search}) ";
			if (!is_user_logged_in()) {
				$search .= " AND ({$wpdb->posts}.post_password = '') ";
			}
		}
		return $search;
	}
	add_filter('posts_search', '__search_by_title_only', 500, 2);
}
if ($salong['switch_post_id']) {
	//直接显示文章页面自定义文章类型文章的ID
	add_filter('manage_posts_columns', 'salong_id_manage_posts_columns');
	add_filter('manage_pages_columns', 'salong_id_manage_posts_columns');
	function salong_id_manage_posts_columns($columns)
	{
		$columns['post_id'] = 'ID';
		return $columns;
	}
	add_action('manage_posts_custom_column', 'salong_id_manage_posts_custom_column', 10, 2);
	add_action('manage_pages_custom_column', 'salong_id_manage_posts_custom_column', 10, 2);
	function salong_id_manage_posts_custom_column($column_name, $id)
	{
		if ($column_name == 'post_id') {
			echo $id;
		}
	}
}
if ($salong['switch_remove_logo']) {
	//移除 Admin Bar 上的 WordPress Logo
	function salong_admin_bar_remove()
	{
		global $wp_admin_bar;
		/* Remove their stuff */
		$wp_admin_bar->remove_menu('wp-logo');
	}
	add_action('wp_before_admin_bar_render', 'salong_admin_bar_remove', 0);
}
if ($salong['switch_shortcode_auto'] && is_single()) {
	//禁止简码自动添加p与br标签
	remove_filter('the_content', 'wpautop');
	add_filter('the_content', 'wpautop', 12);
}
if ($salong['switch_content_auto']) {
	//禁止整个文章自动添加p与br标签
	remove_filter('the_content', 'wpautop');
	remove_filter('the_excerpt', 'wpautop');
}