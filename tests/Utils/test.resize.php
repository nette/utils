<h1>Nette\Image resize test</h1>

<pre>
<?php
require_once '../../Nette/loader.php';

/*use Nette\Debug;*/
/*use Nette\Image;*/


$main = Image::fromFile('logo.gif');
$image = clone $main;
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

echo "cropping...\n";
$image->crop(10, 20, 50, 300);
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);


$image = clone $main;
echo "resizing X\n";
$image->resize(150, NULL);
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

$image = clone $main;
echo "resizing Y\n";
$image->resize(NULL, 150);
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

$image = clone $main;
echo "resizing X Y\n";
$image->resize(300, 150);
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

$image = clone $main;
echo "resizing X Y enlarge\n";
$image->resize(300, 150, Image::ENLARGE);
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

$image = clone $main;
echo "resizing X Y enlarge stretch\n";
$image->resize(300, 100, Image::ENLARGE | Image::STRETCH);
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

$image = clone $main;
echo "resizing X Y stretch\n";
$image->resize(300, 100, Image::STRETCH);
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

$image = clone $main;
echo "resizing X%\n";
$image->resize('110%', NULL);
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

$image = clone $main;
echo "resizing X% Y%\n";
$image->resize('110%', '90%');
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);
