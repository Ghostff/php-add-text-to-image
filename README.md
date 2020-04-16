# PHP-Add-text-to-image  
Adds text to an image. PHP >= 7.0

### Usage (Existing Image)
Outputting modified image to the browser.
```php
header("Content-Type: image/png");

TextToImage::setImage(__DIR__ . '/default.png')->open(function (TextToImage $handler)
{
    $handler->add('Text One')->color(255, 0, 0);
})->close();
```
Saving modified image as a new file.
```php
TextToImage::setImage(__DIR__ . '/default.png')->open(function (TextToImage $handler)
{
  $handler->add('Text One')->color(255, 0, 0);
})->close(__DIR__ . '/new_name_without_extension');
```

### Usage (Non Existing Image)
Outputting modified image to the browser.
```php
header("Content-Type: image/png");

TextToImage::createImage(500, 500, 'png')->open(function (TextToImage $handler)
{
    $handler->add('Text One')->color(255, 0, 0);
})->close();
```
Saving modified image as a new file.
```php
TextToImage::createImage(500, 500, 'png')->open(function (TextToImage $handler)
{
    $handler->add('Text One')->color(255, 0, 0);
})->close(__DIR__ . '/new_name_without_extension');
```

### Writing multiple contents
```php
header("Content-Type: image/png");

$text1 = function (TextToImage $handler) {
    $handler->add('Text One')->color(255, 0, 0);
};

$text2 = function (TextToImage $handler) {
    $handler->add('Text Two')->color(0, 0, 0)->shadow(1, 1, [3, 204, 0])->position(120, 40);
};

$text3 = function (TextToImage $handler) {
    $handler->set('Text Three', 150, 0, [0, 0, 252], null, 10, 0, 0);
};

$text4 = function (TextToImage $handler) {
    $handler->add('Imani And Her Dragon')
            ->position(200, 300)
            ->font(25, __DIR__ . '/sweet purple.otf')
            ->color(0, 124, 0)
            ->shadow(1, 2, [0, 0, 0]);
};


TextToImage::createImage(500, 500, 'png')->open($text1, $text2, $text3, $text4)->close();
```
---

### Documentations
Modifies an existing image.  
`setImage(string $image_path)`

| Params        |Description                            |
|---------------|---------------------------------------|
|image_path     |The path to image to write text onto   |

---
Create an image for modification.   
`createImage(int $width, int $height, string $ext = 'png', array $bg_color = [255, 255, 255])`
  
| Params        |Description                                  |
|---------------|---------------------------------------------|
|width          |The width of the image.                      |
|height	        |The height of the image.                     |
|ext	        |The image format e.g png, jpeg or gif        |
|bg_color       |An array [r, g, b] of image background color |
---

Sets image modification data.  
`open(Closure ...$closures)`

| Params        |Description                     |
|---------------|--------------------------------|
| closures      |A sets of image modifications. Each Closure must accept an argument of `TextToImage`   |
---

Evaluates all specified image modification.  
`close(string $save_path = null)`

| Params        |Description                     |
|---------------|--------------------------------|
| save_path     |The path/name without extension to save modified image. Ignore this if outputting to browser. |

---

#### Method that can be used within each open Closure
```php
TextToImage::createImage(...)->open(function (TextToImage $h) {
    $h->add(...)
      ->position(...)
      ->font(...)
      ->color(...)
      ->shadow(...);
}, ...)->close();
```

`add(string $text): TextToImage`  
**Description:** Sets the text that will be written on image.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|text           |The text to add to image.          |


`postion(int $position_x : 0, int $position_y : 0): TextToImage`   
**Description:** Sets the position of specified text on image.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|x              |The X position.                    |
|y	            |The Y position.                    |
---

`font(int $size, string $path = null): TextToImage`   
**Description:** Sets the font/size of specified text.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|size           |The font size of text.             |
|font_path      |The path to font file.             |
---


`color(int $r : 255, int $g : 255, int $b : 255): TextToImage`   
**Description:** Sets the color of specified text.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|r              |Red.                               |
|g	            |Green.                             |
|b	            |Blue.                              |
---

`shadow(int $position_x, int $position_y, array $color : [0, 0, 0]): TextToImage`   
**Description:** Adds shadow to specified text.  
  
| Params        |Description                        |
|---------------|-----------------------------------|
|position_x     |The shadow's X position.           |
|position_y	    |The shadow's Y position.           |
|color	        |The shadow's color.                |
---
