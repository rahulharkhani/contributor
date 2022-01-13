<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://thatpeoples.com/
 * @since      1.0.0
 *
 * @package    Contributor
 * @subpackage Contributor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contributor
 * @subpackage Contributor/admin
 * @author     Rahul Harkhani <rahul.l.harkhani@doyenhub.com>
 */
class Contributor_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_init', array( 'Contributor_Admin', 'admin_init' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/contributor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/contributor-admin.js', array( 'jquery' ), $this->version, false );

	}

	public static function admin_init() {

		load_plugin_textdomain( 'contributor' );
		add_meta_box( 'contributor-meta-box', __('Contributors List', 'contributor'), array( 'Contributor_Admin', 'contributor_meta_box_render' ), 'post', 'normal' );
		add_action( 'save_post', array( 'Contributor_Admin', 'save_post_contributor_data' ) );

		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
		
			wp_add_privacy_policy_content(
				__( 'Contributor', 'contributor' ),
				__( 'We collect information about post authors who selected on posts.', 'contributor' )
			);
		
		}
	}

	public static function contributor_meta_box_render() {

		global $wpdb;
		$post_id = get_the_ID();
		$contributorData = get_post_meta( $post_id, '_contributors', true );
		$args = array(
			'role__in'    => array('administrator', 'author', 'editor'),
			'orderby' => 'user_nicename',
			'order'   => 'ASC'
		);
		$users = get_users( $args );

		?>
		<p>select author from below list to assign this post</p>
			<form id="contributorForm" name="contributorForm">
			<ul>
		
				<?php
				foreach ($users as $user) {
					?>

					<input type="checkbox" id="<?php echo esc_html( $user->display_name ); ?>" name="contributorData[]" value="<?php echo esc_html( $user->ID ); ?>" <?php if (!empty($contributorData) ) { checked(in_array( $user->ID, $contributorData ), true); } ?> />
					<label for="<?php echo esc_html( $user->display_name ); ?>"><?php echo esc_html( $user->display_name ) . ' [' . esc_html( $user->user_email ) . ']'; ?></label><br>
					
					<?php
				}
				?>
		
			</ul>
		</form>
		<?php

	}

	public static function save_post_contributor_data( $post_id ){
		global $post; 
		if ($post->post_type != 'post'){
			return;
		}
		
		$contributorData = isset( $_POST['contributorData'] ) ? (array) $_POST['contributorData'] : array();
		if(isset($contributorData) && !empty($contributorData)) {
			
			$contributorData = array_map( 'sanitize_text_field', $contributorData );
			update_post_meta( $post_id, '_contributors', $contributorData );

		} else {
			
			update_post_meta( $post_id, '_contributors', '' );

		}
	}

}
