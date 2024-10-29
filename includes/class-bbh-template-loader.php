<?php

if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
	require plugin_dir_path( __FILE__ ) . 'class-gamajo-template-loader.php';
}

/**
 * Template loader for BeBetterHotels.
 *
 * Only need to specify class properties here.
 *
 * @package BeBetterHotels
 * @author  BeBetterHotels
 */
class BBH_Template_Loader extends Gamajo_Template_Loader {
	/**
	 * Prefix for filter names.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $filter_prefix = 'bbh';

	/**
	 * Directory name where custom templates for this plugin should be found in the theme.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $theme_template_directory = 'bbh';

	/**
	 * Reference to the root directory path of this plugin.
	 *
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $plugin_directory = BBH_DIR;

	/**
	 * Directory name where templates are found in this plugin.
	 *
	 * Can either be a defined constant, or a relative reference from where the subclass lives.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected $plugin_template_directory = 'templates';
}
