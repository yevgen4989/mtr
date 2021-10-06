<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyAmpPublicGeneral')) {
    class daftplugInstantifyAmpPublicGeneral {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;

        public $daftplugInstantifyAmpPublic;

    	public function __construct($config, $daftplugInstantifyAmpPublic) {
    		$this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->settings = $config['settings'];

            $this->daftplugInstantifyAmpPublic = $daftplugInstantifyAmpPublic;

            add_action('admin_bar_menu', array($this, 'removeSettingsAdminMenu'), 200);
    	}

        public function removeSettingsAdminMenu($adminBar) {
            if (amp_is_canonical() || !amp_is_available()) {
                return;
            }

            $adminBar->remove_node('amp-settings');
        }
    }
}