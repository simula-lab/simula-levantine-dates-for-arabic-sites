<?php
/**
 * Plugin Name: Levantine Dates for Arabic Wordpress
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

/**
 * Replaces Arabic numerals with Arabic-Indic numberals in given string.
 *
 * @since 1.0.0
 *
 * @param string	$string A string containing Arabic numerals
 * @return string	A string consisting of the input string with Arabic numerals replaced with Arabic-Indic numerals
 */
function convert_arabic_numerals_to_arabic_indic( $string ) {
	$arabic_numerals = array('۰', '۱', '۲', '۳', '٤', '۵', '٦', '۷', '۸', '۹', ',', '،');
	$arabic_indic_numerals = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', ',');
	return str_replace( $arabic_numerals, $arabic_indic_numerals, $string );
}

function convert_msa_month_names_to_levantine_month_names( $string ) {
	$modern_standard_arabic_month_names = array(
		'يناير',
		'فبراير',
		'مارس',
		'أبريل',
		'مايو',
		'يونيو',
		'يوليو',
		'أغسطس',
		'سبتمبر',
		'أكتوبر',
		'نوفمبر',
		'ديسمبر'
	);
	$levantine_month_names = array(
		'كانون الثاني',
		'شباط',
		'آذار',
		'نيسان',
		'أيار',
		'حزيران',
		'تموز',
		'آب',
		'أيلول',
		'تشرين الأول',
		'تشرين الثاني',
		'كانون الأول'
	);
	return str_replace( $modern_standard_arabic_month_names, $levantine_month_names, $string );	
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
	if ( get_bloginfo( 'language' ) == 'ar' || $force ) {
		$the_date = convert_arabic_numerals_to_arabic_indic( $the_date );
		$the_date = convert_msa_month_names_to_levantine_month_names( $the_date );
	}
	return $the_date;
}

if( !is_admin() ){
	add_filter( 'get_the_time', 'make_levantine_date' );
	add_filter( 'get_the_date', 'make_levantine_date' );
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
?>
