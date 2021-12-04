<?php declare(strict_types=1);

use Ghostff\TextToImage\Text;
use Ghostff\TextToImage\TextToImage;

require_once __DIR__ . '/src/TextToImage.php';
require_once __DIR__ . '/src/Text.php';

$text1 = Text::from('Text One')->color(231, 81, 0);

$text2 = Text::from('Text Two')->color(130, 146, 145)->position(260, 35);

$text3 = (new Text('Text Three'))->set(150, 0, [0, 0, 252], '', 10, 1, 1, [50, 205, 50]);

$text  = Text::from('Text!')
            ->position(170, 150)
            ->font(20, __DIR__ . '/sweet purple.otf')
            ->shadow(2, 2, [255])
            ->color(255,255, 0)
            ->rotate(20);

header("Content-Type: image/png");

echo (new TextToImage(__DIR__ . '/default.png'))->addTexts($text1, $text2, $text3, $text)->render();










