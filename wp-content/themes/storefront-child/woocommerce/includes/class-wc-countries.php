<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WooCommerce countries
 *
 * The WooCommerce countries class stores country/state data.
 *
 * @class       WC_Countries
 * @version     2.3.0
 * @package     WooCommerce/Classes
 * @category    Class
 * @author      WooThemes
 */
class WC_Countries {

	/** @var array Array of locales */
	public $locale;

	/** @var array Array of address formats for locales */
	public $address_formats;

	/**
	 * Auto-load in-accessible properties on demand.
	 * @param  mixed $key
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( 'countries' == $key ) {
			return $this->get_countries();
		} elseif ( 'states' == $key ) {
			return $this->get_states();
		}
	}

	/**
	 * Get all countries.
	 * @return array
	 */
	public function get_countries() {
		if ( empty( $this->countries ) ) {
			$this->countries = apply_filters( 'woocommerce_countries', include( WC()->plugin_path() . '/i18n/countries.php' ) );
			if ( apply_filters( 'woocommerce_sort_countries', true ) ) {
				asort( $this->countries );
			}
		}
		return $this->countries;
	}

	/**
	 * Get all continents.
	 * @return array
	 */
	public function get_continents() {
		if ( empty( $this->continents ) ) {
			$this->continents = apply_filters( 'woocommerce_continents', include( WC()->plugin_path() . '/i18n/continents.php' ) );
		}
		return $this->continents;
	}

	/**
	 * Get continent code for a country code.
	 * @since 2.6.0
	 * @param string $cc string
	 * @return string
	 */
	public function get_continent_code_for_country( $cc ) {
		$cc                 = trim( strtoupper( $cc ) );
		$continents         = $this->get_continents();
		$continents_and_ccs = wp_list_pluck( $continents, 'countries' );
		foreach ( $continents_and_ccs as $continent_code => $countries ) {
			if ( false !== array_search( $cc, $countries ) ) {
				return $continent_code;
			}
		}
		return '';
	}

	/**
	 * Load the states.
	 */
	public function load_country_states() {
		global $states;

		// States set to array() are blank i.e. the country has no use for the state field.
		$states = array(
			'AF' => array(),
			'AT' => array(),
			'AX' => array(),
			'BE' => array(),
			'BI' => array(),
			'CZ' => array(),
			'DE' => array(),
			'DK' => array(),
			'EE' => array(),
			'FI' => array(),
			'FR' => array(),
			'GP' => array(),
			'GF' => array(),
			'IS' => array(),
			'IL' => array(),
			'KR' => array(),
			'KW' => array(),
			'LB' => array(),
			'MQ' => array(),
			'NL' => array(),
			'NO' => array(),
			'PL' => array(),
			'PT' => array(),
			'RE' => array(),
            'RU' => array(),
			'SG' => array(),
			'SK' => array(),
			'SI' => array(),
			'LK' => array(),
			'SE' => array(),
			'VN' => array(),
			'YT' => array(),
		);

		// Load only the state files the shop owner wants/needs.
		$allowed = array_merge( $this->get_allowed_countries(), $this->get_shipping_countries() );

		if ( ! empty( $allowed ) ) {
			foreach ( $allowed as $code => $country ) {
				if ( ! isset( $states[ $code ] ) && file_exists( WC()->plugin_path() . '/i18n/states/' . $code . '.php' ) ) {
					include( WC()->plugin_path() . '/i18n/states/' . $code . '.php' );
				}
			}
		}

		$this->states = apply_filters( 'woocommerce_states', $states );
	}

	/**
	 * Get the states for a country.
	 * @param  string $cc country code
	 * @return false|array of states
	 */
	public function get_states( $cc = null ) {
		if ( empty( $this->states ) ) {
			$this->load_country_states();
		}

		if ( ! is_null( $cc ) ) {
			return isset( $this->states[ $cc ] ) ? $this->states[ $cc ] : false;
		} else {
			return $this->states;
		}
	}

	/**
	 * Get the base address (first line) for the store.
	 * @since 3.1.1
	 * @return string
	 */
	public function get_base_address() {
		$base_address = get_option( 'woocommerce_store_address', '' );
		return apply_filters( 'woocommerce_countries_base_address', $base_address );
	}

	/**
	 * Get the base address (second line) for the store.
	 * @since 3.1.1
	 * @return string
	 */
	public function get_base_address_2() {
		$base_address_2 = get_option( 'woocommerce_store_address_2', '' );
		return apply_filters( 'woocommerce_countries_base_address_2', $base_address_2 );
	}

	/**
	 * Get the base country for the store.
	 * @return string
	 */
	public function get_base_country() {
		$default = wc_get_base_location();
		return apply_filters( 'woocommerce_countries_base_country', $default['country'] );
	}

	/**
	 * Get the base state for the store.
	 * @return string
	 */
	public function get_base_state() {
		$default = wc_get_base_location();
		return apply_filters( 'woocommerce_countries_base_state', $default['state'] );
	}

	/**
	 * Get the base city for the store.
	 * @version 3.1.1
	 * @return string
	 */
	public function get_base_city() {
		$base_city = get_option( 'woocommerce_store_city', '' );
		return apply_filters( 'woocommerce_countries_base_city', $base_city );
	}

	/**
	 * Get the base postcode for the store.
	 * @since 3.1.1
	 * @return string
	 */
	public function get_base_postcode() {
		$base_postcode = get_option( 'woocommerce_store_postcode', '' );
		return apply_filters( 'woocommerce_countries_base_postcode', $base_postcode );
	}

	/**
	 * Get the allowed countries for the store.
	 * @return array
	 */
	public function get_allowed_countries() {
		if ( 'all' === get_option( 'woocommerce_allowed_countries' ) ) {
			return apply_filters( 'woocommerce_countries_allowed_countries', $this->countries );
		}

		if ( 'all_except' === get_option( 'woocommerce_allowed_countries' ) ) {
			$except_countries = get_option( 'woocommerce_all_except_countries', array() );

			if ( ! $except_countries ) {
				return $this->countries;
			} else {
				$all_except_countries = $this->countries;
				foreach ( $except_countries as $country ) {
					unset( $all_except_countries[ $country ] );
				}
				return apply_filters( 'woocommerce_countries_allowed_countries', $all_except_countries );
			}
		}

		$countries = array();

		$raw_countries = get_option( 'woocommerce_specific_allowed_countries', array() );

		if ( $raw_countries ) {
			foreach ( $raw_countries as $country ) {
				$countries[ $country ] = $this->countries[ $country ];
			}
		}

		return apply_filters( 'woocommerce_countries_allowed_countries', $countries );
	}

	/**
	 * Get the countries you ship to.
	 * @return array
	 */
	public function get_shipping_countries() {
		if ( '' === get_option( 'woocommerce_ship_to_countries' ) ) {
			return $this->get_allowed_countries();
		}

		if ( 'all' === get_option( 'woocommerce_ship_to_countries' ) ) {
			return $this->countries;
		}

		$countries = array();

		$raw_countries = get_option( 'woocommerce_specific_ship_to_countries' );

		if ( $raw_countries ) {
			foreach ( $raw_countries as $country ) {
				$countries[ $country ] = $this->countries[ $country ];
			}
		}

		return apply_filters( 'woocommerce_countries_shipping_countries', $countries );
	}

	/**
	 * Get allowed country states.
	 * @return array
	 */
	public function get_allowed_country_states() {
		if ( get_option( 'woocommerce_allowed_countries' ) !== 'specific' ) {
			return $this->states;
		}

		$states = array();

		$raw_countries = get_option( 'woocommerce_specific_allowed_countries' );

		if ( $raw_countries ) {
			foreach ( $raw_countries as $country ) {
				if ( isset( $this->states[ $country ] ) ) {
					$states[ $country ] = $this->states[ $country ];
				}
			}
		}

		return apply_filters( 'woocommerce_countries_allowed_country_states', $states );
	}

	/**
	 * Get shipping country states.
	 * @return array
	 */
	public function get_shipping_country_states() {
		if ( get_option( 'woocommerce_ship_to_countries' ) == '' ) {
			return $this->get_allowed_country_states();
		}

		if ( get_option( 'woocommerce_ship_to_countries' ) !== 'specific' ) {
			return $this->states;
		}

		$states = array();

		$raw_countries = get_option( 'woocommerce_specific_ship_to_countries' );

		if ( $raw_countries ) {
			foreach ( $raw_countries as $country ) {
				if ( ! empty( $this->states[ $country ] ) ) {
					$states[ $country ] = $this->states[ $country ];
				}
			}
		}

		return apply_filters( 'woocommerce_countries_shipping_country_states', $states );
	}

	/**
	 * Gets an array of countries in the EU.
	 *
	 * MC (monaco) and IM (isle of man, part of UK) also use VAT.
	 *
	 * @param  string $type Type of countries to retrieve. Blank for EU member countries. eu_vat for EU VAT countries.
	 * @return string[]
	 */
	public function get_european_union_countries( $type = '' ) {
		$countries = array( 'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB', 'GR', 'HU', 'HR', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK' );

		if ( 'eu_vat' === $type ) {
			$countries[] = 'MC';
			$countries[] = 'IM';
		}

		return $countries;
	}

	/**
	 * Gets the correct string for shipping - either 'to the' or 'to'
	 *
	 * @param string $country_code
	 *
	 * @return string
	 */
	public function shipping_to_prefix( $country_code = '' ) {
		$country_code = $country_code ? $country_code : WC()->customer->get_shipping_country();
		$countries    = array( 'GB', 'US', 'AE', 'CZ', 'DO', 'NL', 'PH', 'USAF' );
		$return       = in_array( $country_code, $countries ) ? __( 'to the', 'woocommerce' ) : __( 'to', 'woocommerce' );

		return apply_filters( 'woocommerce_countries_shipping_to_prefix', $return, $country_code );
	}

	/**
	 * Prefix certain countries with 'the'
	 *
	 * @param string $country_code
	 *
	 * @return string
	 */
	public function estimated_for_prefix( $country_code = '' ) {
		$country_code = $country_code ? $country_code : $this->get_base_country();
		$countries    = array( 'GB', 'US', 'AE', 'CZ', 'DO', 'NL', 'PH', 'USAF' );
		$return       = in_array( $country_code, $countries ) ? __( 'the', 'woocommerce' ) . ' ' : '';

		return apply_filters( 'woocommerce_countries_estimated_for_prefix', $return, $country_code );
	}

	/**
	 * Correctly name tax in some countries VAT on the frontend.
	 * @return string
	 */
	public function tax_or_vat() {
		$return = in_array( $this->get_base_country(), array_merge( $this->get_european_union_countries( 'eu_vat' ), array( 'NO' ) ) ) ? __( 'VAT', 'woocommerce' ) : __( 'Tax', 'woocommerce' );

		return apply_filters( 'woocommerce_countries_tax_or_vat', $return );
	}

	/**
	 * Include the Inc Tax label.
	 * @return string
	 */
	public function inc_tax_or_vat() {
		$return = in_array( $this->get_base_country(), array_merge( $this->get_european_union_countries( 'eu_vat' ), array( 'NO' ) ) ) ? __( '(incl. VAT)', 'woocommerce' ) : __( '(incl. tax)', 'woocommerce' );

		return apply_filters( 'woocommerce_countries_inc_tax_or_vat', $return );
	}

	/**
	 * Include the Ex Tax label.
	 * @return string
	 */
	public function ex_tax_or_vat() {
		$return = in_array( $this->get_base_country(), array_merge( $this->get_european_union_countries( 'eu_vat' ), array( 'NO' ) ) ) ? __( '(ex. VAT)', 'woocommerce' ) : __( '(ex. tax)', 'woocommerce' );

		return apply_filters( 'woocommerce_countries_ex_tax_or_vat', $return );
	}

	/**
	 * Outputs the list of countries and states for use in dropdown boxes.
	 * @param string $selected_country (default: '')
	 * @param string $selected_state (default: '')
	 * @param bool $escape (default: false)
	 * @param bool   $escape (default: false)
	 */
	public function country_dropdown_options( $selected_country = '', $selected_state = '', $escape = false ) {
		if ( $this->countries ) :
			foreach ( $this->countries as $key => $value ) :
				if ( $states = $this->get_states( $key ) ) :
					echo '<optgroup label="' . esc_attr( $value ) . '">';
						foreach ( $states as $state_key => $state_value ) :
						echo '<option value="' . esc_attr( $key ) . ':' . $state_key . '"';

						if ( $selected_country === $key && $selected_state === $state_key ) {
							echo ' selected="selected"';
						}

						echo '>' . $value . ' &mdash; ' . ( $escape ? esc_js( $state_value ) : $state_value ) . '</option>';
						endforeach;
					echo '</optgroup>';
				else :
					echo '<option';
					if ( $selected_country === $key && '*' === $selected_state ) {
						echo ' selected="selected"';
					}
					echo ' value="' . esc_attr( $key ) . '">' . ( $escape ? esc_js( $value ) : $value ) . '</option>';
				endif;
			endforeach;
		endif;
	}

	/**
	 * Get country address formats.
	 *
	 * These define how addresses are formatted for display in various countries.
	 *
	 * @return array
	 */
	public function get_address_formats() {
		if ( empty( $this->address_formats ) ) {
			$this->address_formats = apply_filters( 'woocommerce_localisation_address_formats', array(
				'default' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}\n{state}\n{postcode}\n{country}",
				'AU' => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {state} {postcode}\n{country}",
				'AT' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'BE' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'CA' => "{company}\n{name}\n{address_1}\n{address_2}\n{city} {state} {postcode}\n{country}",
				'CH' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'CL' => "{company}\n{name}\n{address_1}\n{address_2}\n{state}\n{postcode} {city}\n{country}",
				'CN' => "{country} {postcode}\n{state}, {city}, {address_2}, {address_1}\n{company}\n{name}",
				'CZ' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'DE' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'EE' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'FI' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'DK' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'FR' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city_upper}\n{country}",
				'HK' => "{company}\n{first_name} {last_name_upper}\n{address_1}\n{address_2}\n{city_upper}\n{state_upper}\n{country}",
				'HU' => "{name}\n{company}\n{city}\n{address_1}\n{address_2}\n{postcode}\n{country}",
				'IN' => "{company}\n{name}\n{address_1}\n{address_2}\n{city} - {postcode}\n{state}, {country}",
				'IS' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'IT' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode}\n{city}\n{state_upper}\n{country}",
				'JP' => "{postcode}\n{state}{city}{address_1}\n{address_2}\n{company}\n{last_name} {first_name}\n{country}",
				'TW' => "{company}\n{last_name} {first_name}\n{address_1}\n{address_2}\n{state}, {city} {postcode}\n{country}",
				'LI' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'NL' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'NZ' => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {postcode}\n{country}",
				'NO' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'PL' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'PT' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'SK' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'SI' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'ES' => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}",
				'SE' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}",
				'TR' => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city} {state}\n{country}",
				'US' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}, {state_code} {postcode}\n{country}",
				'VN' => "{name}\n{company}\n{address_1}\n{city}\n{country}",
			) );
		}
		return $this->address_formats;
	}

	/**
	 * Get country address format.
	 * @param  array  $args (default: array())
	 * @return string address
	 */
	public function get_formatted_address( $args = array() ) {
		$default_args = array(
			'first_name' => '',
			'last_name'  => '',
			'company'    => '',
			'address_1'  => '',
			'address_2'  => '',
			'city'       => '',
			'state'      => '',
			'postcode'   => '',
			'country'    => '',
		);

		$args = array_map( 'trim', wp_parse_args( $args, $default_args ) );

		extract( $args );

		// Get all formats
		$formats = $this->get_address_formats();

		// Get format for the address' country
		$format = ( $country && isset( $formats[ $country ] ) ) ? $formats[ $country ] : $formats['default'];

		// Handle full country name
		$full_country = ( isset( $this->countries[ $country ] ) ) ? $this->countries[ $country ] : $country;

		// Country is not needed if the same as base
		if ( $country == $this->get_base_country() && ! apply_filters( 'woocommerce_formatted_address_force_country_display', false ) ) {
			$format = str_replace( '{country}', '', $format );
		}

		// Handle full state name
		$full_state = ( $country && $state && isset( $this->states[ $country ][ $state ] ) ) ? $this->states[ $country ][ $state ] : $state;

		// Substitute address parts into the string
		$replace = array_map( 'esc_html', apply_filters( 'woocommerce_formatted_address_replacements', array(
			'{first_name}'       => $first_name,
			'{last_name}'        => $last_name,
			'{name}'             => $first_name . ' ' . $last_name,
			'{company}'          => $company,
			'{address_1}'        => $address_1,
			'{address_2}'        => $address_2,
			'{city}'             => $city,
			'{state}'            => $full_state,
			'{postcode}'         => $postcode,
			'{country}'          => $full_country,
			'{first_name_upper}' => strtoupper( $first_name ),
			'{last_name_upper}'  => strtoupper( $last_name ),
			'{name_upper}'       => strtoupper( $first_name . ' ' . $last_name ),
			'{company_upper}'    => strtoupper( $company ),
			'{address_1_upper}'  => strtoupper( $address_1 ),
			'{address_2_upper}'  => strtoupper( $address_2 ),
			'{city_upper}'       => strtoupper( $city ),
			'{state_upper}'      => strtoupper( $full_state ),
			'{state_code}'       => strtoupper( $state ),
			'{postcode_upper}'   => strtoupper( $postcode ),
			'{country_upper}'    => strtoupper( $full_country ),
		), $args ) );

		$formatted_address = str_replace( array_keys( $replace ), $replace, $format );

		// Clean up white space
		$formatted_address = preg_replace( '/  +/', ' ', trim( $formatted_address ) );
		$formatted_address = preg_replace( '/\n\n+/', "\n", $formatted_address );

		// Break newlines apart and remove empty lines/trim commas and white space
		$formatted_address = array_filter( array_map( array( $this, 'trim_formatted_address_line' ), explode( "\n", $formatted_address ) ) );

		// Add html breaks
		$formatted_address = implode( '<br/>', $formatted_address );

		// We're done!
		return $formatted_address;
	}

	/**
	 * Trim white space and commas off a line.
	 * @param  string $line
	 * @return string
	 */
	private function trim_formatted_address_line( $line ) {
		return trim( $line, ", " );
	}

	/**
	 * Returns the fields we show by default. This can be filtered later on.
	 * @return array
	 */
	public function get_default_address_fields() {
		$fields = array(
			'first_name' => array(
				'label'        => __( 'First name', 'woocommerce' ),
				'required'     => true,
				'class'        => array( 'form-row-first' ),
				'autocomplete' => 'given-name',
				'autofocus'    => true,
				'priority'     => 10,
			),
			'last_name' => array(
				'label'        => __( 'Last name', 'woocommerce' ),
				'required'     => true,
				'class'        => array( 'form-row-last' ),
				'autocomplete' => 'family-name',
				'priority'     => 20,
			),
			'company' => array(
				'label'        => __( 'Company name', 'woocommerce' ),
				'class'        => array( 'form-row-wide' ),
				'autocomplete' => 'organization',
				'priority'     => 30,
			),
			'country' => array(
				'type'         => 'country',
				'label'        => __( 'Country', 'woocommerce' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
				'autocomplete' => 'country',
				'priority'     => 40,
			),
			'address_1' => array(
				'label'        => __( 'Street address', 'woocommerce' ),
				/* translators: use local order of street name and house number. */
				'placeholder'  => esc_attr__( 'House number and street name', 'woocommerce' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field' ),
				'autocomplete' => 'address-line1',
				'priority'     => 50,
			),
			'address_2' => array(
				'placeholder'  => esc_attr__( 'Apartment, suite, unit etc. (optional)', 'woocommerce' ),
				'class'        => array( 'form-row-wide', 'address-field' ),
				'required'     => false,
				'autocomplete' => 'address-line2',
				'priority'     => 60,
			),
			'city' => array(
				'label'        => __( 'Town / City', 'woocommerce' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field' ),
				'autocomplete' => 'address-level2',
				'priority'     => 70,
			),
			'state' => array(
				'type'         => 'state',
				'label'        => __( 'State / County', 'woocommerce' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field' ),
				'validate'     => array( 'state' ),
				'autocomplete' => 'address-level1',
				'priority'     => 80,
			),
			'postcode' => array(
				'label'        => __( 'Postcode / ZIP', 'woocommerce' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field' ),
				'validate'     => array( 'postcode' ),
				'autocomplete' => 'postal-code',
				'priority'     => 90,
			),
		);

		return apply_filters( 'woocommerce_default_address_fields', $fields );
	}

	/**
	 * Get JS selectors for fields which are shown/hidden depending on the locale.
	 * @return array
	 */
	public function get_country_locale_field_selectors() {
		$locale_fields = array(
			'address_1' => '#billing_address_1_field, #shipping_address_1_field',
			'address_2' => '#billing_address_2_field, #shipping_address_2_field',
			'state'     => '#billing_state_field, #shipping_state_field, #calc_shipping_state_field',
			'postcode'  => '#billing_postcode_field, #shipping_postcode_field, #calc_shipping_postcode_field',
			'city'      => '#billing_city_field, #shipping_city_field, #calc_shipping_city_field',
		);
		return apply_filters( 'woocommerce_country_locale_field_selectors', $locale_fields );
	}

	/**
	 * Get country locale settings.
	 *
	 * These locales override the default country selections after a country is chosen.
	 *
	 * @return array
	 */
	public function get_country_locale() {
		if ( empty( $this->locale ) ) {
			$this->locale = apply_filters( 'woocommerce_get_country_locale', array(
				'AE' => array(
					'postcode' => array(
						'required' => false,
						'hidden'   => true,
					),
					'state' => array(
						'required' => false,
					),
				),
				'MU' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
					'state' => array(
						'required' => false,
					),
				),
				'AF' => array(
					'state' => array(
						'required' => false,
					),
				),
				'AG' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'AO' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'AT' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'AU' => array(
					'city'      => array(
						'label'       => __( 'Suburb', 'woocommerce' ),
					),
					'postcode'  => array(
						'label'       => __( 'Postcode', 'woocommerce' ),
					),
					'state'     => array(
						'label'       => __( 'State', 'woocommerce' ),
					),
				),
				'AW' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'AX' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'BD' => array(
					'postcode' => array(
						'required' => false,
					),
					'state' => array(
						'label'       => __( 'District', 'woocommerce' ),
					),
				),
				'BE' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required'    => false,
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'BF' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'BI' => array(
					'state' => array(
						'required' => false,
					),
				),
				'BJ' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'BO' => array(
					'postcode' => array(
						'required' => false,
						'hidden'   => true,
					),
				),
				'BS' => array(
					'postcode' => array(
						'required' => false,
						'hidden'   => true,
					),
				),
				'BW' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'BZ' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'CA' => array(
					'state' => array(
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'CD' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'CF' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'CG' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'CH' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'label'       => __( 'Canton', 'woocommerce' ),
						'required'    => false,
					),
				),
				'CI' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'CK' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'CL' => array(
					'city'      => array(
						'required' 	=> true,
					),
					'postcode'  => array(
						'required' => false,
					),
					'state'     => array(
						'label'       => __( 'Region', 'woocommerce' ),
					),
				),
				'CM' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'CN' => array(
					'state' => array(
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'CO' => array(
					'postcode' => array(
						'required' => false,
					),
				),
				'CZ' => array(
					'state' => array(
						'required' => false,
					),
				),
				'DE' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'DJ' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'DK' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'DM' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'EE' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'GQ' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'ER' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'FI' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'FJ' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'FR' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'TF' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'GP' => array(
					'state' => array(
						'required' => false,
					),
				),
				'GD' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'GF' => array(
					'state' => array(
						'required' => false,
					),
				),
				'GM' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'GN' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'GH' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'GY' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'HK' => array(
					'postcode' => array(
						'required' => false,
					),
					'city'  => array(
						'label'       => __( 'Town / District', 'woocommerce' ),
					),
					'state' => array(
						'label'       => __( 'Region', 'woocommerce' ),
					),
				),
				'HU' => array(
					'state' => array(
						'label'       => __( 'County', 'woocommerce' ),
					),
				),
				'ID' => array(
					'state' => array(
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'IE' => array(
					'postcode' => array(
						'required' => false,
						'label'    => __( 'Eircode', 'woocommerce' ),
					),
					'state' => array(
						'label'       => __( 'County', 'woocommerce' ),
					),
				),
				'IS' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'IL' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'IT' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required'    => true,
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'JM' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'JP' => array(
					'state' => array(
						'label' => __( 'Prefecture', 'woocommerce' ),
						'priority' => 66,
					),
					'postcode' => array(
						'priority' => 65,
					),
				),
				'KE' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'KI' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'KR' => array(
					'state' => array(
						'required' => false,
					),
				),
				'KM' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'KW' => array(
					'state' => array(
						'required' => false,
					),
				),
				'LB' => array(
					'state' => array(
						'required' => false,
					),
				),
				'ML' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'MO' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'MQ' => array(
					'state' => array(
						'required' => false,
					),
				),
				'MR' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'MS' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'MW' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'NL' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required'    => false,
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'NR' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'AN' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'NZ' => array(
					'postcode' => array(
						'label' => __( 'Postcode', 'woocommerce' ),
					),
					'state' => array(
						'required' => false,
						'label'    => __( 'Region', 'woocommerce' ),
					),
				),
				'NU' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'NO' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'NP' => array(
					'state' => array(
						'label'       => __( 'State / Zone', 'woocommerce' ),
					),
					'postcode' => array(
						'required' => false,
					),
				),
				'PA' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'PL' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'PT' => array(
					'state' => array(
						'required' => false,
					),
				),
				'QA' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'RE' => array(
					'state' => array(
						'required' => false,
					),
				),
				'RO' => array(
					'state' => array(
						'required' => false,
					),
				),
                'RU' => array(
					'state' => array(
						'required' => false,
                        'hidden' => true,
					),
				),
				'RW' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'SG' => array(
					'state' => array(
						'required' => false,
					),
				),
				'SK' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'SI' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'TZ' => array(
					'postcode' => array(
						'required' => false,
						'hidden' => true,
					),
				),
				'ES' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'LI' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'label'       => __( 'Municipality', 'woocommerce' ),
						'required'    => false,
					),
				),
				'LK' => array(
					'state' => array(
						'required' => false,
					),
				),
				'SE' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'required' => false,
					),
				),
				'TR' => array(
					'postcode' => array(
						'priority' => 65,
					),
					'state' => array(
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'US' => array(
					'postcode'  => array(
						'label'       => __( 'ZIP', 'woocommerce' ),
					),
					'state'     => array(
						'label'       => __( 'State', 'woocommerce' ),
					),
				),
				'GB' => array(
					'postcode'  => array(
						'label'       => __( 'Postcode', 'woocommerce' ),
					),
					'state'     => array(
						'label'       => __( 'County', 'woocommerce' ),
						'required'    => false,
					),
				),
				'VN' => array(
					'state' => array(
						'required' => false,
					),
					'postcode' => array(
						'priority'     => 65,
						'required' => false,
						'hidden'   => false,
					),
					'address_2' => array(
						'required' => false,
						'hidden'   => true,
					),
				),
				'WS' => array(
					'postcode' => array(
						'required' => false,
						'hidden'   => true,
					),
				),
				'YT' => array(
					'state' => array(
						'required' => false,
					),
				),
				'ZA' => array(
					'state' => array(
						'label'       => __( 'Province', 'woocommerce' ),
					),
				),
				'ZW' => array(
					'postcode' => array(
						'required' => false,
						'hidden'   => true,
					),
				),
			));

			$this->locale = array_intersect_key( $this->locale, array_merge( $this->get_allowed_countries(), $this->get_shipping_countries() ) );

			// Default Locale Can be filtered to override fields in get_address_fields(). Countries with no specific locale will use default.
			$this->locale['default'] = apply_filters( 'woocommerce_get_country_locale_default', $this->get_default_address_fields() );

			// Filter default AND shop base locales to allow overides via a single function. These will be used when changing countries on the checkout
			if ( ! isset( $this->locale[ $this->get_base_country() ] ) ) {
				$this->locale[ $this->get_base_country() ] = $this->locale['default'];
			}

			$this->locale['default']                   = apply_filters( 'woocommerce_get_country_locale_base', $this->locale['default'] );
			$this->locale[ $this->get_base_country() ] = apply_filters( 'woocommerce_get_country_locale_base', $this->locale[ $this->get_base_country() ] );
		}

		return $this->locale;
	}

	/**
	 * Apply locale and get address fields.
	 * @param  mixed  $country (default: '')
	 * @param  string $type (default: 'billing_')
	 * @return array
	 */
	public function get_address_fields( $country = '', $type = 'billing_' ) {
		if ( ! $country ) {
			$country = $this->get_base_country();
		}

		$fields = $this->get_default_address_fields();
		$locale = $this->get_country_locale();

		if ( isset( $locale[ $country ] ) ) {
			$fields = wc_array_overlay( $fields, $locale[ $country ] );
		}

		// Prepend field keys
		$address_fields = array();

		foreach ( $fields as $key => $value ) {
			if ( 'state' === $key ) {
				$value['country_field'] = $type . 'country';
			}
			$address_fields[ $type . $key ] = $value;
		}

		// Add email and phone fields.
		if ( 'billing_' === $type ) {
			$address_fields['billing_phone'] = array(
				'label'        => __( 'Phone', 'woocommerce' ),
				'required'     => true,
				'type'         => 'tel',
				'class'        => array( 'form-row-first' ),
				'validate'     => array( 'phone' ),
				'autocomplete' => 'tel',
				'priority'     => 100,
			);
			$address_fields['billing_email'] = array(
				'label'        => __( 'Email address', 'woocommerce' ),
				'required'     => true,
				'type'         => 'email',
				'class'        => array( 'form-row-last' ),
				'validate'     => array( 'email' ),
				'autocomplete' => 'no' === get_option( 'woocommerce_registration_generate_username' ) ? 'email' : 'email username',
				'priority'     => 110,
			);
		}

		/**
		 * Important note on this filter: Changes to address fields can and will be overridden by
		 * the woocommerce_default_address_fields. The locales/default locales apply on top based
		 * on country selection. If you want to change things like the required status of an
		 * address field, filter woocommerce_default_address_fields instead.
		 */
		return apply_filters( 'woocommerce_' . $type . 'fields', $address_fields, $country );
	}
}
