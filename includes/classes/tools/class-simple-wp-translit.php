<?php

/**
 * Class SimpleWpTranslit
 */
class SimpleWpTranslit
{
	/** @var array<string,string> */
	private static $retable = [
		'/зг/u'   => 'zgh',
		'/Зг/u'   => 'Zgh',
		'/\\bє/u' => 'ye',
		'/\\Bє/u' => 'ie',
		'/\\bї/u' => 'yi',
		'/\\Bї/u' => 'i',
		'/\\bй/u' => 'y',
		'/\\Bй/u' => 'i',
		'/\\bю/u' => 'yu',
		'/\\Bю/u' => 'iu',
		'/\\bя/u' => 'ya',
		'/\\Bя/u' => 'ia',
	];

	/** @var array<string,string> */
	private static $table = [
		'А' => 'A',
		'Б' => 'B',
		'В' => 'V',
		'Г' => 'H',
		'Ѓ' => 'G',
		'Ґ' => 'G',
		'Д' => 'D',
		'Е' => 'E',
		'Ё' => 'YO',
		'Є' => 'YE',
		'Ж' => 'ZH',
		'З' => 'Z',
		'Ѕ' => 'Z',
		'И' => 'I',
		'Й' => 'J',
		'Ј' => 'J',
		'І' => 'I',
		'Ї' => 'YI',
		'К' => 'K',
		'Ќ' => 'K',
		'Л' => 'L',
		'Љ' => 'L',
		'М' => 'M',
		'Н' => 'N',
		'Њ' => 'N',
		'О' => 'O',
		'П' => 'P',
		'Р' => 'R',
		'С' => 'S',
		'Т' => 'T',
		'У' => 'U',
		'Ў' => 'U',
		'Ф' => 'F',
		'Х' => 'KH',
		'Ц' => 'TS',
		'Ч' => 'CH',
		'Џ' => 'DH',
		'Ш' => 'SH',
		'Щ' => 'SHCH',
		'Ъ' => '',
		'Ы' => 'Y',
		'Ь' => '',
		'Э' => 'E',
		'Ю' => 'YU',
		'Я' => 'YA',

		'а' => 'a',
		'б' => 'b',
		'в' => 'v',
		'г' => 'h',
		'ѓ' => 'g',
		'ґ' => 'g',
		'д' => 'd',
		'е' => 'e',
		'ё' => 'yo',
		'є' => 'ye',
		'ж' => 'zh',
		'з' => 'z',
		'ѕ' => 'z',
		'и' => 'y',
		'й' => 'j',
		'ј' => 'j',
		'і' => 'i',
		'ї' => 'yi',
		'к' => 'k',
		'ќ' => 'k',
		'л' => 'l',
		'љ' => 'l',
		'м' => 'm',
		'н' => 'n',
		'њ' => 'n',
		'о' => 'o',
		'п' => 'p',
		'р' => 'r',
		'с' => 's',
		'т' => 't',
		'у' => 'u',
		'ў' => 'u',
		'ф' => 'f',
		'х' => 'kh',
		'ц' => 'ts',
		'ч' => 'ch',
		'џ' => 'dh',
		'ш' => 'sh',
		'щ' => 'shch',
		'ъ' => '',
		'ы' => 'y',
		'ь' => '',
		'э' => 'e',
		'ю' => 'yu',
		'я' => 'ya',
	];

	/**
	 * Transliteration.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function transliterate( string $value): string {
		$retbl = static::getReTable();
		if ( count( $retbl ) > 0 ) {
			$value = (string) preg_replace( array_keys( $retbl ), array_values( $retbl ), $value );
		}

		$tbl   = static::get_table();
		$value = strtr( $value, $tbl );
		$value = (string) iconv( 'UTF-8', 'UTF-8//TRANSLIT//IGNORE', $value );
		$value = preg_replace( '/[^A-Za-z0-9_.-]/', '-', $value );
		$value = preg_replace( '/-{2,}/', '-', $value );
		$value = trim( $value, '-' );

		return $value;
	}

	/**
	 * @return array<string,string>
	 */
	private static function get_table(): array {
		$locale = get_locale();
		$parts  = explode( '_', $locale, 2 );
		$lang   = $parts[0];
		$tbl    = self::$table;

		if ( in_array( $lang, [ 'bg', 'mk', 'ru', 'be' ], true ) ) {
			$tbl['Ц'] = 'C';
			$tbl['ц'] = 'c';
		}

		if ( in_array( $lang, [ 'bg', 'mk', 'ru' ], true ) ) {
			$tbl['Г'] = 'G';
			$tbl['г'] = 'g';
			$tbl['И'] = 'I';
			$tbl['и'] = 'i';
		}

		switch ( $lang ) {
			case 'bg':
				$tbl['Щ'] = 'STH';
				$tbl['щ'] = 'sth';
				$tbl['Ъ'] = 'A';
				$tbl['ъ'] = 'a';
				break;

			case 'ru':
				$tbl['Щ'] = 'SHH';
				$tbl['щ'] = 'shh';
				break;

			default:
				break;
		}

		return $tbl;
	}

	/**
	 * @return array<string,string>
	 */
	private static function getReTable(): array
    {
		$locale = get_locale();
		$parts  = explode( '_', $locale, 2 );
		$lang   = $parts[0];

		return ( 'uk' === $lang ) ? self::$retable : [];
	}
}