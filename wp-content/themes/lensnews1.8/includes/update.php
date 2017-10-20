<?php
//主题更新提示
global $salong;
$update_time = esc_attr($salong['update_time']);

// 设置主题名称、文件夹名、xml文件路径和检查更新时间
define( 'NOTIFIER_THEME_NAME', 'LensNews' ); //主题名称
define( 'NOTIFIER_THEME_FOLDER_NAME', 'lensnews' ); //主题文件夹名称
define( 'NOTIFIER_XML_FILE', 'https://pic.salongweb.com/themes-update/lensnews.xml' ); // xml文件路径
define( 'NOTIFIER_CACHE_INTERVAL', $update_time); // 检查主题更新时间



// 增加了一个更新通知在WordPress仪表盘菜单
function update_notifier_menu() {  
	if (function_exists('simplexml_load_string')) { // 假如simplexml_load_string 函数没有启用将不添加
	    $xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // 获得最新的远程服务器上的XML文件
		$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // 读取当前主题的style.css数据
		
		if( (float)$xml->latest > (float)$theme_data['Version']) { // 当前主题版本与远程XML版本的版本进行比较
			add_dashboard_page( NOTIFIER_THEME_NAME . __('主题更新','salong'), NOTIFIER_THEME_NAME .__('更新','salong').' <span class="update-plugins count-1"><span class="update-count">'.$xml->latest.'</span></span>', 'administrator', 'theme-update-notifier', 'update_notifier');
		}
	}	
}
add_action('admin_menu', 'update_notifier_menu');  



// 添加主题更新提示到WordPress工具栏
function update_notifier_bar_menu() {
	if (function_exists('simplexml_load_string')) { // 假如simplexml_load_string 函数没有启用将不添加
		global $wp_admin_bar, $wpdb;
	
		if ( !is_super_admin() || !is_admin_bar_showing() ) // 只对超级管理员显示
		return;
		
		$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // 获得最新的远程服务器上的XML文件
		$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // 读取当前主题的style.css数据
	
		if( (float)$xml->latest > (float)$theme_data['Version']) { // 当前主题版本与远程XML版本的版本进行比较
			$wp_admin_bar->add_menu( array( 'id' => 'update_notifier', 'title' => '<span>' . NOTIFIER_THEME_NAME . ' <span id="ab-updates">'.__('主题更新','salong').'</span></span>', 'href' => get_admin_url() . 'index.php?page=theme-update-notifier' ) );
		}
	}
}
add_action( 'admin_bar_menu', 'update_notifier_bar_menu', 1000 );



// 更新说明与内容
function update_notifier() { 
	$xml = get_latest_theme_version(NOTIFIER_CACHE_INTERVAL); // Get the latest remote XML file on our server
	$theme_data = get_theme_data(TEMPLATEPATH . '/style.css'); // Read theme current version from the style.css ?>
	
	<style>
		.update-nag { display: none; }
		#instructions {max-width: 800px;}
		h3.title {margin: 30px 0 0 0; padding: 30px 0 0 0; border-top: 1px solid #ddd;}
	</style>

	<div class="wrap">
	
		<div id="icon-tools" class="icon32"></div>
		<h2><?php echo NOTIFIER_THEME_NAME ?> <?php _e('主题更新','salong'); ?></h2>
        <div id="message" class="updated below-h2"><p><?php printf(__( '尊敬的萨龙网络主题用户，<strong>%s</strong>主题有更新了，当前版本：<strong>%s</strong>，可更新到的最新版本：<strong>%s</strong>。' , 'salong' ), esc_attr(NOTIFIER_THEME_NAME), esc_attr($theme_data['Version']), esc_attr($xml->latest)); ?></p></div>

		<img style="float: left; margin: 0 20px 20px 0; border: 1px solid #ddd;max-width: 800px;width:100%" src="<?php echo get_bloginfo( 'template_url' ) . '/screenshot.jpg'; ?>" />
		
		<div id="instructions">
		    <h3><?php _e('主题更新说明与下载：','salong'); ?></h3>
		    <p><?php printf(__('<strong>请注意：</strong>更新主题前，请备份好当前主题及网站数据库，当前主题目录：<strong>/wp-content/themes/%s/</strong>','salong'),esc_attr(NOTIFIER_THEME_FOLDER_NAME)); ?></p>
		    <p><?php _e('更新主题请登录<a href="https://salongweb.com" target="_blank" title="萨龙网络｜专注高端网站设计与开发，为您提供一个现代、干净的WEB站点！"><strong>萨龙网络</strong></a>，在 <strong>我的帐户—下载</strong> 中找到该主题点击下载按钮，下载主题zip压缩包。','salong'); ?></p>
		    <p><?php _e('主题更新过程中有任何问题，请及时联系我们！','salong'); ?></p>
		    <p><a class="button-primary" target="_blank" href="https://salongweb.com/my-account.html"><?php printf(__( '获取主题<strong>%s</strong>版本' , 'salong' ), esc_attr($xml->latest)); ?></a> <a class="button-secondary" target="_blank" href="https://salongweb.com/contact.html"><?php _e('联系我们','salong'); ?></a></p>
		</div>
	    <h3 class="title"><?php _e('主题更新日志：','salong'); ?></h3>
	    <?php echo $xml->changelog; ?>

	</div>
    
<?php } 

// 获取远程XML文件内容并返回它的数据(版本和更新日志)
// 使用缓存的版本如果可用,在定义的时间间隔
function get_latest_theme_version($interval) {
	$notifier_file_url = NOTIFIER_XML_FILE;	
	$db_cache_field = 'notifier-cache';
	$db_cache_field_last_updated = 'notifier-cache-last-updated';
	$last = get_option( $db_cache_field_last_updated );
	$now = time();
	// 检查缓存
	if ( !$last || (( $now - $last ) > $interval) ) {
		// 缓存中不存在,或者是旧的,将刷新
		if( function_exists('curl_init') ) { // 假如cURL可用，就使用
			$ch = curl_init($notifier_file_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$cache = curl_exec($ch);
			curl_close($ch);
		} else {
			$cache = file_get_contents($notifier_file_url); // 假如没有，将使用file_get_contents()
		}
		
		if ($cache) {			
			update_option( $db_cache_field, $cache );
			update_option( $db_cache_field_last_updated, time() );
		} 
		// 从缓存中读取文件
		$notifier_data = get_option( $db_cache_field );
	}
	else {
		// 缓存文件足够新，就读取它
		$notifier_data = get_option( $db_cache_field );
	}
	
	// 如果没有,使用默认1.0最新版本,所以我们没有问题当远程服务器托管的XML文件
	if( strpos((string)$notifier_data, '<notifier>') === false ) {
		$notifier_data = '<?xml version="1.0" encoding="UTF-8"?><notifier><latest>1.0</latest><changelog></changelog></notifier>';
	}
	
	// 远程XML数据加载到一个变量并返回它
	$xml = simplexml_load_string($notifier_data); 
	
	return $xml;
}

?>