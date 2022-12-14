<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/** @internal */
final class FinderBatch
{
	use Nette\SmartObject;

	public array $find = [];
	public array $in = [];
	public array $filters = [];
	public array $recurseFilters = [];
}
