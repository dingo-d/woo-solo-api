<?php
/**
 * The plugin helpers class.
 *
 * @link       https://madebydenis.com
 * @since      1.5.0 Change hnb API to an official one.
 * @since      1.3.0
 *
 * @package    Woo_Solo_Api\Admin
 */

namespace Woo_Solo_Api\Admin;

/**
 * Helpers class for the plugin.
 *
 * Holds various helper methods for the plugin.
 *
 * @package    Woo_Solo_Api\Admin
 * @author     Denis Žoljom <denis.zoljom@gmail.com>
 */
class Helpers {
  /**
   * The name of this plugin.
   *
   * @since    1.9.0
   * @access   private
   * @var      string    $plugin_name    The name of this plugin.
   */
  private $plugin_name;

  /**
   * Initialize the class and set its properties.
   *
   * @since 1.9.0
   * @param string $plugin_name  The name of this plugin.
   */
  public function __construct( $plugin_name ) {
    $this->plugin_name = $plugin_name;
  }

  /**
   * Returns the Croatian exchange rates
   *
   * @link https://www.hnb.hr/tecajn/htecajn.htm
   *
   * @since 1.7.5 Add fallback method in case the allow_url_fopen is disabled.
   * @since 1.5.0 Change link for the currency fetch.
   * @since 1.3.0
   *
   * @return array Exchange rates.
   */
  public function get_exchange_rates() {

    $currency_rates = get_transient( 'exchange_rate_transient' ); // Get transient.

    if ( false === $currency_rates ) { // If no valid transient exists, run this.
      $url = 'https://www.hnb.hr/tecajn/htecajn.htm';

      if ( ini_get( 'allow_url_fopen' ) ) {
        $headers = get_headers( $url );
        $status  = substr( $headers[0], 9, 3 );

        // Is the link up?
        if ( $status !== '200' ) {
          return false;
        }

        // phpcs:disable WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        // phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_file_get_contents
        $contents = file_get_contents( $url );
        // phpcs:enable
      } else {
        // phpcs:disable
        $ch = curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

        $contents = curl_exec( $ch );

        if ( curl_errno( $ch ) ) {
          return false;
          $contents = '';
        } else {
          curl_close( $ch );
        }

        if ( ! is_string( $contents ) || ! strlen( $contents ) ) {
          return false;
          $contents = '';
        }
        // phpcs:enable
      }

      if ( empty( $contents ) ) {
        return false;
      }

      $array = explode( "\n", $contents );
      unset( $array[0] );
      $array = array_values( $array );

      $currency_rates = [];

      foreach ( $array as $arr_key => $arr_value ) {
        $single_rate   = array_values( array_filter( explode( ' ', $arr_value ) ) );
        $currency_name = preg_replace( '/[^a-zA-Z]+/', '', $single_rate[0] );

        $currency_rates[ $currency_name ] = $single_rate[2];
      }

      set_transient( 'exchange_rate_transient', $currency_rates, 6 * HOUR_IN_SECONDS );
    }

    // Are the results in an array?
    if ( ! is_array( $currency_rates ) ) {
      return false;
    }

    return $currency_rates;
  }

  /**
   * Get language codes from available translations
   *
   * @param  string $val Array value.
   *
   * @since 1.9.0
   *
   * @return string      Value that passes the filter.
   */
  public function get_lang_codes( $val ) {

    $code = str_replace( '-', '', str_replace( $this->plugin_name, '', $val ) );

    $language_code = 'en';
    if ( ! empty( $code ) ) {
      $language_code = $code;
    }

    return $language_code;
  }

  /**
   * Returns the language for a language code
   *
   * The built in function works only for multisite installs.
   *
   * @link https://developer.wordpress.org/reference/functions/format_code_lang/
   * @param  string $code     Code for language.
   *
   * @since 1.9.0
   * @return array  $lang_array Array with language codes and name.
   */
  public function format_code_lang( $code = '' ) {
    $code = strtolower( substr( $code, 0, 2 ) );

    $lang_codes = array(
        'aa' => 'Afar',
        'ab' => 'Abkhazian',
        'af' => 'Afrikaans',
        'ak' => 'Akan',
        'sq' => 'Albanian',
        'am' => 'Amharic',
        'ar' => 'Arabic',
        'an' => 'Aragonese',
        'hy' => 'Armenian',
        'as' => 'Assamese',
        'av' => 'Avaric',
        'ae' => 'Avestan',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'ba' => 'Bashkir',
        'bm' => 'Bambara',
        'eu' => 'Basque',
        'be' => 'Belarusian',
        'bn' => 'Bengali',
        'bh' => 'Bihari',
        'bi' => 'Bislama',
        'bs' => 'Bosnian',
        'br' => 'Breton',
        'bg' => 'Bulgarian',
        'my' => 'Burmese',
        'ca' => 'Catalan; Valencian',
        'ch' => 'Chamorro',
        'ce' => 'Chechen',
        'zh' => 'Chinese',
        'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic',
        'cv' => 'Chuvash',
        'kw' => 'Cornish',
        'co' => 'Corsican',
        'cr' => 'Cree',
        'cs' => 'Czech',
        'da' => 'Danish',
        'dv' => 'Divehi; Dhivehi; Maldivian',
        'nl' => 'Dutch; Flemish',
        'dz' => 'Dzongkha',
        'en' => 'English',
        'eo' => 'Esperanto',
        'et' => 'Estonian',
        'ee' => 'Ewe',
        'fo' => 'Faroese',
        'fj' => 'Fijjian',
        'fi' => 'Finnish',
        'fr' => 'French',
        'fy' => 'Western Frisian',
        'ff' => 'Fulah',
        'ka' => 'Georgian',
        'de' => 'German',
        'gd' => 'Gaelic; Scottish Gaelic',
        'ga' => 'Irish',
        'gl' => 'Galician',
        'gv' => 'Manx',
        'el' => 'Greek, Modern',
        'gn' => 'Guarani',
        'gu' => 'Gujarati',
        'ht' => 'Haitian; Haitian Creole',
        'ha' => 'Hausa',
        'he' => 'Hebrew',
        'hz' => 'Herero',
        'hi' => 'Hindi',
        'ho' => 'Hiri Motu',
        'hu' => 'Hungarian',
        'ig' => 'Igbo',
        'is' => 'Icelandic',
        'io' => 'Ido',
        'ii' => 'Sichuan Yi',
        'iu' => 'Inuktitut',
        'ie' => 'Interlingue',
        'ia' => 'Interlingua (International Auxiliary Language Association)',
        'id' => 'Indonesian',
        'ik' => 'Inupiaq',
        'it' => 'Italian',
        'jv' => 'Javanese',
        'ja' => 'Japanese',
        'kl' => 'Kalaallisut; Greenlandic',
        'kn' => 'Kannada',
        'ks' => 'Kashmiri',
        'kr' => 'Kanuri',
        'kk' => 'Kazakh',
        'km' => 'Central Khmer',
        'ki' => 'Kikuyu; Gikuyu',
        'rw' => 'Kinyarwanda',
        'ky' => 'Kirghiz; Kyrgyz',
        'kv' => 'Komi',
        'kg' => 'Kongo',
        'ko' => 'Korean',
        'kj' => 'Kuanyama; Kwanyama',
        'ku' => 'Kurdish',
        'lo' => 'Lao',
        'la' => 'Latin',
        'lv' => 'Latvian',
        'li' => 'Limburgan; Limburger; Limburgish',
        'ln' => 'Lingala',
        'lt' => 'Lithuanian',
        'lb' => 'Luxembourgish; Letzeburgesch',
        'lu' => 'Luba-Katanga',
        'lg' => 'Ganda',
        'mk' => 'Macedonian',
        'mh' => 'Marshallese',
        'ml' => 'Malayalam',
        'mi' => 'Maori',
        'mr' => 'Marathi',
        'ms' => 'Malay',
        'mg' => 'Malagasy',
        'mt' => 'Maltese',
        'mo' => 'Moldavian',
        'mn' => 'Mongolian',
        'na' => 'Nauru',
        'nv' => 'Navajo; Navaho',
        'nr' => 'Ndebele, South; South Ndebele',
        'nd' => 'Ndebele, North; North Ndebele',
        'ng' => 'Ndonga',
        'ne' => 'Nepali',
        'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian',
        'nb' => 'Bokmål, Norwegian, Norwegian Bokmål',
        'no' => 'Norwegian',
        'ny' => 'Chichewa; Chewa; Nyanja',
        'oc' => 'Occitan, Provençal',
        'oj' => 'Ojibwa',
        'or' => 'Oriya',
        'om' => 'Oromo',
        'os' => 'Ossetian; Ossetic',
        'pa' => 'Panjabi; Punjabi',
        'fa' => 'Persian',
        'pi' => 'Pali',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'ps' => 'Pushto',
        'qu' => 'Quechua',
        'rm' => 'Romansh',
        'ro' => 'Romanian',
        'rn' => 'Rundi',
        'ru' => 'Russian',
        'sg' => 'Sango',
        'sa' => 'Sanskrit',
        'sr' => 'Serbian',
        'hr' => 'Croatian',
        'si' => 'Sinhala; Sinhalese',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'se' => 'Northern Sami',
        'sm' => 'Samoan',
        'sn' => 'Shona',
        'sd' => 'Sindhi',
        'so' => 'Somali',
        'st' => 'Sotho, Southern',
        'es' => 'Spanish; Castilian',
        'sc' => 'Sardinian',
        'ss' => 'Swati',
        'su' => 'Sundanese',
        'sw' => 'Swahili',
        'sv' => 'Swedish',
        'ty' => 'Tahitian',
        'ta' => 'Tamil',
        'tt' => 'Tatar',
        'te' => 'Telugu',
        'tg' => 'Tajik',
        'tl' => 'Tagalog',
        'th' => 'Thai',
        'bo' => 'Tibetan',
        'ti' => 'Tigrinya',
        'to' => 'Tonga (Tonga Islands)',
        'tn' => 'Tswana',
        'ts' => 'Tsonga',
        'tk' => 'Turkmen',
        'tr' => 'Turkish',
        'tw' => 'Twi',
        'ug' => 'Uighur; Uyghur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        've' => 'Venda',
        'vi' => 'Vietnamese',
        'vo' => 'Volapük',
        'cy' => 'Welsh',
        'wa' => 'Walloon',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'za' => 'Zhuang; Chuang',
        'zu' => 'Zulu',
    );

    return strtr( $code, $lang_codes );
  }

  /**
   * Get available translations from languages folder
   *
   * Helper function.
   *
   * @since 1.9.0
   * @return array Array of available translations.
   */
  public function get_translations() {
    $languages  = array_map( [ $this, 'get_lang_codes' ], \get_available_languages( plugin_dir_path( __DIR__ ) . '/languages/' ) );
    $lang_array = array();

    foreach ( $languages as $lang_code ) {
      $lang_array[ $lang_code ] = $this->format_code_lang( $lang_code );
    }

    return $lang_array;
  }
}
