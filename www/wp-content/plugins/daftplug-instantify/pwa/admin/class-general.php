<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaAdminGeneral')) {
    class daftplugInstantifyPwaAdminGeneral {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;

    	public function __construct($config) {
    		$this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->settings = $config['settings'];

            add_action('add_meta_boxes', array($this, 'addMetaBoxes'), 10, 2);
            add_action('save_post', array($this, 'saveMetaBox'), 10, 2);
    	}

        public function renderMetaBoxContent($post, $callbackArgs) {
            $disablePwa = get_post_meta($post->ID, 'disablePwa', true);
            wp_nonce_field("{$this->optionName}_pwa_meta_nonce", "{$this->optionName}_pwa_meta_nonce");
            ?>
            <label>
                <input type="checkbox" name="disablePwa" value="disable" <?php checked($disablePwa, 'disable'); ?>>
                <?php esc_html_e('Disable PWA on this post', $this->textDomain); ?>
            </label>
            <?php
        }

        public function addMetaBoxes($postType, $post)  {
            if (daftplugInstantify::getSetting('pwaOnAll') == 'on' || in_array($post->post_type, (array)daftplugInstantify::getSetting('pwaOnPostTypes'))) {
                add_meta_box("{$this->optionName}_pwa_meta_box", esc_html__('PWA', $this->textDomain), array($this, 'renderMetaBoxContent'), $postType, 'side', 'default', array());
            }
        }

        public function saveMetaBox($postId) {
            $isAutosave = wp_is_post_autosave($postId);
            $isRevision = wp_is_post_revision($postId);
            $isValidNonce = (isset($_POST["{$this->optionName}_pwa_meta_nonce"]) && wp_verify_nonce($_POST["{$this->optionName}_pwa_meta_nonce"], $this->pluginBasename)) ? 'true' : 'false';

            if ($isAutosave || $isRevision || !$isValidNonce) {
                return;
            }

            if (isset($_POST['disablePwa'])) {
                $disablePwa = $_POST['disablePwa'];
            } else {
                $disablePwa = 'enable';
            }
            
            update_post_meta($postId, 'disablePwa', $disablePwa);
        }
        
        public function getPostTypes() {
            return array_values(
                        get_post_types(
                            array(
                               'public' => true,
                            ),
                            'names'
                        )
                    );
        }

        public function getPageTypes() {
            if (get_option('show_on_front') === 'page') {
                $pageTypes['is_front_page'] = array(
                    'label'  => __('Homepage', $this->textDomain),
                );

                $pageTypes['is_home'] = array(
                    'label' => __('Blog', $this->textDomain),
                );
            } else {
                $pageTypes['is_home'] = array(
                    'label' => __('Homepage', $this->textDomain),
                );
            }
    
            $pageTypes = array_merge(
                $pageTypes,
                array(
                    'is_author'  => array(
                        'label'  => __('Author', $this->textDomain),
                        'parent' => 'is_archive',
                    ),
                    'is_search'  => array(
                        'label' => __('Search', $this->textDomain),
                    ),
                    'is_404'     => array(
                        'label' => __('Not Found (404)', $this->textDomain),
                    ),
                )
            );
    
            if (taxonomy_exists('category')) {
                $pageTypes['is_category'] = array(
                    'label'  => get_taxonomy('category')->labels->name,
                );
            }

            if (taxonomy_exists('post_tag')) {
                $pageTypes['is_tag'] = array(
                    'label'  => get_taxonomy('post_tag')->labels->name,
                );
            }

            return $pageTypes;
        }
    }
}