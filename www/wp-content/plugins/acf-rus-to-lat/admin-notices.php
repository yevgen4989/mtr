<?php

	/**
	 * Admin Notices class
	 *
	 * @package WordPress
	 * @subpackage Admin Notices
	 */
	class WACFR_Admin_Notices {

		// Configuration
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Plugin suggestions
		 */
		private $days_dismissing_suggestions = 180; // 6 months reappear
		private $suggestions_message;
		private $suggestions;

		// Properties
		// ---------------------------------------------------------------------------------------------------
		/**
		 * Store missing plugins
		 */
		private $missing;
		/**
		 * Default prefix
		 * Can be changed by the external initialization.
		 */
		private $prefix = 'lbladn';
		/**
		 * Caller plugin file
		 */
		private $plugin_file;

		/**
		 * Single class instance
		 */
		private static $instance;

		// Initialization
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Create or retrieve instance
		 */
		public static function instance($plugin_file = null)
		{

			// Avoid direct calls
			if( !function_exists('add_action') ) {
				die;
			}

			// Check instance
			if( !isset(self::$instance) ) {
				self::$instance = new self($plugin_file);
			}

			// Done
			return self::$instance;
		}


		/**
		 * Constructor
		 */
		private function __construct($plugin_file = null)
		{
			// Main plugin file
			$this->plugin_file = isset($plugin_file)
				? $plugin_file
				: __FILE__;

			// Uninstall hook endpoint
			register_uninstall_hook($this->plugin_file, array(__CLASS__, 'uninstall'));

			// Prefix from namespace constant
			$this->prefix = 'wbcr_acfr_an_';
            $plugin_url = plugin_dir_url(__FILE__);

            if( in_array(get_locale(), array('ru_RU', 'bel', 'kk', 'uk', 'bg', 'bg_BG', 'ka_GE')) ) {

				$this->suggestions = array(
					'robin-image-optimizer' => array(
						'name' => 'Вы имеете [%unoptimzed_images%] неоптимизированных изображений!',
						'desc' => "Ускорьте свой сайт, уменьшите место на диске на 70%, улучшите SEO, уменьшив размеры файлов изображений, не теряя качества с помощью <b>Robin image optimizer</b>. Наш плагин оптимизации изображений полностью <b>бесплатный</b> и не имеет ограничений и лимитов на оптимизацию изображений.",
						'filename' => 'robin-image-optimizer.php',
                        'ico' => $plugin_url . 'assets/img/robin.jpg'
					),
				);
			} else {
                $this->suggestions = array(
                    'robin-image-optimizer' => array(
                        'name' => 'You have [%unoptimzed_images%] not optimized images.',
                        'desc' => "Reduce the size of images and files with <b>Robin Image Optimizer</b>. Speed up the website and save up to 70% of disk capacity with zero quality loss. Our plugin is 100% <b>free</b> and has no limitations in usage",
                        'filename' => 'robin-image-optimizer.php',
                        'ico' => $plugin_url . 'assets/img/robin.jpg'
                    ),
                );
			}

			// Check notices
			if( is_admin() ) {
				$this->check_timestamps();
				$this->check_suggestions();
			}
		}

		// Timestamp checks
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Creates the activation timestamp only if it does not exist
		 */
		private function check_timestamps()
		{
			$timestamp = $this->get_activation_timestamp();
			if( empty($timestamp) ) {
				$this->update_activation_timestamp();
			}
		}

		/**
		 * Check the suggestions dismissed timestamp
		 */
		private function check_suggestions()
		{

			// Compare timestamp
			$timestamp = $this->get_dismissed_timestamp('suggestions');
			if( empty($timestamp) || (time() - $timestamp) > ($this->days_dismissing_suggestions * 86400) ) {

				// Check AJAX submit
				if( defined('DOING_AJAX') && DOING_AJAX ) {
					add_action('wp_ajax_' . $this->prefix . '_dismiss_suggestions', array(
						&$this,
						'dismiss_suggestions'
					));
					// Admin area (except install or activate plugins page)
				} elseif( !in_array(basename($_SERVER['PHP_SELF']), array(
					'plugins.php',
					'plugin-install.php',
					'update.php'
				))
				) {
					add_action('wp_loaded', array(&$this, 'load_notices_suggestions'), PHP_INT_MAX);
				}
			}
		}

		// Loaders
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Check and load the sugestions notices
		 */
		public function load_notices_suggestions()
		{
			// Check the disable nag constant
			if( $this->disable_nag_notices() ) {
				return;
			}



			// Collect missing plugins
			$this->missing = $this->get_missing_plugins();
			if( !empty($this->missing) && is_array($this->missing) ) {
                wp_enqueue_style('wacfr_notices', plugin_dir_url(__FILE__).'assets/css/notices.css');
				add_action('admin_footer', array(&$this, 'admin_footer_suggestions'));
				add_action('admin_notices', array(&$this, 'admin_notices_suggestions'));
			}
		}

		// Admin Notices display
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Suggestions display
		 */
		public function admin_notices_suggestions()
		{
			$plugin_data = get_plugin_data($this->plugin_file);


			?>
			<div class="<?php echo esc_attr($this->prefix); ?>-dismiss-suggestions wacrf-notice-wrapper notice notice-success is-dismissible" data-nonce="<?php echo esc_attr(wp_create_nonce($this->prefix . '-dismiss-suggestions')); ?>">
                <?if(!empty($this->suggestions_message)){?>
                    <p><?php echo str_replace('%plugin%', $plugin_data['Name'], $this->suggestions_message); ?></p>
                <?}?>
				<?php foreach($this->missing as $plugin) : ?>
                    <?php
                    $plugin_path = $plugin . '/'. $this->suggestions[$plugin]['filename'];
                    $plugin_installed = is_file(WP_PLUGIN_DIR. '/' .$plugin_path);
                    ?>
                    <?php if($plugin === 'robin-image-optimizer'){
                            $unoptimzed_images = wp_count_posts('attachment')->inherit;
                            $this->suggestions[$plugin]['name'] = str_replace('%unoptimzed_images%', $unoptimzed_images, $this->suggestions[$plugin]['name']);
                        }?>



                            <div class='wacrf-notice'>
                                <div class='wacrf-leftcol'><img class='wacrf-plugin-ico' src='<?php echo $this->suggestions[$plugin]['ico']; ?>' alt='<?php echo $plugin; ?>'></div>
                                <div class='wacrf-content'>
                                    <div class='wacrf-title'><?php echo $this->suggestions[$plugin]['name']; ?></div>
                                    <div class="wacrf-text-cols">
                                        <div class='wacrf-description'><?php echo $this->suggestions[$plugin]['desc']; ?></div>
                                        <div class='wacrf-rightcol'>
                                            <a class='wacrf-install-btn' href='<?php echo esc_url($this->get_install_url($plugin)); ?>'>
                                                <?php if( in_array(get_locale(), array(
                                                    'ru_RU',
                                                    'bel',
                                                    'kk',
                                                    'uk',
                                                    'bg',
                                                    'bg_BG',
                                                    'ka_GE'
                                                )) ):

                                                    if($plugin_installed):?>
                                                        Активировать
                                                    <?php else: ?>
                                                        Установить и оптимизировать
                                                    <?php endif; ?>

                                                <?php else: ?>

                                                    <?php if($plugin_installed):?>
                                                        Activate
                                                    <?php else: ?>
                                                        Install and optimize
                                                    <?php endif; ?>

                                                <?php endif ?>
                                            </a>
                                        </div>
                                    </div>

                                </div>

                            </div>



					<?php endforeach; ?>
			</div>
		<?php
		}

		// AJAX Handlers
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Dismiss suggestions
		 */
		public function dismiss_suggestions()
		{
			if( !empty($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], $this->prefix . '-dismiss-suggestions') ) {
				$this->update_dismissed_timestamp('suggestions');
			}
		}


		// Plugins information retrieval
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Retrieve uninstalled plugins
		 */
		private function get_missing_plugins()
		{

			// Initialize
			$inactive = array();

			// Check plugins directory
			$directories = array_merge(self::get_mu_plugins_directories(), self::get_plugins_directories());
			if( !empty($directories) ) {
				$required = array_keys($this->suggestions);
				foreach($required as $plugin) {
					if( !in_array($plugin, $directories) ) {
						$inactive[] = $plugin;
					}
				}
			}

			// Check inactives
			if( empty($inactive) ) {
				return false;
			}

			// Done
			return $inactive;
		}


		/**
		 * Collects all active plugins
		 */
		private function get_plugins_directories()
		{

			// Initialize
			$directories = array();

			// Plugins split directory
			$split = '/' . basename(WP_CONTENT_DIR) . '/' . basename(WP_PLUGIN_DIR) . '/';

			// Multisite plugins
			if( is_multisite() ) {
				$ms_plugins = wp_get_active_network_plugins();
				if( !empty($ms_plugins) && is_array($ms_plugins) ) {
					foreach($ms_plugins as $file) {
						$directory = explode($split, $file);
						$directory = explode('/', ltrim($directory[1], '/'));
						$directory = $directory[0];
						if( !in_array($directory, $directories) ) {
							$directories[] = $directory;
						}
					}
				}
			}

			// Active plugins
			$plugins = wp_get_active_and_valid_plugins();
			if( !empty($plugins) && is_array($plugins) ) {
				foreach($plugins as $file) {
					$directory = explode($split, $file);
					$directory = explode('/', ltrim($directory[1], '/'));
					$directory = $directory[0];
					if( !in_array($directory, $directories) ) {
						$directories[] = $directory;
					}
				}
			}

			// Done
			return $directories;
		}


		/**
		 * Retrieve mu-plugins directories
		 */
		private function get_mu_plugins_directories()
		{

			// Initialize
			$directories = array();

			// Dependencies
			if( !function_exists('get_plugins') ) {
				require_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			// Retrieve mu-plugins
			$plugins = get_plugins('/../mu-plugins');
			if( !empty($plugins) && is_array($plugins) ) {
				foreach($plugins as $path => $info) {
					$directory = dirname($path);
					if( !in_array($directory, array('.', '..')) ) {
						$directories[] = $directory;
					}
				}
			}

			// Done
			return $directories;
		}


		/**
		 * Plugin install/activate URL
		 */
		private function get_install_url($plugin)
		{
			if( !isset($this->suggestions[$plugin]['filename']) && isset($this->suggestions[$plugin]['url']) ) {
				return $this->suggestions[$plugin]['url'];
			}

			// Check existing plugin
			$exists = @file_exists(WP_PLUGIN_DIR . '/' . $plugin);

			// Activate
			if( $exists ) {

				// Existing plugin
				$path = $plugin . '/' . $this->suggestions[$plugin]['filename'];

				return admin_url('plugins.php?action=activate&plugin=' . $path . '&_wpnonce=' . wp_create_nonce('activate-plugin_' . $path));
				// Install
			} else {

				// New plugin
				return admin_url('update.php?action=install-plugin&plugin=' . $plugin . '&_wpnonce=' . wp_create_nonce('install-plugin_' . $plugin));
			}
		}

		/**
		 * Determines the admin notices display
		 */
		private function disable_nag_notices()
		{
			return (defined('DISABLE_NAG_NOTICES') && DISABLE_NAG_NOTICES);
		}

		// Plugin related
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Plugin uninstall hook
		 */
		public static function uninstall()
		{
			$admin_notices = self::instance();
			$admin_notices->delete_activation_timestamp();
			$admin_notices->delete_dismissed_timestamp('suggestions');
		}



		// Activation timestamp management
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Retrieves the plugin activation timestamp
		 */
		private function get_activation_timestamp()
		{
			return (int)get_option($this->prefix . '_activated_on');
		}

		/**
		 * Updates activation timestamp
		 */
		private function update_activation_timestamp()
		{
			update_option($this->prefix . '_activated_on', time(), true);
		}

		/**
		 * Removes activation timestamp
		 */
		private function delete_activation_timestamp()
		{
			delete_option($this->prefix . '_activated_on');
		}

		// Dismissed timestamp management
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Current timestamp by key
		 */
		private function get_dismissed_timestamp($key)
		{
			return (int)get_option($this->prefix . '_dismissed_' . $key . '_on');
		}

		/**
		 * Update with the current timestamp
		 */
		private function update_dismissed_timestamp($key)
		{
			update_option($this->prefix . '_dismissed_' . $key . '_on', time(), true);
		}

		/**
		 * Removes dismissied option
		 */
		private function delete_dismissed_timestamp($key)
		{
			delete_option($this->prefix . '_dismissed_' . $key . '_on');
		}

		// Javascript code
		// ---------------------------------------------------------------------------------------------------

		/**
		 * Footer script for Suggestions
		 */
		public function admin_footer_suggestions()
		{
			?>
			<script type="text/javascript">
				jQuery(function($) {

					$(document).on('click', '.<?php echo $this->prefix; ?>-dismiss-suggestions .notice-dismiss', function() {
						$.post(ajaxurl, {
							'action': '<?php echo $this->prefix; ?>_dismiss_suggestions',
							'nonce': $(this).parent().attr('data-nonce')
						});
					});

				});
			</script>
		<?php
		}
	}
