<?php

/**
 * Test: Nette\Utils\Strings::fixEncoding()
 */

use Nette\Utils\Strings,
	Tester\Assert;


require __DIR__ . '/../bootstrap.php';

// Based on "UTF-8 decoder capability and stress test" by Markus Kuhn
// http://www.cl.cam.ac.uk/~mgk25/ucs/examples/UTF-8-test.txt
$tests = array(
	'1  Some correct UTF-8 text' => array(
		"\xCE\xBA\xE1\xBD\xB9\xCF\x83\xCE\xBC\xCE\xB5",
		"\xCE\xBA\xE1\xBD\xB9\xCF\x83\xCE\xBC\xCE\xB5",
	),
	'2  Boundary condition test cases' => array(
		'2.1  First possible sequence of a certain length' => array(
			'2.1.1  1 byte  (U-00000000)' => array(
				"\x00",
				"\x00",
			),
			'2.1.2  2 bytes (U-00000080)' => array(
				"\xC2\x80",
				"\xC2\x80",
			),
			'2.1.3  3 bytes (U-00000800)' => array(
				"\xE0\xA0\x80",
				"\xE0\xA0\x80",
			),
			'2.1.4  4 bytes (U-00010000)' => array(
				"\xF0\x90\x80\x80",
				"\xF0\x90\x80\x80",
			),
			'2.1.5  5 bytes (U-00200000)' => array(
				"\xF8\x88\x80\x80\x80",
				"",
			),
			'2.1.6  6 bytes (U-04000000)' => array(
				"\xFC\x84\x80\x80\x80\x80",
				"",
			),
		),
		'2.2  Last possible sequence of a certain length' => array(
			'2.2.1  1 byte  (U-0000007F)' => array(
				"\x7F",
				"\x7F",
			),
			'2.2.2  2 bytes (U-000007FF)' => array(
				"\xDF\xBF",
				"\xDF\xBF",
			),
			'2.2.3  3 bytes (U-0000FFFF)' => array(
				"\xEF\xBF\xBF",
				"\xEF\xBF\xBF",
			),
			'2.2.4  4 bytes (U-001FFFFF)' => array(
				"\xF7\xBF\xBF\xBF",
				"",
			),
			'2.2.5  5 bytes (U-03FFFFFF)' => array(
				"\xFB\xBF\xBF\xBF\xBF",
				"",
			),
			'2.2.6  6 bytes (U-7FFFFFFF)' => array(
				"\xFD\xBF\xBF\xBF\xBF\xBF",
				"",
			),
		),
		'2.3  Other boundary conditions' => array(
			'2.3.1  U-0000D7FF' => array(
				"\xED\x9F\xBF",
				"\xED\x9F\xBF",
			),
			'2.3.2  U-0000E000' => array(
				"\xEE\x80\x80",
				"\xEE\x80\x80",
			),
			'2.3.3  U-0000FFFD' => array(
				"\xEF\xBF\xBD",
				"\xEF\xBF\xBD",
			),
			'2.3.4  U-0010FFFF' => array(
				"\xF4\x8F\xBF\xBF",
				"\xF4\x8F\xBF\xBF",
			),
			'2.3.5  U-00110000' => array(
				"\xF4\x90\x80\x80",
				"",
			),
		),
	),
	'3  Malformed sequences' => array(
		'3.1  Unexpected continuation bytes' => array(
			'3.1.1  First continuation byte 0x80' => array(
				"\x80",
				"",
			),
			'3.1.2  Last  continuation byte 0xbf' => array(
				"\xBF",
				"",
			),
			'3.1.3  2 continuation bytes' => array(
				"\x80\xBF",
				"",
			),
			'3.1.4  3 continuation bytes' => array(
				"\x80\xBF\x80",
				"",
			),
			'3.1.5  4 continuation bytes' => array(
				"\x80\xBF\x80\xBF",
				"",
			),
			'3.1.6  5 continuation bytes' => array(
				"\x80\xBF\x80\xBF\x80",
				"",
			),
			'3.1.7  6 continuation bytes' => array(
				"\x80\xBF\x80\xBF\x80\xBF",
				"",
			),
			'3.1.8  7 continuation bytes' => array(
				"\x80\xBF\x80\xBF\x80\xBF\x80",
				"",
			),
			'3.1.9  Sequence of all 64 possible continuation bytes (0x80-0xbf)' => array(
				implode('', range("\x80", "\xBF")),
				"",
			),
		),
		'3.2  Lonely start characters' => array(
			'3.2.1  All 32 first bytes of 2-byte sequences (0xc0-0xdf), each followed by a space character' => array(
				implode(' ', range("\xC0", "\xDF")) . ' ',
				str_repeat(' ', 32),
			),
			'3.2.2  All 16 first bytes of 3-byte sequences (0xe0-0xef), each followed by a space character' => array(
				implode(' ', range("\xE0", "\xEF")) . ' ',
				str_repeat(' ', 16 ),
			),
			'3.2.3  All 8 first bytes of 4-byte sequences (0xf0-0xf7), each followed by a space character' => array(
				implode(' ', range("\xF0", "\xF7")) . ' ',
				str_repeat(' ', 8),
			),
			'3.2.4  All 4 first bytes of 5-byte sequences (0xf8-0xfb), each followed by a space character' => array(
				implode(' ', range("\xF8", "\xFB")) . ' ',
				str_repeat(' ', 4),
			),
			'3.2.5  All 2 first bytes of 6-byte sequences (0xfc-0xfd), each followed by a space character' => array(
				implode(' ', range("\xFC", "\xFD")) . ' ',
				str_repeat(' ', 2),
			),
		),
		'3.3  Sequences with last continuation byte missing' => array(
			'3.3.1  2-byte sequence with last byte missing (U+0000)' => array(
				"\xC0",
				"",
			),
			'3.3.2  3-byte sequence with last byte missing (U+0000)' => array(
				"\xE0\x80",
				"",
			),
			'3.3.3  4-byte sequence with last byte missing (U+0000)' => array(
				"\xF0\x80\x80",
				"",
			),
			'3.3.4  5-byte sequence with last byte missing (U+0000)' => array(
				"\xF8\x80\x80\x80",
				"",
			),
			'3.3.5  6-byte sequence with last byte missing (U+0000)' => array(
				"\xFC\x80\x80\x80\x80",
				"",
			),
			'3.3.6  2-byte sequence with last byte missing (U-000007FF)' => array(
				"\xDF",
				"",
			),
			'3.3.7  3-byte sequence with last byte missing (U-0000FFFF)' => array(
				"\xEF\xBF",
				"",
			),
			'3.3.8  4-byte sequence with last byte missing (U-001FFFFF)' => array(
				"\xF7\xBF\xBF",
				"",
			),
			'3.3.9  5-byte sequence with last byte missing (U-03FFFFFF)' => array(
				"\xFB\xBF\xBF\xBF",
				"",
			),
			'3.3.10 6-byte sequence with last byte missing (U-7FFFFFFF)' => array(
				"\xFD\xBF\xBF\xBF\xBF",
				"",
			),
		),
		'3.4  Concatenation of incomplete sequences' => array(
			"\xC0\xE0\x80\xF0\x80\x80\xF8\x80\x80\x80\xFC\x80\x80\x80\x80\xDF\xEF\xBF\xF7\xBF\xBF\xFB\xBF\xBF\xBF\xFD\xBF\xBF\xBF\xBF",
			"",
		),
		'3.5  Impossible bytes' => array(
			'3.5.1  fe' => array(
				"\xFE",
				"",
			),
			'3.5.2  ff' => array(
				"\xFF",
				"",
			),
			'3.5.3  fe fe ff ff' => array(
				"\xFE\xFE\xFF\xFF",
				"",
			),
		),
	),
	'4  Overlong sequences' => array(
		'4.1  Examples of an overlong ASCII character' => array(
			'4.1.1 U+002F = c0 af' => array(
				"\xC0\xAF",
				"",
			),
			'4.1.2 U+002F = e0 80 af' => array(
				"\xE0\x80\xAF",
				"",
			),
			'4.1.3 U+002F = f0 80 80 af' => array(
				"\xF0\x80\x80\xAF",
				"",
			),
			'4.1.4 U+002F = f8 80 80 80 af' => array(
				"\xF8\x80\x80\x80\xAF",
				"",
			),
			'4.1.5 U+002F = fc 80 80 80 80 af' => array(
				"\xFC\x80\x80\x80\x80\xAF",
				"",
			),
		),
		'4.2  Maximum overlong sequences' => array(
			'4.2.1  U-0000007F = c1 bf' => array(
				"\xC1\xBF",
				"",
			),
			'4.2.2  U-000007FF = e0 9f bf' => array(
				"\xE0\x9F\xBF",
				"",
			),
			'4.2.3  U-0000FFFF = f0 8f bf bf' => array(
				"\xF0\x8F\xBF\xBF",
				"",
			),
			'4.2.4  U-001FFFFF = f8 87 bf bf bf' => array(
				"\xF8\x87\xBF\xBF\xBF",
				"",
			),
			'4.2.5  U-03FFFFFF = fc 83 bf bf bf bf' => array(
				"\xFC\x83\xBF\xBF\xBF\xBF",
				"",
			),
		),
		'4.3  Overlong representation of the NUL character' => array(
			'4.3.1  U+0000 = c0 80' => array(
				"\xC0\x80",
				"",
			),
			'4.3.2  U+0000 = e0 80 80' => array(
				"\xE0\x80\x80",
				"",
			),
			'4.3.3  U+0000 = f0 80 80 80' => array(
				"\xF0\x80\x80\x80",
				"",
			),
			'4.3.4  U+0000 = f8 80 80 80 80' => array(
				"\xF8\x80\x80\x80\x80",
				"",
			),
			'4.3.5  U+0000 = fc 80 80 80 80 80' => array(
				"\xFC\x80\x80\x80\x80\x80",
				"",
			),
		),
	),
	'5  Illegal code positions' => array(
		'5.1 Single UTF-16 surrogates' => array(
			'5.1.1  U+D800 = ed a0 80' => array(
				"\xED\xA0\x80",
				"",
			),
			'5.1.2  U+DB7F = ed ad bf' => array(
				"\xED\xAD\xBF",
				"",
			),
			'5.1.3  U+DB80 = ed ae 80' => array(
				"\xED\xAE\x80",
				"",
			),
			'5.1.4  U+DBFF = ed af bf' => array(
				"\xED\xAF\xBF",
				"",
			),
			'5.1.5  U+DC00 = ed b0 80' => array(
				"\xED\xB0\x80",
				"",
			),
			'5.1.6  U+DF80 = ed be 80' => array(
				"\xED\xBE\x80",
				"",
			),
			'5.1.7  U+DFFF = ed bf bf' => array(
				"\xED\xBF\xBF",
				"",
			),
		),
		'5.2 Paired UTF-16 surrogates' => array(
			'5.2.1  U+D800 U+DC00 = ed a0 80 ed b0 80' => array(
				"\xED\xA0\x80\xED\xB0\x80",
				"",
			),
			'5.2.2  U+D800 U+DFFF = ed a0 80 ed bf bf' => array(
				"\xED\xA0\x80\xED\xBF\xBF",
				"",
			),
			'5.2.3  U+DB7F U+DC00 = ed ad bf ed b0 80' => array(
				"\xED\xAD\xBF\xED\xB0\x80",
				"",
			),
			'5.2.4  U+DB7F U+DFFF = ed ad bf ed bf bf' => array(
				"\xED\xAD\xBF\xED\xBF\xBF",
				"",
			),
			'5.2.5  U+DB80 U+DC00 = ed ae 80 ed b0 80' => array(
				"\xED\xAE\x80\xED\xB0\x80",
				"",
			),
			'5.2.6  U+DB80 U+DFFF = ed ae 80 ed bf bf' => array(
				"\xED\xAE\x80\xED\xBF\xBF",
				"",
			),
			'5.2.7  U+DBFF U+DC00 = ed af bf ed b0 80' => array(
				"\xED\xAF\xBF\xED\xB0\x80",
				"",
			),
			'5.2.8  U+DBFF U+DFFF = ed af bf ed bf bf' => array(
				"\xED\xAF\xBF\xED\xBF\xBF",
				"",
			),
		),
		// noncharacters are allowed according to http://www.unicode.org/versions/corrigendum9.html
		'5.3 Other illegal code positions' => array(
			'5.3.1  U+FFFE = ef bf be' => array(
				"\xEF\xBF\xBE",
				"\xEF\xBF\xBE",
			),
			'5.3.2  U+FFFF = ef bf bf' => array(
				"\xEF\xBF\xBF",
				"\xEF\xBF\xBF",
			),
		),
	),
);

if (PHP_VERSION_ID < 50400 && trim(ICONV_IMPL, '"') === 'libiconv') {
	unset($tests['3  Malformed sequences']['3.2  Lonely start characters']);
}
if (PHP_VERSION_ID < 50400 && trim(ICONV_IMPL, '"') === 'libiconv') {
	unset($tests['5  Illegal code positions']['5.3 Other illegal code positions']['5.3.1  U+FFFE = ef bf be']);
}

$stack = array($tests);
while ($item = array_pop($stack)) {
	if (isset($item[0])) {
		list($in, $out, $label) = $item;
		echo "$label\n";
		Assert::same( 'a' . $out . 'b', Strings::fixEncoding('a' . $in . 'b') );

	} else {
		foreach (array_reverse($item) as $label => $tests) {
			$stack[] = $tests + (isset($tests[0]) ? array(2 => $label) : array());
		}
	}
}
