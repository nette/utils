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
			'r' => max(0, min(255, (int) $red)),
			'g' => max(0, min(255, (int) $green)),
			'b' => max(0, min(255, (int) $blue)),
			'a' => max(0, min(127, (int) $transparency)),
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
		switch ($info[2]) {
		case self::JPEG:
			return new self(imagecreatefromjpeg($file));

		case self::PNG:
			return new self(imagecreatefrompng($file));

		case self::GIF:
			return new self(imagecreatefromgif($file));

		default:
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
			$color = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
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
		if (!is_resource($image) || get_resource_type($image) !== 'gd') {
			throw new /*\*/InvalidArgumentException('Image is not valid.');
		}
		$this->image = $image;
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
		$width = imagesx($this->image);
		$height = imagesy($this->image);

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

		$oldImg = $this->image;
		$this->image = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($this->image, $oldImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
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
		$width = min((int) $width, imagesx($this->image) - $left);
		$height = min((int) $height, imagesy($this->image) - $top);

		$oldImg = $this->image;
		$this->image = imagecreatetruecolor($width, $height);
		imagecopy($this->image, $oldImg, 0, 0, $left, $top, $width, $height);
	}



	/**
	 * Sharpen image.
	 * @return void
	 */
	public function sharpen()
	{
		imageconvolution($this->image, array( // my magic numbers ;)
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
			$left = round((imagesx($this->image) - imagesx($image->image)) / 100 * $left);
		}

		if (substr($top, -1) === '%') {
			$top = round((imagesy($this->image) - imagesy($image->image)) / 100 * $top);
		}

		if ($opacity === 100) {
			imagecopy($this->image, $image->image, $left, $top, 0, 0, imagesx($image->image), imagesy($image->image));

		} elseif ($opacity <> 0) {
			imagecopymerge($this->image, $image->image, $left, $top, 0, 0, imagesx($image->image), imagesy($image->image), $opacity);
		}
	}



	/**
	 * Saves image to the file.
	 * @param  string  filename
	 * @param  int  quality 0..100 (for JPEG and PNG)
	 * @return void
	 */
	public function save($file = NULL, $quality = 85)
	{
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		switch ($ext) {
		case 'jpg':
		case 'jpeg':
			imagejpeg($this->image, $file, max(0, min(100, (int) $quality)));
			break;

		case 'png':
			imagepng($this->image, $file, max(0, min(9, round($quality / 10))));
			break;

		case 'gif':
			imagegif($this->image, $file);
			break;

		default:
			throw new /*\*/Exception("Unsupported image type.");
		}
	}



	/**
	 * Outputs image to string.
	 * @param  int  image type
	 * @param  int  quality 0..100 (for JPEG and PNG)
	 * @return void
	 */
	public function __toString($type = self::JPEG, $quality = 85)
	{
		switch ($type) {
		case self::JPEG:
			ob_start();
			imagejpeg($this->image, NULL, max(0, min(100, (int) $quality)));
			return ob_get_clean();

		case self::PNG:
			ob_start();
			imagepng($this->image, NULL, max(0, min(9, round($quality / 10))));
			return ob_get_clean();

		case self::GIF:
			ob_start();
			imagegif($this->image);
			return ob_get_clean();

		default:
			trigger_error("Unsupported image type.", E_USER_WARNING);
		}
	}



	/**
	 * Outputs image to browser.
	 * @param  int  image type
	 * @param  int  quality 0..100 (for JPEG and PNG)
	 * @return void
	 */
	public function send($type = self::JPEG, $quality = 85)
	{
		if ($type !== self::GIF && $type !== self::PNG && $type !== self::JPEG) {
			throw new /*\*/Exception("Unsupported image type.");
		}
		header('Content-Type: ' . image_type_to_mime_type($type));
		echo $this->__toString($type, $quality);
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
					$args[$key] = $value->image;

				} elseif (is_array($value) && isset($value['r'])) { // rgb
					$args[$key] = imagecolorallocatealpha($this->image, $value['r'], $value['g'], $value['b'], $value['a']);
				}
			}
			array_unshift($args, $this->image);

			return call_user_func_array($function, $args);
		}

		parent::__call($name, $args);
	}

}
