<?php
global $salong, $allow_genuine;
header("Content-type: text/html; charset=utf-8");
//加载语言包
load_theme_textdomain('salong', get_template_directory() . '/languages');
//绑定域名
function genuine_allow_domain()
{
	$allow_genuine = false;
	global $allow_genuine;
	//获取不带端口号的域名前缀
	$servername = trim($_SERVER['HTTP_HOST']);
	//授权域名列表
	$Array = array($_SERVER['HTTP_HOST']);
	//遍历数组
	foreach ($Array as $value) {
		$value = trim($value);
		$domain = explode($value, $servername);
		if (count($domain) > 1) {
			$allow_genuine = true;
			break;
		}
	}
	if (!$allow_genuine) {
		exit('购买正版主题请访问：<a href="https://salongweb.com">萨龙网络</a>，感谢您的支持！');
	}
}
genuine_allow_domain();
// 后台设置
if (!class_exists('ReduxFramework') && file_exists(get_template_directory() . '/admin/ReduxCore/framework.php') && function_exists('genuine_allow_domain') && $allow_genuine) {
	require_once get_template_directory() . '/admin/ReduxCore/framework.php';
}
if (!isset($redux_demo) && file_exists(get_template_directory() . '/admin/config.php') && function_exists('genuine_allow_domain') && $allow_genuine) {
	require_once get_template_directory() . '/admin/config.php';
}
// 主题更新
if ($salong['switch_update']) {
	require_once get_template_directory() . '/includes/update.php';
}
// 缩略图
if ($salong['thumb_mode'] == 'timthumb') {
	require_once get_template_directory() . '/includes/thumb.php';
} else {
	require_once get_template_directory() . '/includes/wpauto.php';
}
// 自定义文章
if ($salong['switch_post_type'] && function_exists('genuine_allow_domain') && $allow_genuine) {
	require_once get_template_directory() . '/includes/post-types.php';
}
// 短代码
require_once get_template_directory() . '/includes/shortcodes/shortcodespanel.php';
require_once get_template_directory() . '/includes/shortcodes/shortcodes.php';
//主题教程
require_once get_template_directory() . '/includes/tutorial.php';
//文章META
require_once get_template_directory() . '/includes/meta-boxes.php';
// 让WordPress支持使用中文用户名注册和登录
function salong_sanitize_user($username, $raw_username, $strict)
{
	$username = wp_strip_all_tags($raw_username);
	$username = remove_accents($username);
	// Kill octets
	$username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);
	$username = preg_replace('/&.+?;/', '', $username);
	// Kill entities
	// 网上很多教程都是直接将$strict赋值false，
	// 这样会绕过字符串检查，留下隐患
	if ($strict) {
		$username = preg_replace('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
	}
	$username = trim($username);
	// Consolidate contiguous whitespace
	$username = preg_replace('|\s+|', ' ', $username);
	return $username;
}
add_filter('sanitize_user', 'salong_sanitize_user', 10, 3);