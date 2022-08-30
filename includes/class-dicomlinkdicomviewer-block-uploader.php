<?php

/**
 * 
 *
 * @package WordPress Plugin Template/Includes
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Dicom Vault Uploader class.
 */
class dicomLinkDicomViewer_block_Uploader
{

    public $parent = null;
    private $vaultScript = 'https://cdn.dicom.link/vault/v1/main.js';
    private $vaultAddress = '';
    private $vaultUserId = '';

    function __construct($parent)
    {
        $this->parent = $parent;
        $this->getOpts();

        wp_enqueue_script(
            'vault-script',
            $this->vaultScript,
            array('wp-blocks', 'wp-element'),
            null
        );
        if (is_admin()) {
        }

        add_action('init', array($this, 'vault_uploader_register_block'));
        add_action('wp_enqueue_scripts', array($this, 'script_enqueue_if_block_is_present')); // Can be loaded in the both in head and footer

    }

    function getOpts()
    {

        $prefix = $this->parent->settings->base;

        $is_debug = get_option($prefix . 'is_debug');

        $this->vaultAddress = get_option($prefix . 'vault_address');
        $this->vaultUserId = get_option($prefix . 'vault_user_id');

        if ($is_debug === 'on') {


            $setting = get_option($prefix . 'debug_vault_address');
            if ($this->debugCanUse($setting))
                $this->vaultAddress = $setting;


            $setting = get_option($prefix . 'debug_vault_user_id');
            if ($this->debugCanUse($setting))
                $this->vaultUserId = $setting;

            $setting = get_option($prefix . 'debug_sdk');
            if ($this->debugCanUse($setting))
                $this->vaultScript = $setting;
        }
    }

    function vault_uploader_register_block()
    {

        if (!function_exists('register_block_type')) {
            // Gutenberg is not active.
            return;
        }
        register_block_type(__DIR__ . '/vault-uploader', array(
            'render_callback' => function ($attribs, $content) {

                wp_register_script('main-script-inline', '');
                wp_enqueue_script('main-script-inline', '');

                $mainScript = '
                
                window.onload = function () {
                    let opts = {
                        apiKey: "mykey",
                        server: \'' . $this->vaultAddress  . '\',
                        userId: \'' . $this->vaultUserId  . '\',
                    }
                    Vault.Init(opts);
                };

                ';
                wp_add_inline_script('main-script-inline', $mainScript);

                wp_enqueue_script(
                    'vault-script',
                    $this->vaultScript,
                    array('wp-blocks', 'wp-element'),
                    null
                );

                wp_register_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css', array(), $this->_version);
                wp_enqueue_style('font-awesome');


                return $content;
            }
        ));
    }

    function script_enqueue_if_block_is_present()
    {
        if (has_block('dicomlink/vault-uploader')) {
        }
    }

    function debugCanUse($setting)
    {
        if ($setting !== false)
            if (!(ctype_space($setting) || $setting == ''))
                return true;
        return false;
    }
}
