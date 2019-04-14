<?php
declare(strict_types=1);


require_once __DIR__ . '/src/TextToImage.php';



/* This will show your image in a browser.

header('Content-Type: image/jpg');
(new TextToImage('Hey FooBar'))
    ->setPosition(1, 1)
    ->setShadow()
    ->setFont(__DIR__ . '/GreatVibes-Regular.otf')
    ->setFontSize(40)
    ->setColor(255, 255, 255)
    ->setImage('generic.jpg')
    ->render();
 */

echo (new TextToImage('Hey FooBar'))
    ->setImage('generic.jpg')
    ->setPosition(1, 1)
    ->setShadow()
    ->setImage('generic.jpg')
    ->setSavePath(__DIR__ . '/Rendered')
    ->render('my-new-image-without-ext'); //Output: Rendered/my-new-image-without-ext.jpg