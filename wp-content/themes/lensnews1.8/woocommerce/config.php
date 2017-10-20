<?php

//设置促销的位置
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 10 );
//把列表评论放在价格后面
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 15 );
//把文章评论放在价格后面
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );
//移除META
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
// 移除WooCommerce默认面包屑
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

//让palpay支持人民币
add_filter( 'woocommerce_paypal_supported_currencies', 'enable_custom_currency' );
function enable_custom_currency($currency_array) {
  $currency_array[] = 'CNY';
  return $currency_array;
}

// 修改paypay付款时人民币转美元汇率
global $salong;
add_filter('woocommerce_paypal_args', 'convert_rmb_to_usd');
$rate = $salong['rate'];
function convert_rmb_to_usd($paypal_args){
    if ( $paypal_args['currency_code'] == 'CNY'){
        $convert_rate = $rate; //设置转换速率
        $count = 1;
        while( isset($paypal_args['amount_' . $count]) ){
            $paypal_args['amount_' . $count] = round( $paypal_args['amount_' . $count] / $convert_rate, 2);
            $count++;
        }       
    }
    return $paypal_args;
}

// ajax添加到购物车
if (class_exists( 'woocommerce' )) {
    require_once get_template_directory() . '/woocommerce/hooks.php';
}

// 在主题中声明对WooCommerce的支持
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// 禁用 WooCommerce样式
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

// WooCommerce相关产品数量
add_filter( 'woocommerce_output_related_products_args', 'wc_custom_related_products_args' );
function wc_custom_related_products_args( $args ){
    global $salong;
    $args = array(
        'posts_per_page' => $salong['product_related_count'],
        'orderby' => 'rand'
    );
    return $args;
}

//为商城添加图片延迟加载
if( $salong['switch_lazyload']== true ){
if (!function_exists('woocommerce_template_loop_product_thumbnail')) {
    function woocommerce_template_loop_product_thumbnail() {
        global $salong;
        $llwoo_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'shop_catalog');
        $llwoo_placeholder = $salong['thumb_loading']['url'];
        $llwoo_placeholder_fallback = woocommerce_placeholder_img_src();
        if (!empty($llwoo_placeholder)) {
            echo '<img src="' . $llwoo_placeholder . '" data-original="' . $llwoo_image_src[0] . '">';
        } else {
            echo '<img src="' . $llwoo_placeholder_fallback . '" data-original="' . $llwoo_image_src[0] . '">';
        }
    }

}
}


//让SI CAPTCHA Anti-Spam插件支持WooCommerce注册
add_filter( 'woocommerce_process_registration_errors', 'op_woocommerce_sicaptcha_registration_filter' );
function op_woocommerce_sicaptcha_registration_filter($errors) {
    if (class_exists('siCaptcha')) {
        $si_image_captcha = new siCaptcha();
        $errors = $si_image_captcha->si_captcha_register_post($errors);
    }
    return($errors);
}