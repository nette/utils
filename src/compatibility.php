<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils {
	if (false) {
		/** @deprecated use Nette\HtmlStringable */
		interface IHtmlString
		{
		}
	} elseif (!interface_exists(IHtmlString::class)) {
		class_alias(\Nette\HtmlStringable::class, IHtmlString::class);
	}
}
