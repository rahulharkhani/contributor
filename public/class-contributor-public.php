<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://thatpeoples.com/
 * @since      1.0.0
 *
 * @package    Contributor
 * @subpackage Contributor/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Contributor
 * @subpackage Contributor/public
 * @author     Rahul Harkhani <rahul.l.harkhani@doyenhub.com>
 */
class Contributor_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_filter( 'the_content', array($this, 'contributor_content_filter'), 1 );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Contributor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Contributor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/contributor-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Contributor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Contributor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/contributor-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Display Contributor name for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function contributor_content_filter( $content ) {

		global $post;

		if (is_singular() && $post->post_type == 'post') {
			
			$post_id = get_the_ID();
			$contributorData = maybe_unserialize( get_post_meta( $post_id, '_contributors', true ) );
			
			if(!empty($contributorData)) {
			
				$content .= '<div id="authorBox">Contributors : ';
			
				foreach ( $contributorData as $contributor ) {

					$firstName = get_user_meta( $contributor, 'first_name', true );
					$lastName = get_user_meta( $contributor, 'last_name', true );
					$nickName = get_user_meta( $contributor, 'nickname', true );
					$authorImage = get_avatar_url( $contributor );
			
					if( !empty($firstName) && !empty($lastName) ) {
			
						$authorName = $firstName . " " . $lastName;
			
					} else {
			
						$authorName = $nickName;
			
					}
					
					$content .= '<div><h3><img src="'.$authorImage.'" /><a href="'.get_author_posts_url($contributor).'">' . $authorName . '</a></h3></div>';
			
				}
			
				$content .= '</div>';
			
			}
		
		}
		return $content;
	}

}
