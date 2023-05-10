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
 * Dicom Viewer class.
 */
class dicomLinkDicomViewer_Viewer
{

    public $parent = null;
    private $dicomViewerSDKScript = array(
        "dicomViewer-live" =>  'https://cdn.dicom.link/dicomViewer/1.6.6/dicomViewer.min.js'
    );

    function __construct($parent)
    {
        $this->parent = $parent;
        $this->getOpts();

        // add 'dcm' DICOM view shortcode
        add_shortcode('dicomViewer', array($this, 'embed_viewer'));
        // enqueue scripts on front end
        add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
    }

    function getOpts()
    {

        $prefix = $this->parent->settings->base;
        $is_debug = get_option($prefix . 'is_debug');

        if ($is_debug === 'on') {

            $setting = get_option($prefix . 'debug_dicomviewer_sdk');
            if ($this->debugCanUse($setting)) {
                $_scrs = explode('|', $setting);
                $this->dicomViewerSDKScript = array();

                foreach ($_scrs as $_id => $_script) {
                    $this->dicomViewerSDKScript  = array_push_assoc($this->dicomViewerSDKScript, "dicomViewer-".$_id, $_script);
                }
            }
        }
    }

    function debugCanUse($setting)
    {
        if ($setting !== false)
            if (!(ctype_space($setting) || $setting == ''))
                return true;
        return false;
    }

    function embed_viewer($atts, $content = null)
    {

        if (empty($atts['loadzip'])) {
            return;
        }

        if (empty($atts['min-height'])) {
            $minHeight = '450px';
        } else {
            $minHeight =  $atts['min-height'];
        }

        $fileList = array_map('trim', explode(',', $atts['loadzip']));
        $urls = '"' . implode('","', $fileList) . '"';

        wp_enqueue_script('dicomViewer-inline');


        foreach ($this->dicomViewerSDKScript as $_id => $_script) {
            wp_enqueue_script($_id);
        }


        $prefix = $this->parent->settings->base;
        $licenceKey = get_option($prefix . 'license_key');

        $script = '
        window.addEventListener(\'load\', (event) => {

            
        // ' . $minHeight . '
        //  ' . $urls . '
        dicomViewer = dicomViewer || {};
        licenceKey = "' . $licenceKey . '"
        dicomViewer.License = JSON.parse(atob(licenceKey));
            //dicomViewer.parseDOM();

        var t = new dicomViewer.loadStudy();
        t.init(\'#test-container-id\').then((v) => {
            // dicomViewer.viewers.push(t);
            document.querySelector(\'#dicomViewer\').style.minHeight = "' . $minHeight . '";
            t.LoadZipURI(' . $urls . ');
        });

            });
        ';

        // add script to queue
        wp_add_inline_script('dicomViewer-inline', $script);

        // create html
        $html = '
        <!-- dicom.link dicomViewer -->
        
        <div class="test-container" id="test-container-id" style="min-height: ' . $minHeight . ';" ></div>
        <!-- end dicomViewer -->
        ';

        return $html;
    }


    /**
     * Enqueue scripts for the front end.
     * @see https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
     */
    function wp_enqueue_scripts()
    {
        wp_register_script('dicomViewer-inline', '');

        foreach ($this->dicomViewerSDKScript as $_id => $_script) {
            wp_register_script($_id, $_script, null, null);
        }
    }
}

function array_push_assoc($array, $key, $value)
{
    $array[$key] = $value;
    return $array;
}

