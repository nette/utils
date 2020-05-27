<?php

/**
 * Test: Nette\Utils\Strings::fixEncoding()
 */

declare(strict_types=1);

use Nette\Utils\Strings;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

// Based on "UTF-8 decoder capability and stress test" by Markus Kuhn
// http://www.cl.cam.ac.uk/~mgk25/ucs/examples/UTF-8-test.txt
$tests = [
	'1  Some correct UTF-8 text' => [
		"\u{3BA}\u{1F79}\u{3C3}\u{3BC}\u{3B5}",
		"\u{3BA}\u{1F79}\u{3C3}\u{3BC}\u{3B5}",
	],
	'2  Boundary condition test cases' => [
		'2.1  First possible sequence of a certain length' => [
			'2.1.1  1 byte  (U-00000000)' => [
				"\x00",
				"\x00",
			],
			'2.1.2  2 bytes (U-00000080)' => [
				"\u{80}",
				"\u{80}",
			],
			'2.1.3  3 bytes (U-00000800)' => [
				"\u{800}",
				"\u{800}",
			],
			'2.1.4  4 bytes (U-00010000)' => [
				"\u{10000}",
				"\u{10000}",
			],
			'2.1.5  5 bytes (U-00200000)' => [
				"\xF8\x88\x80\x80\x80",
				'',
			],
			'2.1.6  6 bytes (U-04000000)' => [
				"\xFC\x84\x80\x80\x80\x80",
				'',
			],
		],
		'2.2  Last possible sequence of a certain length' => [
			'2.2.1  1 byte  (U-0000007F)' => [
				"\x7F",
				"\x7F",
			],
			'2.2.2  2 bytes (U-000007FF)' => [
				"\u{7FF}",
				"\u{7FF}",
			],
			'2.2.3  3 bytes (U-0000FFFF)' => [
				"\u{FFFF}",
				"\u{FFFF}",
			],
			'2.2.4  4 bytes (U-001FFFFF)' => [
				"\xF7\xBF\xBF\xBF",
				'',
			],
			'2.2.5  5 bytes (U-03FFFFFF)' => [
				"\xFB\xBF\xBF\xBF\xBF",
				'',
			],
			'2.2.6  6 bytes (U-7FFFFFFF)' => [
				"\xFD\xBF\xBF\xBF\xBF\xBF",
				'',
			],
		],
		'2.3  Other boundary conditions' => [
			'2.3.1  U-0000D7FF' => [
				"\u{D7FF}",
				"\u{D7FF}",
			],
			'2.3.2  U-0000E000' => [
				"\u{E000}",
				"\u{E000}",
			],
			'2.3.3  U-0000FFFD' => [
				"\u{FFFD}",
				"\u{FFFD}",
			],
			'2.3.4  U-0010FFFF' => [
				"\u{10FFFF}",
				"\u{10FFFF}",
			],
			'2.3.5  U-00110000' => [
				"\xF4\x90\x80\x80",
				'',
			],
		],
	],
	'3  Malformed sequences' => [
		'3.1  Unexpected continuation bytes' => [
			'3.1.1  First continuation byte 0x80' => [
				"\x80",
				'',
			],
			'3.1.2  Last  continuation byte 0xbf' => [
				"\xBF",
				'',
			],
			'3.1.3  2 continuation bytes' => [
				"\x80\xBF",
				'',
			],
			'3.1.4  3 continuation bytes' => [
				"\x80\xBF\x80",
				'',
			],
			'3.1.5  4 continuation bytes' => [
				"\x80\xBF\x80\xBF",
				'',
			],
			'3.1.6  5 continuation bytes' => [
				"\x80\xBF\x80\xBF\x80",
				'',
			],
			'3.1.7  6 continuation bytes' => [
				"\x80\xBF\x80\xBF\x80\xBF",
				'',
			],
			'3.1.8  7 continuation bytes' => [
				"\x80\xBF\x80\xBF\x80\xBF\x80",
				'',
			],
			'3.1.9  Sequence of all 64 possible continuation bytes (0x80-0xbf)' => [
				implode('', range("\x80", "\xBF")),
				'',
			],
		],
		'3.2  Lonely start characters' => [
			'3.2.1  All 32 first bytes of 2-byte sequences (0xc0-0xdf), each followed by a space character' => [
				implode(' ', range("\xC0", "\xDF")) . ' ',
				str_repeat(' ', 32),
			],
			'3.2.2  All 16 first bytes of 3-byte sequences (0xe0-0xef), each followed by a space character' => [
				implode(' ', range("\xE0", "\xEF")) . ' ',
				str_repeat(' ', 16),
			],
			'3.2.3  All 8 first bytes of 4-byte sequences (0xf0-0xf7), each followed by a space character' => [
				implode(' ', range("\xF0", "\xF7")) . ' ',
				str_repeat(' ', 8),
			],
			'3.2.4  All 4 first bytes of 5-byte sequences (0xf8-0xfb), each followed by a space character' => [
				implode(' ', range("\xF8", "\xFB")) . ' ',
				str_repeat(' ', 4),
			],
			'3.2.5  All 2 first bytes of 6-byte sequences (0xfc-0xfd), each followed by a space character' => [
				implode(' ', range("\xFC", "\xFD")) . ' ',
				str_repeat(' ', 2),
			],
		],
		'3.3  Sequences with last continuation byte missing' => [
			'3.3.1  2-byte sequence with last byte missing (U+0000)' => [
				"\xC0",
				'',
			],
			'3.3.2  3-byte sequence with last byte missing (U+0000)' => [
				"\xE0\x80",
				'',
			],
			'3.3.3  4-byte sequence with last byte missing (U+0000)' => [
				"\xF0\x80\x80",
				'',
			],
			'3.3.4  5-byte sequence with last byte missing (U+0000)' => [
				"\xF8\x80\x80\x80",
				'',
			],
			'3.3.5  6-byte sequence with last byte missing (U+0000)' => [
				"\xFC\x80\x80\x80\x80",
				'',
			],
			'3.3.6  2-byte sequence with last byte missing (U-000007FF)' => [
				"\xDF",
				'',
			],
			'3.3.7  3-byte sequence with last byte missing (U-0000FFFF)' => [
				"\xEF\xBF",
				'',
			],
			'3.3.8  4-byte sequence with last byte missing (U-001FFFFF)' => [
				"\xF7\xBF\xBF",
				'',
			],
			'3.3.9  5-byte sequence with last byte missing (U-03FFFFFF)' => [
				"\xFB\xBF\xBF\xBF",
				'',
			],
			'3.3.10 6-byte sequence with last byte missing (U-7FFFFFFF)' => [
				"\xFD\xBF\xBF\xBF\xBF",
				'',
			],
		],
		'3.4  Concatenation of incomplete sequences' => [
			"\xC0\xE0\x80\xF0\x80\x80\xF8\x80\x80\x80\xFC\x80\x80\x80\x80\xDF\xEF\xBF\xF7\xBF\xBF\xFB\xBF\xBF\xBF\xFD\xBF\xBF\xBF\xBF",
			'',
		],
		'3.5  Impossible bytes' => [
			'3.5.1  fe' => [
				"\xFE",
				'',
			],
			'3.5.2  ff' => [
				"\xFF",
				'',
			],
			'3.5.3  fe fe ff ff' => [
				"\xFE\xFE\xFF\xFF",
				'',
			],
		],
	],
	'4  Overlong sequences' => [
		'4.1  Examples of an overlong ASCII character' => [
			'4.1.1 U+002F = c0 af' => [
				"\xC0\xAF",
				'',
			],
			'4.1.2 U+002F = e0 80 af' => [
				"\xE0\x80\xAF",
				'',
			],
			'4.1.3 U+002F = f0 80 80 af' => [
				"\xF0\x80\x80\xAF",
				'',
			],
			'4.1.4 U+002F = f8 80 80 80 af' => [
				"\xF8\x80\x80\x80\xAF",
				'',
			],
			'4.1.5 U+002F = fc 80 80 80 80 af' => [
				"\xFC\x80\x80\x80\x80\xAF",
				'',
			],
		],
		'4.2  Maximum overlong sequences' => [
			'4.2.1  U-0000007F = c1 bf' => [
				"\xC1\xBF",
				'',
			],
			'4.2.2  U-000007FF = e0 9f bf' => [
				"\xE0\x9F\xBF",
				'',
			],
			'4.2.3  U-0000FFFF = f0 8f bf bf' => [
				"\xF0\x8F\xBF\xBF",
				'',
			],
			'4.2.4  U-001FFFFF = f8 87 bf bf bf' => [
				"\xF8\x87\xBF\xBF\xBF",
				'',
			],
			'4.2.5  U-03FFFFFF = fc 83 bf bf bf bf' => [
				"\xFC\x83\xBF\xBF\xBF\xBF",
				'',
			],
		],
		'4.3  Overlong representation of the NUL character' => [
			'4.3.1  U+0000 = c0 80' => [
				"\xC0\x80",
				'',
			],
			'4.3.2  U+0000 = e0 80 80' => [
				"\xE0\x80\x80",
				'',
			],
			'4.3.3  U+0000 = f0 80 80 80' => [
				"\xF0\x80\x80\x80",
				'',
			],
			'4.3.4  U+0000 = f8 80 80 80 80' => [
				"\xF8\x80\x80\x80\x80",
				'',
			],
			'4.3.5  U+0000 = fc 80 80 80 80 80' => [
				"\xFC\x80\x80\x80\x80\x80",
				'',
			],
		],
	],
	'5  Illegal code positions' => [
		'5.1 Single UTF-16 surrogates' => [
			'5.1.1  U+D800 = ed a0 80' => [
				"\xED\xA0\x80",
				'',
			],
			'5.1.2  U+DB7F = ed ad bf' => [
				"\xED\xAD\xBF",
				'',
			],
			'5.1.3  U+DB80 = ed ae 80' => [
				"\xED\xAE\x80",
				'',
			],
			'5.1.4  U+DBFF = ed af bf' => [
				"\xED\xAF\xBF",
				'',
			],
			'5.1.5  U+DC00 = ed b0 80' => [
				"\xED\xB0\x80",
				'',
			],
			'5.1.6  U+DF80 = ed be 80' => [
				"\xED\xBE\x80",
				'',
			],
			'5.1.7  U+DFFF = ed bf bf' => [
				"\xED\xBF\xBF",
				'',
			],
		],
		'5.2 Paired UTF-16 surrogates' => [
			'5.2.1  U+D800 U+DC00 = ed a0 80 ed b0 80' => [
				"\xED\xA0\x80\xED\xB0\x80",
				'',
			],
			'5.2.2  U+D800 U+DFFF = ed a0 80 ed bf bf' => [
				"\xED\xA0\x80\xED\xBF\xBF",
				'',
			],
			'5.2.3  U+DB7F U+DC00 = ed ad bf ed b0 80' => [
				"\xED\xAD\xBF\xED\xB0\x80",
				'',
			],
			'5.2.4  U+DB7F U+DFFF = ed ad bf ed bf bf' => [
				"\xED\xAD\xBF\xED\xBF\xBF",
				'',
			],
			'5.2.5  U+DB80 U+DC00 = ed ae 80 ed b0 80' => [
				"\xED\xAE\x80\xED\xB0\x80",
				'',
			],
			'5.2.6  U+DB80 U+DFFF = ed ae 80 ed bf bf' => [
				"\xED\xAE\x80\xED\xBF\xBF",
				'',
			],
			'5.2.7  U+DBFF U+DC00 = ed af bf ed b0 80' => [
				"\xED\xAF\xBF\xED\xB0\x80",
				'',
			],
			'5.2.8  U+DBFF U+DFFF = ed af bf ed bf bf' => [
				"\xED\xAF\xBF\xED\xBF\xBF",
				'',
			],
		],
		// noncharacters are allowed according to http://www.unicode.org/versions/corrigendum9.html
		'5.3 Other illegal code positions' => [
			'5.3.1  U+FFFE = ef bf be' => [
				"\u{FFFE}",
				"\u{FFFE}",
			],
			'5.3.2  U+FFFF = ef bf bf' => [
				"\u{FFFF}",
				"\u{FFFF}",
			],
		],
	],
];


$stack = [$tests];
while ($item = array_pop($stack)) {
	if (isset($item[0])) {
		[$in, $out, $label] = $item;
		echo "$label\n";
		Assert::same('a' . $out . 'b', Strings::fixEncoding('a' . $in . 'b'));

	} else {
		foreach (array_reverse($item) as $label => $tests) {
			$stack[] = $tests + (isset($tests[0]) ? [2 => $label] : []);
		}
	}
}
