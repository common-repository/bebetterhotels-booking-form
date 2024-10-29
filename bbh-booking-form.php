<?php
/**
 * Plugin Name: BeBetterHotels Booking Form
 * Plugin URI: https://github.com/bebetterhotels/bbh-booking-form
 * Description: BeBetterHotels Shortcode for the Booking Form.
 * Author: BeBetterHotels
 * Author URI: https://www.bebetterhotels.com/
 * Version: 1.0.14
 * Text Domain: bebetterhotels
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
define( 'BBH_DIR', plugin_dir_path( __FILE__ ) );
define( 'BBH_VERSION', '1.0.14' );
define( 'DS', DIRECTORY_SEPARATOR);

if ( ! class_exists( 'BBH_Template_Loader' ) ) {
	require plugin_dir_path( __FILE__ ) . 'includes/class-bbh-template-loader.php';
}

if ( ! function_exists( 'bbh_load_plugin_textdomain' ) ) {
	function bbh_load_plugin_textdomain() {
		load_plugin_textdomain(
			'bebetterhotels',
			false,
			basename( dirname( __FILE__ ) ) . '/languages/'
		);
	}
}

add_action( 'plugins_loaded', 'bbh_load_plugin_textdomain' );

if ( ! function_exists( 'bbh_options_init' ) ) {
	function bbh_options_init() {
		register_setting( 'bbh-options-group', 'bbh_url' );
		register_setting( 'bbh-options-group', 'bbh_customer' );
		register_setting( 'bbh-options-group', 'bbh_adults_field' );
		register_setting( 'bbh-options-group', 'bbh_childrens_field' );
		register_setting( 'bbh-options-group', 'bbh_show_childrens_field' );
		register_setting( 'bbh-options-group', 'bbh_calendar_theme' );
		register_setting( 'bbh-options-group', 'bbh_button_border_color' );
		register_setting( 'bbh-options-group', 'bbh_button_background_color' );
		register_setting( 'bbh-options-group', 'bbh_button_text_color' );
	}
}

add_action( 'admin_init', 'bbh_options_init' );

if ( ! function_exists( 'bbh_options_page' ) ) {
	function bbh_options_page() {
		add_submenu_page(
			'options-general.php',
			'BeBetterHotels',
			'BeBetterHotels',
			'manage_options',
			'bebetterhotels',
			'bbh_options_page_html'
		);
	}
}

add_action( 'admin_menu', 'bbh_options_page' );

if ( ! function_exists( 'bbh_get_options' ) ) {
	function bbh_get_options() {
		$options = array(
			'url'                     => esc_attr( get_option( 'bbh_url' ) ),
			'adults_field'            => esc_attr( get_option( 'bbh_adults_field' ) ),
			'childrens_field'         => esc_attr( get_option( 'bbh_childrens_field' ) ),
			'show_childrens_field'    => esc_attr( get_option( 'bbh_show_childrens_field' ) ),
			'calendar_theme'          => esc_attr( get_option( 'bbh_calendar_theme' ) ),
			'button_border_color'     => esc_attr( get_option( 'bbh_button_border_color' ) ),
			'button_background_color' => esc_attr( get_option( 'bbh_button_background_color' ) ),
			'button_text_color'       => esc_attr( get_option( 'bbh_button_text_color' ) ),
		);

		return $options;
	}
}

if ( ! function_exists( 'bbh_options_page_html' ) ) {
	function bbh_options_page_html() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$data = array(
			'options' => bbh_get_options(),
			'themes'  => array(),
		);

		$wp_scripts     = wp_scripts();
		$version        = $wp_scripts->registered['jquery-ui-core']->ver;
		$jquery_ui_path = plugin_dir_path( __FILE__ ) . "assets/css/jquery-ui/themes/{$version}/";

		$directories = new DirectoryIterator( $jquery_ui_path );

		foreach ( $directories as $fileinfo ) {
			if ( $fileinfo->isDir() && ! $fileinfo->isDot() ) {
				array_push( $data['themes'], $fileinfo->getFilename() );
			}
		}

		sort( $data['themes'] );

		$template_loader = new BBH_Template_Loader;
		$template_loader->set_template_data( $data )->get_template_part( 'options-general' );
	}
}

if ( ! function_exists( 'bbh_add_color_picker' ) ) {
	function bbh_add_color_picker( $hook ) {
		if ( is_admin() &&
			( 'settings_page_bebetterhotels' === $hook ) ) {

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'custom-script-handle',
				plugins_url( '/assets/js/admin.js', __FILE__ ),
				array( 'wp-color-picker' ),
				BBH_VERSION,
				true
			);
		}
	}
}

add_action( 'admin_enqueue_scripts', 'bbh_add_color_picker' );

if ( ! function_exists( 'bbh_enqueue_scripts' ) ) {
	function bbh_enqueue_scripts() {
		wp_enqueue_script(
			'bbh_js',
			plugins_url( '/assets/js/scripts.js', __FILE__ ),
			array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ),
			BBH_VERSION,
			true
		);
	}
}

if ( ! function_exists( 'bbh_enqueue_styles' ) ) {
	function bbh_enqueue_styles() {
		$wp_scripts = wp_scripts();

		$theme = esc_attr( get_option( 'bbh_calendar_theme' ) );
		$theme = empty( $theme ) ? 'ui-lightness' : $theme;

		$jquery_ui_path = plugins_url( "/assets/css/jquery-ui/themes/%s/{$theme}/jquery-ui.min.css", __FILE__ );

		wp_enqueue_style(
			'jquery-ui-theme',
			sprintf(
				$jquery_ui_path,
				$wp_scripts->registered['jquery-ui-core']->ver
			)
		);

		wp_enqueue_style(
			'bbh_css',
			plugins_url( '/assets/css/styles.css', __FILE__ ),
			array(),
			BBH_VERSION
		);
	}
}

add_action( 'wp_enqueue_scripts', 'bbh_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'bbh_enqueue_styles' );

if ( ! function_exists( 'bbh_shortcode' ) ) {
	function bbh_shortcode( $atts = array(), $content = null ) {

		$options = bbh_get_options();

		$locale = substr( get_locale(), 0, 2 );

		$default_atts = array(
			'url'                     => empty( $options['url'] ) ? 'https://clickandbook.net/' : $options['url'],
			'locale'                  => empty( $options['locale'] ) ? $locale : $options['locale'],
			'customer'                => '',
			'adults_field'            => empty( $options['adults_field'] ) ? 'required' : $options['adults_field'],
			'childrens_field'         => empty( $options['childrens_field'] ) ? 'required' : $options['childrens_field'],
			'show_childrens'          => empty( $options['show_childrens_field'] ) ? 'yes' : $options['show_childrens_field'],
			'calendar_theme'          => empty( $options['calendar_theme'] ) ? 'smothness' : $options['calendar_theme'],
			'button_border_color'     => empty( $options['button_border_color'] ) ? '#000' : $options['button_border_color'],
			'button_background_color' => empty( $options['button_background_color'] ) ? 'transparent' : $options['button_background_color'],
			'button_text_color'       => empty( $options['button_text_color'] ) ? '#000' : $options['button_text_color'],
			'current_date'            => gmdate( 'M y' ),
			'current_month'           => intval( gmdate( 'n' ) ),
		);

		$atts = shortcode_atts(
			$default_atts,
			$atts,
			'bebetterhotels'
		);

		$atts = array_map( 'strtolower', $atts );

		if ( empty( $atts['url'] ) ) {
			return __( 'Error - no url was specified', 'bebetterhotels' );
		}

		if ( empty( $atts['customer'] ) ) {
			return __( 'Error - The attribute customer was not specified', 'bebetterhotels' );
		}

		if ( ! in_array( $atts['show_childrens'], array( 'yes', 'no' ), true ) ) {
			return __( 'Error - The attribute show_childrens has a wrong value specified', 'bebetterhotels' );
		}

		ob_start();
		$template_loader = new BBH_Template_Loader;
		$template_loader->set_template_data( $atts )->get_template_part( 'booking-form' );

		return ob_get_clean();
	}
}

add_shortcode( 'bebetterhotels', 'bbh_shortcode' );

if ( ! function_exists( 'bbh_get_days' ) ) {
	/**
	 * Helper to get days
	 */
	function bbh_get_days( $from = 1, $to = 31 ) {
		$html = null;

		for ( $i = $from; $i <= $to; $i++ ) {
			$html .= "<option value='{$i}'>{$i}</option>";
		}

		return $html;
	}
}

if ( ! function_exists( 'bbh_get_months' ) ) {
	/**
	 * Helper to get days
	 */
	function bbh_get_months( $current_month, $count = 12 ) {
		$html = null;

		for ( $i = 1; $i <= $count; $i++ ) {
			$value = $current_month + $i;
			$desc  = gmdate( 'M y', strtotime( '+' . $i . 'month' ) );
			$html .= "<option value='{$value}'>{$desc}</option>";
		}

		return $html;
	}
}

if ( ! function_exists( 'bbh_generate_sels_date' ) ) {
	/**
	 * Helper to get hidden dropdowns for days and months
	 */
	function bbh_generate_sels_date( $name_days, $name_months ) {
		$current_date  = gmdate( 'M y' );
		$current_month = intval( gmdate( 'n' ) );

		$html = '<div class="sels_date">'
			. '<select id="' . $name_days . '" name="' . $name_days . '">'
			. bbh_get_days()
			. '</select>'
			. '<select id="' . $name_months . '" name="' . $name_months . '">'
			. '<option value="' . $current_month . '">' . $current_date . '</option>'
			. bbh_get_months( $current_month )
			. '</select>'
			. '</div>';

		return $html;
	}
}

if ( ! function_exists( 'bbh_dropdown_numeric_options' ) ) {
	/**
	 * Helper to generate numeric options
	 */
	function bbh_dropdown_numeric_options( $placeholder, $from = 1, $to = 10 ) {
		$html = '<option value="" selected="selected" disabled="disabled">'
				. __( $placeholder, 'bebetterhotels' )
				. '</option>';

		for ( $i = $from; $i <= $to; $i++ ) {
			$html .= "<option value='{$i}>'>{$i}</option>";
		}

		return $html;
	}
}
