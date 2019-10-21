<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Utils;

use Nette;


/**
 * Basic manipulation with images.
 *
 * <code>
 * $image = Image::fromFile('nette.jpg');
 * $image->resize(150, 100);
 * $image->sharpen();
 * $image->send();
 * </code>
 *
 * @method void alphaBlending(bool $on)
 * @method void antialias(bool $on)
 * @method void arc($x, $y, $w, $h, $start, $end, $color)
 * @method void char(int $font, $x, $y, string $char, $color)
 * @method void charUp(int $font, $x, $y, string $char, $color)
 * @method int colorAllocate($red, $green, $blue)
 * @method int colorAllocateAlpha($red, $green, $blue, $alpha)
 * @method int colorAt($x, $y)
 * @method int colorClosest($red, $green, $blue)
 * @method int colorClosestAlpha($red, $green, $blue, $alpha)
 * @method int colorClosestHWB($red, $green, $blue)
 * @method void colorDeallocate($color)
 * @method int colorExact($red, $green, $blue)
 * @method int colorExactAlpha($red, $green, $blue, $alpha)
 * @method void colorMatch(Image $image2)
 * @method int colorResolve($red, $green, $blue)
 * @method int colorResolveAlpha($red, $green, $blue, $alpha)
 * @method void colorSet($index, $red, $green, $blue)
 * @method array colorsForIndex($index)
 * @method int colorsTotal()
 * @method int colorTransparent($color = null)
 * @method void convolution(array $matrix, float $div, float $offset)
 * @method void copy(Image $src, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH)
 * @method void copyMerge(Image $src, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH, $opacity)
 * @method void copyMergeGray(Image $src, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH, $opacity)
 * @method void copyResampled(Image $src, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH)
 * @method void copyResized(Image $src, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH)
 * @method Image cropAuto(int $mode = -1, float $threshold = .5, int $color = -1)
 * @method void dashedLine($x1, $y1, $x2, $y2, $color)
 * @method void ellipse($cx, $cy, $w, $h, $color)
 * @method void fill($x, $y, $color)
 * @method void filledArc($cx, $cy, $w, $h, $s, $e, $color, $style)
 * @method void filledEllipse($cx, $cy, $w, $h, $color)
 * @method void filledPolygon(array $points, $numPoints, $color)
 * @method void filledRectangle($x1, $y1, $x2, $y2, $color)
 * @method void fillToBorder($x, $y, $border, $color)
 * @method void filter($filtertype)
 * @method void flip(int $mode)
 * @method array ftText($size, $angle, $x, $y, $col, string $fontFile, string $text, array $extrainfo = null)
 * @method void gammaCorrect(float $inputgamma, float $outputgamma)
 * @method int interlace($interlace = null)
 * @method bool isTrueColor()
 * @method void layerEffect($effect)
 * @method void line($x1, $y1, $x2, $y2, $color)
 * @method void paletteCopy(Image $source)
 * @method void paletteToTrueColor()
 * @method void polygon(array $points, $numPoints, $color)
 * @method array psText(string $text, $font, $size, $color, $backgroundColor, $x, $y, $space = null, $tightness = null, float $angle = null, $antialiasSteps = null)
 * @method void rectangle($x1, $y1, $x2, $y2, $col)
 * @method Image rotate(float $angle, $backgroundColor)
 * @method void saveAlpha(bool $saveflag)
 * @method Image scale(int $newWidth, int $newHeight = -1, int $mode = IMG_BILINEAR_FIXED)
 * @method void setBrush(Image $brush)
 * @method void setPixel($x, $y, $color)
 * @method void setStyle(array $style)
 * @method void setThickness($thickness)
 * @method void setTile(Image $tile)
 * @method void string($font, $x, $y, string $s, $col)
 * @method void stringUp($font, $x, $y, string $s, $col)
 * @method void trueColorToPalette(bool $dither, $ncolors)
 * @method array ttfText($size, $angle, $x, $y, $color, string $fontfile, string $text)
 * @property-read int $width
 * @property-read int $height
 * @property-read resource $imageResource
 */
class Image
{
	use Nette\SmartObject;

	/** {@link resize()} only shrinks images */
	public const SHRINK_ONLY = 0b0001;

	/** {@link resize()} will ignore aspect ratio */
	public const STRETCH = 0b0010;

	/** {@link resize()} fits in given area so its dimensions are less than or equal to the required dimensions */
	public const FIT = 0b0000;

	/** {@link resize()} fills given area so its dimensions are greater than or equal to the required dimensions */
	public const FILL = 0b0100;

	/** {@link resize()} fills given area exactly */
	public const EXACT = 0b1000;

	/** image types */
	public const
		JPEG = IMAGETYPE_JPEG,
		PNG = IMAGETYPE_PNG,
		GIF = IMAGETYPE_GIF,
		WEBP = 18; // IMAGETYPE_WEBP is available as of PHP 7.1

	public const EMPTY_GIF = "GIF89a\x01\x00\x01\x00\x80\x00\x00\x00\x00\x00\x00\x00\x00!\xf9\x04\x01\x00\x00\x00\x00,\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02D\x01\x00;";

	private const FORMATS = [self::JPEG => 'jpeg', self::PNG => 'png', self::GIF => 'gif', self::WEBP => 'webp'];

	/** @var resource */
	private $image;


	/**
	 * Returns RGB color (0..255) and transparency (0..127).
	 */
	public static function rgb(int $red, int $green, int $blue, int $transparency = 0): array
	{
		return [
			'red' => max(0, min(255, $red)),
			'green' => max(0, min(255, $green)),
			'blue' => max(0, min(255, $blue)),
			'alpha' => max(0, min(127, $transparency)),
		];
	}


	/**
	 * Opens image from file.
	 * @throws Nette\NotSupportedException if gd extension is not loaded
	 * @throws UnknownImageFileException if file not found or file type is not known
	 * @return static
	 */
	public static function fromFile(string $file, int &$detectedFormat = null)
	{
		if (!extension_loaded('gd')) {
			throw new Nette\NotSupportedException('PHP extension GD is not loaded.');
		}

		$detectedFormat = @getimagesize($file)[2]; // @ - files smaller than 12 bytes causes read error
		if (!isset(self::FORMATS[$detectedFormat])) {
			$detectedFormat = null;
			throw new UnknownImageFileException(is_file($file) ? "Unknown type of file '$file'." : "File '$file' not found.");
		}
		return new static(Callback::invokeSafe('imagecreatefrom' . image_type_to_extension($detectedFormat, false), [$file], function (string $message): void {
			throw new ImageException($message);
		}));
	}


	/**
	 * Create a new image from the image stream in the string.
	 * @return static
	 * @throws ImageException
	 */
	public static function fromString(string $s, int &$detectedFormat = null)
	{
		if (!extension_loaded('gd')) {
			throw new Nette\NotSupportedException('PHP extension GD is not loaded.');
		}

		if (func_num_args() > 1) {
			$tmp = @getimagesizefromstring($s)[2]; // @ - strings smaller than 12 bytes causes read error
			$detectedFormat = isset(self::FORMATS[$tmp]) ? $tmp : null;
		}

		return new static(Callback::invokeSafe('imagecreatefromstring', [$s], function (string $message): void {
			throw new ImageException($message);
		}));
	}


	/**
	 * Creates blank image.
	 * @return static
	 */
	public static function fromBlank(int $width, int $height, array $color = null)
	{
		if (!extension_loaded('gd')) {
			throw new Nette\NotSupportedException('PHP extension GD is not loaded.');
		}

		if ($width < 1 || $height < 1) {
			throw new Nette\InvalidArgumentException('Image width and height must be greater than zero.');
		}

		$image = imagecreatetruecolor($width, $height);
		if ($color) {
			$color += ['alpha' => 0];
			$color = imagecolorresolvealpha($image, $color['red'], $color['green'], $color['blue'], $color['alpha']);
			imagealphablending($image, false);
			imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $color);
			imagealphablending($image, true);
		}
		return new static($image);
	}


	/**
	 * Wraps GD image.
	 * @param  resource  $image
	 */
	public function __construct($image)
	{
		$this->setImageResource($image);
		imagesavealpha($image, true);
	}


	/**
	 * Returns image width.
	 */
	public function getWidth(): int
	{
		return imagesx($this->image);
	}


	/**
	 * Returns image height.
	 */
	public function getHeight(): int
	{
		return imagesy($this->image);
	}


	/**
	 * Sets image resource.
	 * @param  resource  $image
	 * @return static
	 */
	protected function setImageResource($image)
	{
		if (!is_resource($image) || get_resource_type($image) !== 'gd') {
			throw new Nette\InvalidArgumentException('Image is not valid.');
		}
		$this->image = $image;
		return $this;
	}


	/**
	 * Returns image GD resource.
	 * @return resource
	 */
	public function getImageResource()
	{
		return $this->image;
	}


	/**
	 * Resizes image.
	 * @param  int|string  $width in pixels or percent
	 * @param  int|string  $height in pixels or percent
	 * @return static
	 */
	public function resize($width, $height, int $flags = self::FIT)
	{
		if ($flags & self::EXACT) {
			return $this->resize($width, $height, self::FILL)->crop('50%', '50%', $width, $height);
		}

		[$newWidth, $newHeight] = static::calculateSize($this->getWidth(), $this->getHeight(), $width, $height, $flags);

		if ($newWidth !== $this->getWidth() || $newHeight !== $this->getHeight()) { // resize
			$newImage = static::fromBlank($newWidth, $newHeight, self::rgb(0, 0, 0, 127))->getImageResource();
			imagecopyresampled(
				$newImage, $this->image,
				0, 0, 0, 0,
				$newWidth, $newHeight, $this->getWidth(), $this->getHeight()
			);
			$this->image = $newImage;
		}

		if ($width < 0 || $height < 0) {
			imageflip($this->image, $width < 0 ? ($height < 0 ? IMG_FLIP_BOTH : IMG_FLIP_HORIZONTAL) : IMG_FLIP_VERTICAL);
		}
		return $this;
	}


	/**
	 * Calculates dimensions of resized image.
	 * @param  int|string  $newWidth in pixels or percent
	 * @param  int|string  $newHeight in pixels or percent
	 */
	public static function calculateSize(int $srcWidth, int $srcHeight, $newWidth, $newHeight, int $flags = self::FIT): array
	{
		if (is_string($newWidth) && substr($newWidth, -1) === '%') {
			$newWidth = (int) round($srcWidth / 100 * abs(substr($newWidth, 0, -1)));
			$percents = true;
		} else {
			$newWidth = (int) abs($newWidth);
		}

		if (is_string($newHeight) && substr($newHeight, -1) === '%') {
			$newHeight = (int) round($srcHeight / 100 * abs(substr($newHeight, 0, -1)));
			$flags |= empty($percents) ? 0 : self::STRETCH;
		} else {
			$newHeight = (int) abs($newHeight);
		}

		if ($flags & self::STRETCH) { // non-proportional
			if (empty($newWidth) || empty($newHeight)) {
				throw new Nette\InvalidArgumentException('For stretching must be both width and height specified.');
			}

			if ($flags & self::SHRINK_ONLY) {
				$newWidth = (int) round($srcWidth * min(1, $newWidth / $srcWidth));
				$newHeight = (int) round($srcHeight * min(1, $newHeight / $srcHeight));
			}

		} else {  // proportional
			if (empty($newWidth) && empty($newHeight)) {
				throw new Nette\InvalidArgumentException('At least width or height must be specified.');
			}

			$scale = [];
			if ($newWidth > 0) { // fit width
				$scale[] = $newWidth / $srcWidth;
			}

			if ($newHeight > 0) { // fit height
				$scale[] = $newHeight / $srcHeight;
			}

			if ($flags & self::FILL) {
				$scale = [max($scale)];
			}

			if ($flags & self::SHRINK_ONLY) {
				$scale[] = 1;
			}

			$scale = min($scale);
			$newWidth = (int) round($srcWidth * $scale);
			$newHeight = (int) round($srcHeight * $scale);
		}

		return [max($newWidth, 1), max($newHeight, 1)];
	}


	/**
	 * Crops image.
	 * @param  int|string  $left in pixels or percent
	 * @param  int|string  $top in pixels or percent
	 * @param  int|string  $width in pixels or percent
	 * @param  int|string  $height in pixels or percent
	 * @return static
	 */
	public function crop($left, $top, $width, $height)
	{
		[$r['x'], $r['y'], $r['width'], $r['height']]
			= static::calculateCutout($this->getWidth(), $this->getHeight(), $left, $top, $width, $height);
		$this->image = imagecrop($this->image, $r);
		imagesavealpha($this->image, true);
		return $this;
	}


	/**
	 * Calculates dimensions of cutout in image.
	 * @param  int|string  $left in pixels or percent
	 * @param  int|string  $top in pixels or percent
	 * @param  int|string  $newWidth in pixels or percent
	 * @param  int|string  $newHeight in pixels or percent
	 */
	public static function calculateCutout(int $srcWidth, int $srcHeight, $left, $top, $newWidth, $newHeight): array
	{
		if (is_string($newWidth) && substr($newWidth, -1) === '%') {
			$newWidth = (int) round($srcWidth / 100 * substr($newWidth, 0, -1));
		}
		if (is_string($newHeight) && substr($newHeight, -1) === '%') {
			$newHeight = (int) round($srcHeight / 100 * substr($newHeight, 0, -1));
		}
		if (is_string($left) && substr($left, -1) === '%') {
			$left = (int) round(($srcWidth - $newWidth) / 100 * substr($left, 0, -1));
		}
		if (is_string($top) && substr($top, -1) === '%') {
			$top = (int) round(($srcHeight - $newHeight) / 100 * substr($top, 0, -1));
		}
		if ($left < 0) {
			$newWidth += $left;
			$left = 0;
		}
		if ($top < 0) {
			$newHeight += $top;
			$top = 0;
		}
		$newWidth = min($newWidth, $srcWidth - $left);
		$newHeight = min($newHeight, $srcHeight - $top);
		return [$left, $top, $newWidth, $newHeight];
	}


	/**
	 * Sharpen image.
	 * @return static
	 */
	public function sharpen()
	{
		imageconvolution($this->image, [ // my magic numbers ;)
			[-1, -1, -1],
			[-1, 24, -1],
			[-1, -1, -1],
		], 16, 0);
		return $this;
	}


	/**
	 * Puts another image into this image.
	 * @param  int|string  $left in pixels or percent
	 * @param  int|string  $top in pixels or percent
	 * @param  int  $opacity 0..100
	 * @return static
	 */
	public function place(self $image, $left = 0, $top = 0, int $opacity = 100)
	{
		$opacity = max(0, min(100, $opacity));
		if ($opacity === 0) {
			return $this;
		}

		$width = $image->getWidth();
		$height = $image->getHeight();

		if (is_string($left) && substr($left, -1) === '%') {
			$left = (int) round(($this->getWidth() - $width) / 100 * substr($left, 0, -1));
		}

		if (is_string($top) && substr($top, -1) === '%') {
			$top = (int) round(($this->getHeight() - $height) / 100 * substr($top, 0, -1));
		}

		$output = $input = $image->image;
		if ($opacity < 100) {
			$tbl = [];
			for ($i = 0; $i < 128; $i++) {
				$tbl[$i] = round(127 - (127 - $i) * $opacity / 100);
			}

			$output = imagecreatetruecolor($width, $height);
			imagealphablending($output, false);
			if (!$image->isTrueColor()) {
				$input = $output;
				imagefilledrectangle($output, 0, 0, $width, $height, imagecolorallocatealpha($output, 0, 0, 0, 127));
				imagecopy($output, $image->image, 0, 0, 0, 0, $width, $height);
			}
			for ($x = 0; $x < $width; $x++) {
				for ($y = 0; $y < $height; $y++) {
					$c = \imagecolorat($input, $x, $y);
					$c = ($c & 0xFFFFFF) + ($tbl[$c >> 24] << 24);
					\imagesetpixel($output, $x, $y, $c);
				}
			}
			imagealphablending($output, true);
		}

		imagecopy(
			$this->image, $output,
			$left, $top, 0, 0, $width, $height
		);
		return $this;
	}


	/**
	 * Saves image to the file. Quality is 0..100 for JPEG and WEBP, 0..9 for PNG.
	 * @throws ImageException
	 */
	public function save(string $file, int $quality = null, int $type = null): void
	{
		if ($type === null) {
			$extensions = array_flip(self::FORMATS) + ['jpg' => self::JPEG];
			$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
			if (!isset($extensions[$ext])) {
				throw new Nette\InvalidArgumentException("Unsupported file extension '$ext'.");
			}
			$type = $extensions[$ext];
		}

		$this->output($type, $quality, $file);
	}


	/**
	 * Outputs image to string. Quality is 0..100 for JPEG and WEBP, 0..9 for PNG.
	 */
	public function toString(int $type = self::JPEG, int $quality = null): string
	{
		ob_start(function () {});
		$this->output($type, $quality);
		return ob_get_clean();
	}


	/**
	 * Outputs image to string.
	 */
	public function __toString(): string
	{
		try {
			return $this->toString();
		} catch (\Throwable $e) {
			if (func_num_args() || PHP_VERSION_ID >= 70400) {
				throw $e;
			}
			trigger_error('Exception in ' . __METHOD__ . "(): {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}", E_USER_ERROR);
			return '';
		}
	}


	/**
	 * Outputs image to browser. Quality is 0..100 for JPEG and WEBP, 0..9 for PNG.
	 * @throws ImageException
	 */
	public function send(int $type = self::JPEG, int $quality = null): void
	{
		if (!isset(self::FORMATS[$type])) {
			throw new Nette\InvalidArgumentException("Unsupported image type '$type'.");
		}
		header('Content-Type: ' . image_type_to_mime_type($type));
		$this->output($type, $quality);
	}


	/**
	 * Outputs image to browser or file.
	 * @throws ImageException
	 */
	private function output(int $type, ?int $quality, string $file = null): void
	{
		switch ($type) {
			case self::JPEG:
				$quality = $quality === null ? 85 : max(0, min(100, $quality));
				$success = imagejpeg($this->image, $file, $quality);
				break;

			case self::PNG:
				$quality = $quality === null ? 9 : max(0, min(9, $quality));
				$success = imagepng($this->image, $file, $quality);
				break;

			case self::GIF:
				$success = imagegif($this->image, $file);
				break;

			case self::WEBP:
				$quality = $quality === null ? 80 : max(0, min(100, $quality));
				$success = imagewebp($this->image, $file, $quality);
				break;

			default:
				throw new Nette\InvalidArgumentException("Unsupported image type '$type'.");
		}
		if (!$success) {
			throw new ImageException(error_get_last()['message'] ?: 'Unknown error');
		}
	}


	/**
	 * Call to undefined method.
	 * @return mixed
	 * @throws Nette\MemberAccessException
	 */
	public function __call(string $name, array $args)
	{
		$function = 'image' . $name;
		if (!function_exists($function)) {
			ObjectHelpers::strictCall(get_class($this), $name);
		}

		foreach ($args as $key => $value) {
			if ($value instanceof self) {
				$args[$key] = $value->getImageResource();

			} elseif (is_array($value) && isset($value['red'])) { // rgb
				$args[$key] = imagecolorallocatealpha(
					$this->image,
					$value['red'], $value['green'], $value['blue'], $value['alpha']
				) ?: imagecolorresolvealpha(
					$this->image,
					$value['red'], $value['green'], $value['blue'], $value['alpha']
				);
			}
		}
		$res = $function($this->image, ...$args);
		return is_resource($res) && get_resource_type($res) === 'gd' ? $this->setImageResource($res) : $res;
	}


	public function __clone()
	{
		ob_start(function () {});
		imagegd2($this->image);
		$this->setImageResource(imagecreatefromstring(ob_get_clean()));
	}


	/**
	 * Prevents serialization.
	 */
	public function __sleep(): array
	{
		throw new Nette\NotSupportedException('You cannot serialize or unserialize ' . self::class . ' instances.');
	}
}
