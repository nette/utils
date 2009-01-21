<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 * @version    $Id$
 */

/*namespace Nette;*/



require_once dirname(__FILE__) . '/Object.php';



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
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 */
class Image extends Object {

	/**#@+ resizing flags {@link resize()} */
	const ENLARGE = 1;
	const STRETCH = 2;
	/**#@-*/

	/**#@+ image types {@link send()} */
	const JPEG = IMAGETYPE_JPEG;
	const PNG = IMAGETYPE_PNG;
	const GIF = IMAGETYPE_GIF;
	/**#@-*/

	/** @var bool */
	public static $useImageMagick = FALSE;

	/** @var resource */
	private $image;



	/**
	 * Returns empty GIF.
	 * @return string
	 */
	public static function emptyGif()
	{
		return "GIF89a\x01\x00\x01\x00\x80\x00\x00\x00\x00\x00\x00\x00\x00!\xf9\x04\x01\x00\x00\x00\x00,\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02D\x01\x00;";
	}



	/**
	 * Returns RGB color.
	 * @param  int  red 0..255
	 * @param  int  green 0..255
	 * @param  int  blue 0..255
	 * @param  int  transparency 0..127
	 * @return array
	 */
	public static function rgb($red, $green, $blue, $transparency = 0)
	{
		return array(
			'red' => max(0, min(255, (int) $red)),
			'green' => max(0, min(255, (int) $green)),
			'blue' => max(0, min(255, (int) $blue)),
			'alpha' => max(0, min(127, (int) $transparency)),
		);
	}



	/**
	 * Opens image from file.
	 * @param  string
	 * @return Image
	 */
	public static function fromFile($file)
	{
		if (!extension_loaded('gd')) {
			throw new /*\*/Exception("PHP extension GD is not loaded.");
		}

		$info = getimagesize($file);
		if (self::$useImageMagick && (empty($info) || $info[0] * $info[1] > 2e6)) {
			return new ImageMagick($file);
		}

		switch ($info[2]) {
		case self::JPEG:
			return new self(imagecreatefromjpeg($file));

		case self::PNG:
			return new self(imagecreatefrompng($file));

		case self::GIF:
			return new self(imagecreatefromgif($file));

		default:
			if (self::$useImageMagick) {
				return new ImageMagick($file);
			}
			throw new /*\*/Exception("Unknown image type in file '$file'.");
		}
	}



	/**
	 * Creates blank image.
	 * @param  int
	 * @param  int
	 * @param  array
	 * @return Image
	 */
	public static function fromBlank($width, $height, $color = NULL)
	{
		if (!extension_loaded('gd')) {
			throw new /*\*/Exception("PHP extension GD is not loaded.");
		}

		$width = (int) $width;
		$height = (int) $height;
		if ($width < 1 || $height < 1) {
			throw new /*\*/InvalidArgumentException('Image width and height must be greater than zero.');
		}

		$image = imagecreatetruecolor($width, $height);
		if (is_array($color)) {
			$color = imagecolorallocate($image, $color['red'], $color['green'], $color['blue']);
			imagefilledrectangle($image, 0, 0, $width, $height, $color);
		}
		return new self($image);
	}



	/**
	 * Wraps GD image.
	 * @param  resource
	 */
	public function __construct($image)
	{
		$this->setImageResource($image);
	}



	/**
	 * Returns image width.
	 * @return int
	 */
	public function getWidth()
	{
		return imagesx($this->image);
	}



	/**
	 * Returns image height.
	 * @return int
	 */
	public function getHeight()
	{
		return imagesy($this->image);
	}



	/**
	 * Sets image resource.
	 * @param  resource
	 * @return void
	 */
	protected function setImageResource($image)
	{
		if (!is_resource($image) || get_resource_type($image) !== 'gd') {
			throw new /*\*/InvalidArgumentException('Image is not valid.');
		}
		$this->image = $image;
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
	 * @param  mixed  width in pixels or percent
	 * @param  mixed  height in pixels or percent
	 * @param  int  flags
	 * @return void
	 */
	public function resize($newWidth, $newHeight, $flags = 0)
	{
		list($newWidth, $newHeight) = $this->calculateSize($newWidth, $newHeight, $flags);
		$newImage = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($newImage, $this->getImageResource(), 0, 0, 0, 0, $newWidth, $newHeight, $this->getWidth(), $this->getHeight());
		$this->image = $newImage;
	}



	/**
	 * Calculates dimensions of resized image.
	 * @param  mixed  width in pixels or percent
	 * @param  mixed  height in pixels or percent
	 * @param  int  flags
	 * @return array
	 */
	public function calculateSize($newWidth, $newHeight, $flags = 0)
	{
		$width = $this->getWidth();
		$height = $this->getHeight();

		if (substr($newWidth, -1) === '%') {
			$newWidth = round($width / 100 * $newWidth);
			$flags |= self::ENLARGE;
			$percents = TRUE;
		} else {
			$newWidth = (int) $newWidth;
		}

		if (substr($newHeight, -1) === '%') {
			$newHeight = round($height / 100 * $newHeight);
			$flags |= $percents ? self::STRETCH : self::ENLARGE;
		} else {
			$newHeight = (int) $newHeight;
		}

		if ($flags & self::STRETCH) { // non-proportional
			if ($newWidth < 1 || $newHeight < 1) {
				throw new /*\*/InvalidArgumentException('For stretching must be both width and height specified.');
			}

			if (($flags & self::ENLARGE) === 0) {
				$newWidth = round($width * min(1, $newWidth / $width));
				$newHeight = round($height * min(1, $newHeight / $height));
			}

		} else {  // proportional
			if ($newWidth < 1 && $newHeight < 1) {
				throw new /*\*/InvalidArgumentException('At least width or height must be specified.');
			}

			$scale = array();
			if ($newWidth > 0) { // fit width
				$scale[] = $newWidth / $width;
			}

			if ($newHeight > 0) { // fit height
				$scale[] = $newHeight / $height;
			}

			if (($flags & self::ENLARGE) === 0) {
				$scale[] = 1;
			}

			$scale = min($scale);
			$newWidth = round($width * $scale);
			$newHeight = round($height * $scale);
		}

		return array($newWidth, $newHeight);
	}



	/**
	 * Crops image.
	 * @param  int  x-coordinate
	 * @param  int  y-coordinate
	 * @param  int  width
	 * @param  int  height
	 * @return void
	 */
	public function crop($left, $top, $width, $height)
	{
		$left = max(0, (int) $left);
		$top = max(0, (int) $top);
		$width = min((int) $width, $this->getWidth() - $left);
		$height = min((int) $height, $this->getHeight() - $top);

		$newImage = imagecreatetruecolor($width, $height);
		imagecopy($newImage, $this->getImageResource(), 0, 0, $left, $top, $width, $height);
		$this->image = $newImage;
	}



	/**
	 * Sharpen image.
	 * @return void
	 */
	public function sharpen()
	{
		imageconvolution($this->getImageResource(), array( // my magic numbers ;)
			array( -1, -1, -1 ),
			array( -1, 24, -1 ),
			array( -1, -1, -1 ),
		), 16, 0);
	}



	/**
	 * Puts another image into this image.
	 * @param  Image
	 * @param  mixed  x-coordinate in pixels or percent
	 * @param  mixed  y-coordinate in pixels or percent
	 * @param  int  opacity 0..100
	 * @return void
	 */
	public function place(Image $image, $left = 0, $top = 0, $opacity = 100)
	{
		$opacity = max(0, min(100, (int) $opacity));

		if (substr($left, -1) === '%') {
			$left = round(($this->getWidth() - $image->getWidth()) / 100 * $left);
		}

		if (substr($top, -1) === '%') {
			$top = round(($this->getHeight() - $image->getHeight()) / 100 * $top);
		}

		if ($opacity === 100) {
			imagecopy($this->getImageResource(), $image->getImageResource(), $left, $top, 0, 0, $image->getWidth(), $image->getHeight());

		} elseif ($opacity <> 0) {
			imagecopymerge($this->getImageResource(), $image->getImageResource(), $left, $top, 0, 0, $image->getWidth(), $image->getHeight(), $opacity);
		}
	}



	/**
	 * Saves image to the file.
	 * @param  string  filename
	 * @param  int  quality 0..100 (for JPEG and PNG)
	 * @param  int  optional image type
	 * @return void
	 */
	public function save($file = NULL, $quality = NULL, $type = NULL)
	{
		if ($type === NULL) {
			switch (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
			case 'jpg':
			case 'jpeg':
				$type = self::JPEG;
				break;
			case 'png':
				$type = self::PNG;
				break;
			case 'gif':
				$type = self::GIF;
			}
		}

		switch ($type) {
		case self::JPEG:
			$quality = $quality === NULL ? 85 : max(0, min(100, (int) $quality));
			imagejpeg($this->getImageResource(), $file, $quality);
			break;

		case self::PNG:
			$quality = $quality === NULL ? 9 : max(0, min(9, (int) $quality));
			imagepng($this->getImageResource(), $file, $quality);
			break;

		case self::GIF:
			imagegif($this->getImageResource(), $file);
			break;

		default:
			throw new /*\*/Exception("Unsupported image type.");
		}
	}



	/**
	 * Outputs image to string.
	 * @param  int  image type
	 * @param  int  quality 0..100 (for JPEG and PNG)
	 * @return string
	 */
	public function toString($type = self::JPEG, $quality = NULL)
	{
		ob_start();
		$this->save(NULL, $quality, $type);
		return ob_get_clean();
	}



	/**
	 * Outputs image to string.
	 * @return string
	 */
	public function __toString()
	{
		try {
			return $this->toString();

		} catch (/*\*/Exception $e) {
			trigger_error($e->getMessage(), E_USER_WARNING);
			return '';
		}
	}



	/**
	 * Outputs image to browser.
	 * @param  int  image type
	 * @param  int  quality 0..100 (for JPEG and PNG)
	 * @return void
	 */
	public function send($type = self::JPEG, $quality = NULL)
	{
		if ($type !== self::GIF && $type !== self::PNG && $type !== self::JPEG) {
			throw new /*\*/Exception("Unsupported image type.");
		}
		header('Content-Type: ' . image_type_to_mime_type($type));
		$this->save(NULL, $quality, $type);
	}



	/**
	 * Call to undefined method.
	 *
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws \MemberAccessException
	 */
	public function __call($name, $args)
	{
		$function = 'image' . $name;
		if (function_exists($function)) {
			foreach ($args as $key => $value) {
				if ($value instanceof self) {
					$args[$key] = $value->getImageResource();

				} elseif (is_array($value) && isset($value['red'])) { // rgb
					$args[$key] = imagecolorallocatealpha($this->getImageResource(), $value['red'], $value['green'], $value['blue'], $value['alpha']);
				}
			}
			array_unshift($args, $this->getImageResource());

			return call_user_func_array($function, $args);
		}

		parent::__call($name, $args);
	}

}
