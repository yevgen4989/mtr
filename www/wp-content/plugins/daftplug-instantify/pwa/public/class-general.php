<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaPublicGeneral')) {
    class daftplugInstantifyPwaPublicGeneral {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;

        public $daftplugInstantifyPwaPublic;

    	public function __construct($config, $daftplugInstantifyPwaPublic) {
    		$this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->settings = $config['settings'];

            $this->daftplugInstantifyPwaPublic = $daftplugInstantifyPwaPublic;
    	}
    }
}