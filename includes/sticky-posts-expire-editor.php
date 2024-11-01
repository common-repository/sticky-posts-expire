<?php
/**
 * MK Sticky Posts Expire Editor class.
 *
 * @package MKStickyPostsExpire/Editor
 * @since 1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MKStickyPostsExpireEditor' ) ){
    
    class MKStickyPostsExpireEditor{
        
        /**
		 * Construct
		 * @since 1.0
		 * @version 1.0
		 */
        public function __construct(){
            add_action( 'post_submitbox_misc_actions', array( $this, 'mk_sticky_posts_expire_add_expiration_field' ) );
            add_action( 'post_submitbox_start', array( $this, 'mk_sticky_posts_expire_add_expiration_field' ) );
            add_action( 'save_post', array( $this, 'mk_sticky_posts_expire_save_expiration' ) );
            add_action( 'load-post-new.php', array( $this, 'mk_sticky_posts_expire_scripts' ) );
            add_action( 'load-post.php', array( $this, 'mk_sticky_posts_expire_scripts' ) );
        }
        
        /**
		 * Determines if a post is expired
         * @access public
		 * @since 1.0
		 * @version 1.0
		 */
        public function mk_sticky_posts_expire_add_expiration_field() {

            global $post;

            if( ! empty( $post->ID ) ) {
                $expires = get_post_meta( $post->ID, 'mk_spe_expiration', true );
            }

            $label = ! empty( $expires ) ? date_i18n( 'Y-n-d', strtotime( $expires ) ) : __( 'never', MK_SPE_TEXT_DOMAIN );
            $date  = ! empty( $expires ) ? date_i18n( 'Y-n-d', strtotime( $expires ) ) : '';
            ?>
            <div id="mk-sep-expiration-wrap" class="misc-pub-section">
                <span>
                    <span class="wp-media-buttons-icon dashicons dashicons-calendar"></span>&nbsp;
                    <?php _e( 'Sticky Expires:', MK_SPE_TEXT_DOMAIN ); ?>
                    <b id="mk-sep-expiration-label"><?php echo $label; ?></b>
                </span>

                <a href="#" id="mk-sep-edit-expiration" class="mk-sep-edit-expiration hide-if-no-js">
                    <span aria-hidden="true"><?php _e( 'Edit', MK_SPE_TEXT_DOMAIN ); ?></span>&nbsp;
                    <span class="screen-reader-text"><?php _e( 'Edit date and time', MK_SPE_TEXT_DOMAIN ); ?></span>
                </a>
                
                <div id="mk-sep-expiration-field" class="hide-if-js">
                    <p>
                        <input type="text" name="mk-sep-expiration" id="mk-sep-expiration" value="<?php echo esc_attr( $date ); ?>" placeholder="yyyy-mm-dd"/>
                    </p>
                    <p>
                        <a href="#" class="mk-sep-hide-expiration button secondary"><?php _e( 'OK', MK_SPE_TEXT_DOMAIN ); ?></a>
                        <a href="#" class="mk-sep-hide-expiration cancel"><?php _e( 'Cancel', MK_SPE_TEXT_DOMAIN ); ?></a>
                    </p>
                </div>
                <?php wp_nonce_field( 'mk_spe_edit_expiration', 'mk_spe_expiration_nonce' ); ?>
            </div>
            <?php
        }
        
        /**
         * Save the posts's expiration date
         * @access public
         * @since 1.0
         * @return void
         */
        public function mk_sticky_posts_expire_save_expiration( $post_id = 0 ) {

        	if( empty( $_POST['mk_spe_expiration_nonce'] ) ) {
        		return;
        	}
        
        	if( ! wp_verify_nonce( $_POST['mk_spe_expiration_nonce'], 'mk_spe_edit_expiration' ) ) {
        		return;
        	}
        
        	if( ! current_user_can( 'edit_post', $post_id ) ) {
        		return;
        	}
        
        	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
        		return;
        	}
        
        	$expiration = ! empty( $_POST['mk-sep-expiration'] ) ? sanitize_text_field( $_POST['mk-sep-expiration'] ) : false;
        	if( $expiration ) {
        		update_post_meta( $post_id, 'mk_spe_expiration', $expiration );
        	} else {
        		delete_post_meta( $post_id, 'mk_spe_expiration' );
        	}
        
        }
        
        /**
         * Load our JS and CSS files
         * @access public
         * @since 1.0
         * @return void
         */
        public function mk_sticky_posts_expire_scripts() {
        	wp_enqueue_style( 'jquery-ui-css', MK_SPE_CSS . 'jquery-ui-fresh.min.css' );
        	wp_enqueue_script( 'jquery-ui-datepicker' );
        	wp_enqueue_script( 'jquery-ui-slider' );
        	wp_enqueue_script( 'mk-sep-expiration', MK_SPE_JS . 'script.js' );
        }
        
    }
    new MKStickyPostsExpireEditor();
}

function register_catalog_meta_boxes() {
    global $current_screen;
    // Make sure gutenberg is loaded before adding the metabox
    if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
        add_meta_box( 'mk_spe_expiration', __( 'Sticky Expires', MK_SPE_TEXT_DOMAIN ), 'product_data_visibility', 'post', 'side' );
    }
}
add_action( 'add_meta_boxes', 'register_catalog_meta_boxes' );

function product_data_visibility( $post ) {

    global $post;

            if( ! empty( $post->ID ) ) {
                $expires = get_post_meta( $post->ID, 'mk_spe_expiration', true );
            }

            $label = ! empty( $expires ) ? date_i18n( 'Y-n-d', strtotime( $expires ) ) : __( 'never', MK_SPE_TEXT_DOMAIN );
            $date  = ! empty( $expires ) ? date_i18n( 'Y-n-d', strtotime( $expires ) ) : '';
            ?>
            <div id="mk-sep-expiration-wrap" class="misc-pub-section">
                <span>
                    <span class="wp-media-buttons-icon dashicons dashicons-calendar"></span>&nbsp;
                    <?php _e( 'Sticky Expires:', MK_SPE_TEXT_DOMAIN ); ?>
                    <b id="mk-sep-expiration-label"><?php echo $label; ?></b>
                </span>

                <a href="#" id="mk-sep-edit-expiration" class="mk-sep-edit-expiration hide-if-no-js">
                    <span aria-hidden="true"><?php _e( 'Edit', MK_SPE_TEXT_DOMAIN ); ?></span>&nbsp;
                    <span class="screen-reader-text"><?php _e( 'Edit date and time', MK_SPE_TEXT_DOMAIN ); ?></span>
                </a>

                <div id="mk-sep-expiration-field" class="hide-if-js">
                    <p>
                        <input type="text" name="mk-sep-expiration" id="mk-sep-expiration" value="<?php echo esc_attr( $date ); ?>" placeholder="yyyy-mm-dd"/>
                    </p>
                    <p>
                        <a href="#" class="mk-sep-hide-expiration button secondary"><?php _e( 'OK', MK_SPE_TEXT_DOMAIN ); ?></a>
                        <a href="#" class="mk-sep-hide-expiration cancel"><?php _e( 'Cancel', MK_SPE_TEXT_DOMAIN ); ?></a>
                    </p>
                </div>
                <?php wp_nonce_field( 'mk_spe_edit_expiration', 'mk_spe_expiration_nonce' ); ?>
            </div>
            <?php
}
?>