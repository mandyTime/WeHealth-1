<?php
/**
 * WooCommerce Points and Rewards
 *
 * @package     WC-Points-Rewards/Classes
 * @author      WooThemes
 * @copyright   Copyright (c) 2013, WooThemes
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * # WooCommerce Core Actions Integration Class
 *
 * This class adds the WooCommerce core actions of product review and user
 * account registration as point earning actions.  This also provides a sample
 * integration for 3rd party plugins to follow to add their own custom point
 * reward actions.
 */
class WC_Points_Rewards_Actions {


	/**
	 * Initialize the WooCommerce core Points & Rewards integration class
	 *
	 * @since 1.0
	 */
	public function __construct() {

		// add the WooCommerce core action settings
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			add_filter( 'wc_points_rewards_action_settings', array( $this, 'points_rewards_action_settings' ), 1 );
		}

		// add the WooCommerce core actions event descriptions
		add_filter( 'wc_points_rewards_event_description', array( $this, 'add_action_event_descriptions' ), 10, 3 );

		// add points for user signup & writing a review
        //喜欢
		add_action( 'wp_ajax_nopriv_post-like', array( $this, 'like_action' ), 10, 2 );
		add_action( 'wp_ajax_post-like', array( $this, 'like_action' ), 10, 2 );
        //添加收藏
		add_action( 'wp_ajax_nopriv_add_favorite', array( $this, 'add_favorite_action' ), 10, 2 );
		add_action( 'wp_ajax_add_favorite', array( $this, 'add_favorite_action' ), 10, 2 );
        //取消收藏
		add_action( 'wp_ajax_nopriv_remove_favorite', array( $this, 'remove_favorite_action' ), 10, 2 );
		add_action( 'wp_ajax_remove_favorite', array( $this, 'remove_favorite_action' ), 10, 2 );
        //清空收藏
		add_action( 'wp_ajax_nopriv_clear_favorite', array( $this, 'clear_favorite_action' ), 10, 2 );
		add_action( 'wp_ajax_clear_favorite', array( $this, 'clear_favorite_action' ), 10, 2 );
        //发布文章
		add_action( 'publish_post', array( $this, 'publish_post_action' ), 10, 2 );
        //发布画廊
		add_action( 'publish_gallery', array( $this, 'publish_gallery_action' ), 10, 2 );
        //发布作品
		add_action( 'publish_portfolio', array( $this, 'publish_portfolio_action' ), 10, 2 );
        //发布视频
		add_action( 'publish_video', array( $this, 'publish_video_action' ), 10, 2 );
        //发布产品
		add_action( 'publish_product', array( $this, 'publish_product_action' ), 10, 2 );
        //文章评论
		add_action( 'comment_post', array( $this, 'post_review_action' ), 10, 2 );
		add_action( 'comment_unapproved_to_approved', array( $this, 'post_review_approve_action' ) );
        //登录
//		add_action( 'wp_login', array( $this, 'user_login_action' ) );
        //注册
		add_action( 'user_register', array( $this, 'create_account_action' ) );

	}


	/**
	 * Adds the WooCommerce core actions integration settings
	 *
	 * @since 1.0
	 * @param array $settings the settings array
	 * @return array the settings array
	 */
	public function points_rewards_action_settings( $settings ) {

		$settings = array_merge(
			$settings,
			array(
				array(
					'title'    => __( 'Points earned for account signup', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer signs up for an account.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_account_signup_points',
				),
                
//				array(
//					'title'    => __( 'Points earned for user login', 'woocommerce-points-and-rewards' ),
//					'desc_tip' => __( 'Enter the amount of points earned when a customer login.', 'woocommerce-points-and-rewards' ),
//					'id'       => 'wc_points_rewards_user_login_points',
//				),

				array(
					'title'    => __( 'Points earned for like post', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer like post.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_like_points',
				),

				array(
					'title'    => __( 'Points earned for add favorite', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer add favorite.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_add_favorite_points',
				),

				array(
					'title'    => __( 'Points earned for remove favorite', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer remove favorite.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_remove_favorite_points',
				),

				array(
					'title'    => __( 'Points earned for clear favorite', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer clear favorite.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_clear_favorite_points',
				),

				array(
					'title'    => __( 'Points earned for writing a post review', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer first reviews a post.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_write_post_review_points',
				),
                
				array(
					'title'    => __( 'Points earned for publish post', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer publish a post.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_publish_post_points',
				),

				array(
					'title'    => __( 'Points earned for publish gallery', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer publish a gallery.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_publish_gallery_points',
				),

				array(
					'title'    => __( 'Points earned for publish portfolio', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer publish a portfolio.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_publish_portfolio_points',
				),

				array(
					'title'    => __( 'Points earned for publish video', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer publish a video.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_publish_video_points',
				),

				array(
					'title'    => __( 'Points earned for publish product', 'woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Enter the amount of points earned when a customer publish a product.', 'woocommerce-points-and-rewards' ),
					'id'       => 'wc_points_rewards_publish_product_points',
				),
			)
		);

		return $settings;
	}


	/**
	 * Provides an event description if the event type is one of 'product-review' or
	 * 'account-signup'
	 *
	 * @since 1.0
	 * @param string $event_description the event description
	 * @param string $event_type the event type
	 * @param object $event the event log object, or null
	 * @return string the event description
	 */
	public function add_action_event_descriptions( $event_description, $event_type, $event ) {
		global $wc_points_rewards;

		$points_label = $wc_points_rewards->get_points_label( $event ? $event->points : null );

		// set the description if we know the type
		switch ( $event_type ) {
			case 'like': $event_description                                  = sprintf( __( '%s earned for like', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'add_favorite': $event_description                          = sprintf( __( '%s earned for add favorite', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'remove_favorite': $event_description                       = sprintf( __( '%s earned for remove favorite', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'clear_favorite': $event_description                        = sprintf( __( '%s earned for clear favorite', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'account-signup': $event_description                        = sprintf( __( '%s earned for account signup', 'woocommerce-points-and-rewards' ), $points_label ); break;
//			case 'user-login': $event_description                          = sprintf( __( '%s earned for user login', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'publish-post': $event_description                          = sprintf( __( '%s earned for publish post', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'publish-gallery': $event_description                       = sprintf( __( '%s earned for publish gallery', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'publish-portfolio': $event_description                     = sprintf( __( '%s earned for publish portfolio', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'publish-video': $event_description                         = sprintf( __( '%s earned for publish video', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'publish-product': $event_description                       = sprintf( __( '%s earned for publish product', 'woocommerce-points-and-rewards' ), $points_label ); break;
			case 'post-review': $event_description                           = sprintf( __( '%s earned for review', 'woocommerce-points-and-rewards' ), $points_label ); break;
		}

		return $event_description;
	}
    

	/****************************************************************************************
	 * Add points 文章评论
	 *
	 * @since 1.0
	 */
	public function post_review_action( $comment_id, $approved = 0 ) {
		if ( ! is_user_logged_in() || ! $approved )
			return;

		$comment   = get_comment( $comment_id );
		$post_type = get_post_type( $comment->comment_post_ID );
        
        $points = get_option( 'wc_points_rewards_write_post_review_points' );

		if ( ! empty( $points ) ) {

			/**
			 * Filter the parameters for get_comments called on posting a review.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			$params = apply_filters( 'wc_points_rewards_review_post_comments_args', array( 'user_id' => get_current_user_id(), 'post_id' => $comment->comment_post_ID ) );

			// only award points for the first comment placed on a particular post by a user
			$comments = get_comments( $params );

			/**
			 * Filter if points should be added for this comment id on posting a review.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			if ( count( $comments ) <= 1 && apply_filters( 'wc_points_rewards_post_add_post_review_points', true, $comment_id ) ) {
				WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'post-review', array( 'post_id' => get_the_ID() ) );
			}
		}
	}

	/**
	 * Triggered 文章评论
	 */
	public function post_review_approve_action( $comment ) {
        
		$post_type = get_post_type( $comment->comment_post_ID );
        
        $points = get_option( 'wc_points_rewards_write_post_review_points' );

		if ( ! empty( $points ) && $comment->user_id ) {

			/**
			 * Filter the parameters for get_comments called when reviews are approved.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			$params = apply_filters( 'wc_points_rewards_post_review_approve_comments_args', array( 'user_id' => $comment->user_id, 'post_id' => $comment->comment_post_ID ) );

			// only award points for the first comment placed on a particular post by a user
			$comments = get_comments( $params );

			/**
			 * Filter if points should be added for this comment id when reviews are approved.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			if ( count( $comments ) <= 1 && apply_filters( 'wc_points_rewards_approve_add_post_review_points', true, $comment->comment_ID ) ) {
				WC_Points_Rewards_Manager::increase_points( $comment->user_id, $points, 'post-review', array( 'post_id' => $comment->comment_post_ID ) );
			}
		}
	}
	/****************************************************************************************/
    


	/**
	 * Add points 发布文章
	 *
	 * @since 1.0
	 */
	public function publish_post_action( $post_id, $approved = 0 ) {
        
		if ( ! is_user_logged_in() || ! $approved )
			return;
        
		$post_type = get_post_type($post_id);
		if ( 'post' === $post_type ) {
			$points = get_option( 'wc_points_rewards_publish_post_points' );
		}

		if ( ! empty( $points ) ) {

			/**
			 * Filter if points should be added for this comment id on posting a review.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			global $wpdb;
            if($_POST['original_post_status'] != 'publish') {
				WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'publish-post', array( 'post_id' => get_the_ID() ) );
			}
		}
	}


	/**
	 * Add points 发布画廊
	 *
	 * @since 1.0
	 */
	public function publish_gallery_action( $post_id, $approved = 0 ) {
        
		if ( ! is_user_logged_in() || ! $approved )
			return;
        
		$post_type = get_post_type($post_id);
		if ( 'gallery' === $post_type ) {
			$points = get_option( 'wc_points_rewards_publish_gallery_points' );
		}

		if ( ! empty( $points ) ) {

			/**
			 * Filter if points should be added for this comment id on posting a review.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			global $wpdb;
            if($_POST['original_post_status'] != 'publish') {
				WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'publish-gallery', array( 'post_id' => get_the_ID() ) );
			}
		}
	}


	/**
	 * Add points 发布作品
	 *
	 * @since 1.0
	 */
	public function publish_portfolio_action( $post_id, $approved = 0 ) {
        
		if ( ! is_user_logged_in() || ! $approved )
			return;
        
		$post_type = get_post_type($post_id);
		if ( 'portfolio' === $post_type ) {
			$points = get_option( 'wc_points_rewards_publish_portfolio_points' );
		}

		if ( ! empty( $points ) ) {

			/**
			 * Filter if points should be added for this comment id on posting a review.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			global $wpdb;
            if($_POST['original_post_status'] != 'publish') {
				WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'publish-portfolio', array( 'post_id' => get_the_ID() ) );
			}
		}
	}


	/**
	 * Add points 发布视频
	 *
	 * @since 1.0
	 */
	public function publish_video_action( $post_id, $approved = 0 ) {
        
		if ( ! is_user_logged_in() || ! $approved )
			return;
        
		$post_type = get_post_type($post_id);
		if ( 'video' === $post_type ) {
			$points = get_option( 'wc_points_rewards_publish_video_points' );
		}

		if ( ! empty( $points ) ) {

			/**
			 * Filter if points should be added for this comment id on posting a review.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			global $wpdb;
            if($_POST['original_post_status'] != 'publish') {
				WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'publish-video', array( 'post_id' => get_the_ID() ) );
			}
		}
	}


	/**
	 * Add points 发布产品
	 *
	 * @since 1.0
	 */
	public function publish_product_action( $post_id, $approved = 0 ) {
        
		if ( ! is_user_logged_in() || ! $approved )
			return;
        
		$post_type = get_post_type($post_id);
		if ( 'product' === $post_type ) {
			$points = get_option( 'wc_points_rewards_publish_product_points' );
		}

		if ( ! empty( $points ) ) {

			/**
			 * Filter if points should be added for this comment id on posting a review.
			 *
			 * @since 1.3.5-1
			 * @param array $params existing parameters for the get_comments function
			 */
			global $wpdb;
            if($_POST['original_post_status'] != 'publish') {
				WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'publish-product', array( 'post_id' => get_the_ID() ) );
			}
		}
	}


	/**
	 * Add points to 新注册用户
	 *
	 * @since 1.0
	 */
	public function create_account_action( $user_id ) {
		$points = get_option( 'wc_points_rewards_account_signup_points' );

		if ( ! empty( $points ) )
			WC_Points_Rewards_Manager::increase_points( $user_id, $points, 'account-signup' );
	}

//
//	/**
//	 * Add points to 用户登录
//	 *
//	 * @since 1.0
//	 */
//	public function user_login_action() {
//        
//		$points = get_option( 'wc_points_rewards_user_login_points' );
//        
//        $user_id = get_current_user_id();
//        
//        if ( ! empty( $points ) && $user_id != 0 ){
//            WC_Points_Rewards_Manager::increase_points( $user_id, $points, 'user-login' );
//        }
//	}


}
