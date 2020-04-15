<?php
declare(strict_types=1);


require_once __DIR__ . '/src/TextToImage.php';

header("Content-Type: image/png");


$text1 = function (TextHandler $handler) {
    $handler->add('Text One')->color(255, 0, 0);
};

$text2 = function (TextHandler $handler) {
    $handler->add('Text Two')->color(0, 0, 0)->shadow(1, 1, [3, 204, 0])->position(120, 40);
};

$text3 = function (TextHandler $handler) {
    $handler->set('Text Three', 150, 0, [0, 0, 252], null, 10, 0, 0);
};

$text4 = function (TextHandler $handler) {
    $handler->add('Text Four')
            ->position(200, 300)
            ->font(25, __DIR__ . '/sweet purple.otf')
            ->color(0, 124, 0)
            ->shadow(1, 2, [0, 0, 0]);
};


# Write to an existing image
/*TextToImage::setImage(__DIR__ . '/default.png')->open(
    $text1,
    $text2,
    $text3,
    $text4
)->close(null);*/


# Write to a new image
TextToImage::createImage(500, 500, 'png', [222, 222, 222])->open(
    $text1,
    $text2,
    $text3,
    $text4
)->close();
