<?php
/**
 * Plugin Name: Sticky Posts Expire
 * Plugin URI: https://wordpress.org/plugins/sticky-posts-expire/
 * Description: A simple plugin that allows you to set an expiration date on posts. Once a post is expired, it will no longer be sticky.
 * Version: 1.0
 * Tags: wordpress, expiration, sticky post, sticky post expiration, sticky, posts, expire
 * Author: Mubeen Khan
 * Author URI: http://mubeenkhan.com/
 * Author Email: wpmubeenkhan@gmail.com
 * Requires at least: WP 4.5
 * Tested up to: WP 5.5.1
 * Text Domain: sticky-posts-expire
 * Domain Path: /language
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Here we are adding plugin final class
 * it will work around the plugin.
 */
if ( ! class_exists( 'MKStickyPostsExpire' ) ){
    final class MKStickyPostsExpire{
        
        /**
         * 
         * Sticky Posts Expire version
         * Sticky Posts Expire text domain
         * 
         */
        public $version   = '1.0';
		public $slug      = 'sticky-posts-expire';
        
        /**
		 * Not allowed
		 * @since 1.0
		 * @version 1.0
		 */
		public function __clone(){
            _doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', $this->version );
        }
        
        /**
		 * Not allowed
		 * @since 1.0
		 * @version 1.0
		 */
		public function __wakeup(){
            _doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', $this->version );
        }
        
        /**
		 * Define
		 * @since 1.0
		 * @version 1.0
		 */
		public function define( $name, $value, $definable = true ){
			if ( ! defined( $name ) )
				define( $name, $value );
			elseif ( ! $definable && defined( $name ) )
				_doing_it_wrong( 'MKStickyPostsExpire->define()', 'Could not define: ' . $name . ' as it is already defined somewhere else!', MK_SPE_VERSION );
		}
        
        /**
		 * Require File
		 * @since 1.0
		 * @version 1.0
		 */
		public function file( $required_file ){
			if ( file_exists( $required_file ) )
				require_once $required_file;
			else
				_doing_it_wrong( 'MKStickyPostsExpire->file()', 'Requested file ' . $required_file . ' not found.', MK_SPE_VERSION );
		}
        
        /**
		 * Construct
		 * @since 1.0
		 * @version 1.0
		 */
		public function __construct(){
            $this->define_constants();
            $this->wordpress();
            $this->includes();
		}
        
        /**
		 * Define Constants
		 * First, we start with defining all requires constants if they are not defined already.
		 * @since 1.0
		 * @version 1.0
		 */
		private function define_constants(){
			/**
             * Here we define all plugin dir paths
             */
            $this->define( 'MK_SPE_VERSION', $this->version );
            $this->define( 'MK_SPE_TEXT_DOMAIN', $this->slug );
			$this->define( 'MK_SPE_THIS', __FILE__, false );
            $this->define( 'MK_SPE_BASE', plugin_basename( MK_SPE_THIS ) );
			$this->define( 'MK_SPE_ROOT_DIR', plugin_dir_path( MK_SPE_THIS ), false );
			$this->define( 'MK_SPE_INCLUDES', MK_SPE_ROOT_DIR . 'includes/', false );
            /**
             * Here we define all plugin urls
             */
            $this->define( 'MK_SPE_URL', plugin_dir_url(__FILE__), false );
            $this->define( 'MK_SPE_ASSETS', MK_SPE_URL . 'assets/', false );
            $this->define( 'MK_SPE_JS', MK_SPE_ASSETS . 'js/', false );
            $this->define( 'MK_SPE_CSS', MK_SPE_ASSETS . 'css/', false );
            $this->define( 'MK_SPE_IMG', MK_SPE_ASSETS . 'images/', false );
		}
        
        /**
		 * Include Plugin Files
		 * @since 1.0
		 * @version 1.0
		 */
		public function includes(){
            $this->file( MK_SPE_INCLUDES . 'sticky-posts-expire-core.php' );
            if( is_admin() ) {
                $this->file( MK_SPE_INCLUDES . 'sticky-posts-expire-editor.php' );
            }
		}
        
        /**
		 * WordPress
		 * Next we hook into WordPress
		 * @since 1.0
		 * @version 1.0
		 */
		public function wordpress() {
            add_action( 'in_plugin_update_message-sticky-posts-expire/sticky-posts-expire.php', array( $this, 'mk_sticky_posts_expire_update_warning' ) );
			add_action( 'init', array( $this, 'mk_sticky_posts_expire_load_textdomain' ), 5 );
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__) , array( $this, 'mk_sticky_posts_expire_plugin_links' ), 10, 4 );
            add_filter( 'plugin_row_meta', array( $this, 'mk_sticky_posts_expire_description_links' ), 10, 2 );
        }
        
        /**
		 * Plugin Update Warning
		 * @since 1.0
		 * @version 1.0
		 */
		public function mk_sticky_posts_expire_update_warning(){
			echo '<div style="color:#cc0000;">' . __( 'Make sure to backup your database and files before updating, in case anything goes wrong!', MK_SPE_TEXT_DOMAIN ) . '</div>';
		}
        
        /**
		 * Load Plugin Textdomain
		 * @since 1.0
		 * @version 1.0
		 */
		public function mk_sticky_posts_expire_load_textdomain(){
			$locale = apply_filters( 'plugin_locale', get_locale(), MK_SPE_TEXT_DOMAIN );
			load_textdomain( MK_SPE_TEXT_DOMAIN , WP_LANG_DIR . '/sticky-posts-expire/mk-' . $locale . '.mo' );
			load_plugin_textdomain( MK_SPE_TEXT_DOMAIN , false, dirname( plugin_basename( __FILE__ ) ) . '/language/' );
		}
        
        /**
		 * Plugin Links
		 * @since 1.0
		 * @version 1.0
		 */
		public function mk_sticky_posts_expire_plugin_links( $actions, $plugin_file, $plugin_data, $context ){
			$actions['_settings'] = '<a href="' . admin_url( 'plugins.php#' ) . '" >' . __( 'Settings', MK_SPE_TEXT_DOMAIN ) . '</a>';
			ksort( $actions );
			return $actions;
		}
        
        /**
		 * Plugin Description Links
		 * @since 1.0
		 * @version 1.0
		 */
		public function mk_sticky_posts_expire_description_links( $links, $file ){
			if ( $file != MK_SPE_BASE ) return $links;
			// Usefull links
			$links[] = '<a href="#" target="_blank">Documentation</a>';
            $links[] = '<a href="#" target="_blank">About</a>';
			$links[] = '<a href="#" target="_blank">Premium support</a>';
            return $links;
		}
    }
    new MKStickyPostsExpire();
}
?>