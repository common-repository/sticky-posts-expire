<?php
/**
 * MK Sticky Posts Expire core class.
 *
 * @package MKStickyPostsExpire/Core
 * @since 1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MKStickyPostsExpireCore' ) ){
    
    class MKStickyPostsExpireCore{
        
        /**
		 * Construct
		 * @since 1.0
		 * @version 1.0
		 */
        public function __construct(){
            add_filter( 'the_title', array( $this, 'mk_sticky_posts_expire_unstick' ), 100, 2 );
        }
        
        /**
		 * Determines if a post is expired
         * @access public
		 * @since 1.0
		 * @version 1.0
		 */
        public function mk_sticky_posts_expire_time( $post_id = 0 ){
            
            $expires = get_post_meta( $post_id, 'mk_spe_expiration', true );
            
            if( ! empty( $expires ) ) {
                // Get the current time and the post's expiration date
                $current_time = current_time( 'timestamp' );
                $expiration   = strtotime( $expires, current_time( 'timestamp' ) );
                // Determine if current time is greater than the expiration date
                if( $current_time >= $expiration ) {
                    return true;
                }
            }
            
            return false;
        }
        
        /**
         * Unstick Posts
         * @access public
         * @since 1.0
         * @version 1.0
         */
        public function mk_sticky_posts_expire_unstick( $title = '', $post_id = 0 ) {
        
            if( $this->mk_sticky_posts_expire_time( $post_id ) ) {
                    // Post is expired so unstick
                    unstick_post ( $post_id );
            }
            
            return $title;
        }
        
    }
    new MKStickyPostsExpireCore();
}
?>