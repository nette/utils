<h1>Nette\Image construct test</h1>

<pre>
<?php
require_once '../../Nette/loader.php';

/*use Nette\Debug;*/
/*use Nette\Image;*/


$image = Image::fromFile('logo.gif');
echo "logo.gif\n";
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);

$image = Image::fromBlank(200, 300, Image::rgb(255, 128, 0));
echo "blank\n";
echo "width: "; Debug::dump($image->width);
echo "height: "; Debug::dump($image->height);
