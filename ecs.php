<?php

/**
 * Rules for Nette Coding Standard
 * https://github.com/nette/coding-standard
 */

declare(strict_types=1);


return function (Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator): void {
	$containerConfigurator->import(PRESET_DIR . '/php80.php');

	$parameters = $containerConfigurator->parameters();

	$parameters->set('skip', [
		'fixtures*/*',

		// RemoteStream extends streamWrapper
		PHP_CodeSniffer\Standards\PSR1\Sniffs\Methods\CamelCapsMethodNameSniff::class => [
			'tests/Utils/FileSystem.phpt',
		],

		// use function
		PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer::class => [
			'src/Utils/Arrays.php',
			'src/Utils/Callback.php',
			'src/Utils/Html.php',
			'src/Utils/Strings.php',
		],

		// use function
		PhpCsFixer\Fixer\Import\OrderedImportsFixer::class => [
			'src/Utils/Arrays.php',
			'src/Utils/Callback.php',
			'src/Utils/Html.php',
			'src/Utils/Strings.php',
		],
	]);
};
