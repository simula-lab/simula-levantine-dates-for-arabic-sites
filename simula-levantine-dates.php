<?php
/**
 * Plugin Name: Levantine Dates for Arabic Sites
 * Plugin URI: https://github.com/simula-lab/levantine-dates-for-arabic-wp
 * Description: Converts Arabic numerals in dates into Arabic-Indic numerals, and Modern Standard Arabic month names to Levantine equivalent
 * Version: 1.0.0
 * Author: SiMULA
 * Author URI: https://simulalab.org/
 * License: GPL2
 * Text Domain: levantine-dates-for-arabic-wp
 * Domain Path: /languages
 *
 */

// Abort, if this file is called directly.
if ( !defined( 'WPINC' ) ) {
	die;
}

// Define the arrays of month names as constants
const MODERN_STANDARD_ARABIC_MONTH_NAMES = array(
    'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 
    'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
);

const LEVANTINE_MONTH_NAMES = array(
    'كانون الثاني', 'شباط', 'آذار', 'نيسان', 'أيار', 'حزيران', 
    'تموز', 'آب', 'أيلول', 'تشرين الأول', 'تشرين الثاني', 'كانون الأول'
);

const LEVANTINE_MSA_MONTH_NAMES = array(
    'كانون الثاني/يناير', 'شباط/فبراير', 'آذار/مارس', 'نيسان/أبريل', 'أيار/مايو', 'حزيران/يونيو', 
    'تموز/يوليو', 'آب/أغسطس', 'أيلول/سبتمبر', 'تشرين الأول/أكتوبر', 'تشرين الثاني/نوفمبر', 'كانون الأول/ديسمبر'
);


const MSA_LEVANTINE_MONTH_NAMES = array(
    'يناير/كانون الثاني', 'فبراير/شباط', 'مارس/آذار', 'أبريل/نيسان', 'مايو/أيار', 'يونيو/حزيران', 
    'يوليو/تموز', 'أغسطس/آب', 'سبتمبر/أيلول', 'أكتوبر/تشرين الأول', 'نوفمبر/تشرين الثاني', 'ديسمبر/كانون الأول'
);

/**
 * Generic function to convert month names
 *
 * @param string $string The string containing dates
 * @param array $source The source array of month names
 * @param array $target The target array of month names for conversion
 * @return string The string with month names converted
 */
function convert_month_names( $string, $source, $target ) {
    return str_replace( $source, $target, $string );
}


// Modify the existing functions to use the new generic function
function convert_msa_month_names_to_levantine_month_names( $string ) {
    return convert_month_names( $string, MODERN_STANDARD_ARABIC_MONTH_NAMES, LEVANTINE_MONTH_NAMES );
}

function convert_msa_month_names_to_levantine_msa_month_names( $string ) {
    return convert_month_names( $string, MODERN_STANDARD_ARABIC_MONTH_NAMES, LEVANTINE_MSA_MONTH_NAMES );
}

function convert_msa_month_names_to_msa_levantine_month_names( $string ) {
    return convert_month_names( $string, MODERN_STANDARD_ARABIC_MONTH_NAMES, MSA_LEVANTINE_MONTH_NAMES );
}

/**
 * Replaces Arabic numerals with Arabic-Indic numberals in given string.
 *
 * @since 1.0.0
 *
 * @param string	$string A string containing Arabic numerals
 * @return string	A string consisting of the input string with Arabic numerals replaced with Arabic-Indic numerals
 */
function convert_arabic_numerals_to_arabic_indic( $string ) {
	$arabic_indic_numerals = array('۰', '۱', '۲', '۳', '٤', '۵', '٦', '۷', '۸', '۹', ',', '،');
	$arabic_numerals = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', ',');
	return str_replace( $arabic_numerals, $arabic_indic_numerals, $string );
}


/**
 * If the blog language is Arabic, hook into date and time functions
 * and convert the Arabic numerals to Arabic-Indic numberals, 
 * and convert the Modern Standard Arabic month names to Levantine equivalent
 *
 * @since 1.0.0
 *
 * @param string	$the_date the date as returned by get_the_time or get_the_date
 * @return string	The date with Arabic-Indic numerals and Levantine month names
 */
function make_levantine_date( $the_date, $force=null ) {

	$options = get_option('simula_levantine_dates_settings');
    $format = isset($options['simula_levantine_dates_field_format']) ? $options['simula_levantine_dates_field_format'] : 'levantine';
	$use_arabic_indic_numerals = isset($options['simula_levantine_dates_field_arabic_indic']) && $options['simula_levantine_dates_field_arabic_indic'];

	if ( get_bloginfo( 'language' ) == 'ar' || $force ) {
		if ( $use_arabic_indic_numerals ){
			$the_date = convert_arabic_numerals_to_arabic_indic( $the_date );
		}
		switch ($format) {
            case 'levantine':
                $the_date = convert_msa_month_names_to_levantine_month_names( $the_date );
                break;
            case 'levantine_msa':
                $the_date = convert_msa_month_names_to_levantine_msa_month_names( $the_date );
                break;
            case 'msa_levantine':
                $the_date = convert_msa_month_names_to_msa_levantine_month_names( $the_date );
                break;
        }
	}
	return $the_date;
}

if( !is_admin() ){
	add_filter( 'get_the_time', 'make_levantine_date' );
	add_filter( 'get_the_date', 'make_levantine_date' );
	add_filter( 'date_i18n', 'make_levantine_date' );
}

/**
 * Shortcode for inserting a the Levantine date of the current post.
 * [levantine_date]
 *
 * @since 1.0.0
 *
 * @param array $atts Shortcode attributes
 * @return string The post date in Levantine Arabic
 */
function simula_levantine_date_shortcode( $atts ) {
	$the_date = get_the_date();
	return make_levantine_date( $the_date, true );
}
add_shortcode( 'levantine_date', 'simula_levantine_date_shortcode' );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function simula_levantine_date_load_textdomain() {
  load_plugin_textdomain( 'levantine-dates-for-arabic-wp', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'simula_levantine_date_load_textdomain' );


/**
 * Create the Options Page
 */
function simula_levantine_dates_add_admin_menu() {
    add_options_page(
        __('Levantine Dates Settings', 'levantine-dates-for-arabic-wp' ),
        __('Levantine Dates', 'levantine-dates-for-arabic-wp' ),
        'manage_options',
        'simula_levantine_dates',
        'simula_levantine_dates_options_page'
    );
}
add_action('admin_menu', 'simula_levantine_dates_add_admin_menu');

function simula_levantine_dates_options_page() {
    ?>
    <div class="wrap">
    <h2><?php _e('Levantine Dates Settings', 'levantine-dates-for-arabic-wp'); ?></h2>
    <form action="options.php" method="post">
        <?php
        settings_fields('simula_levantine_dates_plugin_options');
        do_settings_sections('simula_levantine_dates_plugin');
        submit_button();
        ?>
        <!-- Add your promotional paragraph here -->
        <p>
            <?php _e('This plugin is brought to you by Simula.', 'levantine-dates-for-arabic-wp'); ?>
            <?php _e('Visit us at ', 'levantine-dates-for-arabic-wp'); ?>
            <a href="https://simulalab.org" target="_blank">
                <?php _e('https://simulalab.org', 'levantine-dates-for-arabic-wp'); ?>
            </a>
            <?php _e('to learn more about what we do!', 'levantine-dates-for-arabic-wp'); ?>
        </p>
    </form>
    </div>
    <?php
}

/**
 * Register Settings
 */

 function simula_levantine_dates_settings_init() {
    register_setting('simula_levantine_dates_plugin_options', 'simula_levantine_dates_settings');

    add_settings_section(
        'simula_levantine_dates_plugin_section',
        __('Choose months format', 'levantine-dates-for-arabic-wp'),
        'simula_levantine_dates_settings_section_callback',
        'simula_levantine_dates_plugin'
    );

    add_settings_field(
        'simula_levantine_dates_field_format',
        __('Month format options', 'levantine-dates-for-arabic-wp'),
        'simula_levantine_dates_field_format_render',
        'simula_levantine_dates_plugin',
        'simula_levantine_dates_plugin_section'
    );

	add_settings_field(
		'simula_levantine_dates_field_arabic_indic',
		__('Use Arabic-Indic numerals', 'levantine-dates-for-arabic-wp'),
		'simula_levantine_dates_field_arabic_indic_render',
		'simula_levantine_dates_plugin',
		'simula_levantine_dates_plugin_section'
	);	
}

add_action('admin_init', 'simula_levantine_dates_settings_init');

function simula_levantine_dates_settings_section_callback() {
    echo '<p>' . __('Select the format for displaying months.', 'levantine-dates-for-arabic-wp') . '</p>';
}

function simula_levantine_dates_field_format_render() {
    $options = get_option('simula_levantine_dates_settings');
    ?>
    <select name="simula_levantine_dates_settings[simula_levantine_dates_field_format]">
        <option value="no_change" <?php selected($options['simula_levantine_dates_field_format'], 'no_change'); ?>>No Change</option>
        <option value="levantine" <?php selected($options['simula_levantine_dates_field_format'], 'levantine'); ?>>Levantine</option>
        <option value="levantine_msa" <?php selected($options['simula_levantine_dates_field_format'], 'levantine_msa'); ?>>Levantine/MSA</option>
        <option value="msa_levantine" <?php selected($options['simula_levantine_dates_field_format'], 'msa_levantine'); ?>>MSA/Levantine</option>
    </select>
    <?php
}

function simula_levantine_dates_field_arabic_indic_render() {
    $options = get_option('simula_levantine_dates_settings');
    ?>
    <input type='checkbox' name='simula_levantine_dates_settings[simula_levantine_dates_field_arabic_indic]' <?php checked(isset($options['simula_levantine_dates_field_arabic_indic']) && $options['simula_levantine_dates_field_arabic_indic']); ?> value='1'>
    <?php
}

?>