<?php
/**
 * Plugin Name: dicomLinkDicomViewer
 * Version: 1.7.0
 * Plugin URI: https://dicom.link/wordpress
 * Description: DICOM viewer for Wordpress: embed studies in pages and posts.
 * Author: dicom.link
 * Author URI: https://dicom.link/wordpress
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: dicomlinkdicomviewer
 *
 * @package dicomLinkDicomViewer
 * @author dicom.link
 * @since 1.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files.
require_once 'includes/class-dicomlinkdicomviewer.php';
require_once 'includes/class-dicomlinkdicomviewer-settings.php';
require_once 'includes/class-dicomlinkdicomviewer-viewer.php';
require_once 'includes/class-dicomlinkdicomviewer-block-uploader.php';

// Load plugin libraries.
require_once 'includes/lib/class-dicomlinkdicomviewer-admin-api.php';

/**
 * Returns the main instance of dicomLinkDicomViewer to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object dicomLinkDicomViewer
 */
function dicomlinkdicomviewer() {
	$instance = dicomLinkDicomViewer::instance( __FILE__, '1.7.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = dicomLinkDicomViewer_Settings::instance( $instance );
	}

	$instance->viewer = new dicomLinkDicomViewer_Viewer($instance );
	$instance->vaultUploader = new dicomLinkDicomViewer_block_Uploader($instance );
	return $instance;
}

dicomlinkdicomviewer();
