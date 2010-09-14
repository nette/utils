<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette;

use Nette;



/**
 * Manipulation with large images using ImageMagick.
 *
 * <code>
 * $image = Image::fromFile('bigphoto.jpg');
 * $image->resize(150, 100);
 * $image->sharpen();
 * $image->send();
 * </code>
 *
 * @author     David Grudl
 */
class ImageMagick extends Image
{
	/** @var string  path to ImageMagick library */
	public static $path = '';

	/** @var string */
	public static $tempDir;

	/** @var string */
	private $file;

	/** @var bool */
	private $isTemporary = FALSE;

	/** @var int */
	private $width;

	/** @var int */
	private $height;



	/**
	 * Wraps image file.
	 * @param  string  detected image format
	 * @param  string
	 */
	public function __construct($file, & $format = NULL)
	{
		if (!is_file($file)) {
			throw new \InvalidArgumentException("File '$file' not found.");
		}
		$format = $this->setFile(realpath($file));
		if ($format === 'JPEG') $format = self::JPEG;
		elseif ($format === 'PNG') $format = self::PNG;
		elseif ($format === 'GIF') $format = self::GIF;
	}



	/**
	 * Returns image width.
	 * @return int
	 */
	public function getWidth()
	{
		return $this->file === NULL ? parent::getWidth() : $this->width;
	}



	/**
	 * Returns image height.
	 * @return int
	 */
	public function getHeight()
	{
		return $this->file === NULL ? parent::getHeight() : $this->height;
	}



	/**
	 * Returns image GD resource.
	 * @return resource
	 */
	public function getImageResource()
	{
		if ($this->file !== NULL) {
			if (!$this->isTemporary) {
				$this->execute("convert -strip %input %output", self::PNG);
			}
			$this->setImageResource(imagecreatefrompng($this->file));
			if ($this->isTemporary) {
				unlink($this->file);
			}
			$this->file = NULL;
		}

		return parent::getImageResource();
	}



	/**
	 * Resizes image.
	 * @param  mixed  width in pixels or percent
	 * @param  mixed  height in pixels or percent
	 * @param  int    flags
	 * @return ImageMagick  provides a fluent interface
	 */
	public function resize($width, $height, $flags = self::FIT)
	{
		if ($this->file === NULL) {
			return parent::resize($width, $height, $flags);
		}

		$mirror = '';
		if ($width < 0) $mirror .= ' -flop';
		if ($height < 0) $mirror .= ' -flip';
		list($newWidth, $newHeight) = self::calculateSize($this->getWidth(), $this->getHeight(), $width, $height, $flags);
		$this->execute("convert -resize {$newWidth}x{$newHeight}! {$mirror} -strip %input %output", self::PNG);
		return $this;
	}



	/**
	 * Crops image.
	 * @param  int  x-coordinate
	 * @param  int  y-coordinate
	 * @param  int  width
	 * @param  int  height
	 * @return ImageMagick  provides a fluent interface
	 */
	public function crop($left, $top, $width, $height)
	{
		if ($this->file === NULL) {
			return parent::crop($left, $top, $width, $height);
		}

		$left = max(0, (int) $left);
		$top = max(0, (int) $top);
		$width = min((int) $width, $this->getWidth() - $left);
		$height = min((int) $height, $this->getHeight() - $top);
		$this->execute("convert -crop {$width}x{$height}+{$left}+{$top} -strip %input %output", self::PNG);
		return $this;
	}



	/**
	 * Saves image to the file.
	 * @param  string  filename
	 * @param  int  quality 0..100 (for JPEG and PNG)
	 * @param  int  optional image type
	 * @return bool TRUE on success or FALSE on failure.
	 */
	public function save($file = NULL, $quality = NULL, $type = NULL)
	{
		if ($this->file === NULL) {
			return parent::save($file, $quality, $type);
		}

		$quality = $quality === NULL ? '' : '-quality ' . max(0, min(100, (int) $quality));
		if ($file === NULL) {
			$this->execute("convert $quality -strip %input %output", $type === NULL ? self::PNG : $type);
			readfile($this->file);

		} else {
			$this->execute("convert $quality -strip %input %output", (string) $file);
		}
		return TRUE;
	}



	/**
	 * Change and identify image file.
	 * @param  string  filename
	 * @return string  detected image format
	 */
	private function setFile($file)
	{
		$this->file = $file;
		$res = $this->execute('identify -format "%w,%h,%m" ' . escapeshellarg($this->file));
		if (!$res) {
			throw new \Exception("Unknown image type in file '$file' or ImageMagick not available.");
		}
		list($this->width, $this->height, $format) = explode(',', $res, 3);
		return $format;
	}



	/**
	 * Executes command.
	 * @param  string  command
	 * @param  string|bool  process output?
	 * @return string
	 */
	private function execute($command, $output = NULL)
	{
		$command = str_replace('%input', escapeshellarg($this->file), $command);
		if ($output) {
			$newFile = is_string($output)
				? $output
				: (self::$tempDir ? self::$tempDir : dirname($this->file)) . '/' . uniqid('_tempimage', TRUE) . image_type_to_extension($output);
			$command = str_replace('%output', escapeshellarg($newFile), $command);
		}

		$lines = array();
		exec(self::$path . $command, $lines, $status); // $status: 0 - ok, 1 - error, 127 - command not found?

		if ($output) {
			if ($status != 0) {
				throw new \Exception("Unknown error while calling ImageMagick.");
			}
			if ($this->isTemporary) {
				unlink($this->file);
			}
			$this->setFile($newFile);
			$this->isTemporary = !is_string($output);
		}

		return $lines ? $lines[0] : FALSE;
	}



	/**
	 * Delete temporary files.
	 * @return void
	 */
	public function __destruct()
	{
		if ($this->file !== NULL && $this->isTemporary) {
			unlink($this->file);
		}
	}

}