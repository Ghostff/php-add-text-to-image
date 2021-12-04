# PHP-add-text-to-image  
Add text to an existing or new image. PHP >= 7.0

```shell
composer require ghostff/php-text-to-image
```

### Usage
```php
$text1 = Text::from('Text One')->color(231, 81, 0);

$text2 = Text::from('Text Two')->color(130, 146, 145)->position(260, 35);

$text3 = (new Text('Text Three'))->set(150, 0, [0, 0, 252], '', 10, 1, 1, [50, 205, 50]);

$text  = Text::from('Text!')
            ->position(170, 150)
            ->font(20, __DIR__ . '/sweet purple.otf')
            ->shadow(2, 2, [255])
            ->color(255,255, 0)
            ->rotate(20);

```
Writing to existing image
```php
{
    header("Content-Type: image/png");
    echo (new TextToImage(__DIR__ . '/default.png'))->addTexts($text1, $text2, $text3, $text)->render();
}
    
// Or save to a file
(new TextToImage(__DIR__ . '/default.png'))->addTexts($text1, $text2, $text3, $text)->render(__DIR__ . '/tmp.png')
```
Writing to a new image:
```php
{
    header("Content-Type: image/png");
    echo (new TextToImage())->setDimension(350, 350)->setBackgroundColor(0, 0, 0)->addTexts($text1, $text2, $text3, $text)->render();
}
    
// Or save to a file
(new TextToImage())->setDimension(350, 350)->setBackgroundColor(0, 0, 0)->addTexts($text1, $text2, $text3, $text)->render(__DIR__ . '/tmp.png');
```


----

### `TextToImage` Documentations
`TextToImage::__construct(string $from = '')`   
**Description:** Creates TextToImage instance.  

| Params        |Description                            |
|---------------|---------------------------------------|
|from     |The image text will be added to. If not specified a blank image 200x200 will be created   |

---
`TextToImage::setDimension(int $width, int $height): TextToImage`   
**Description:** Set background image dimension. **Note: This is not evaluated if `$from` argument is passed to the constructor**   
  
| Params        |Description                                  |
|---------------|---------------------------------------------|
|width          |The width of the background image.                      |
|height	        |The height of the background image.                     |
---

`TextToImage::setBackgroundColor(int $r = 255, int $g = 255, int $b = 255, int $a = 255): TextToImage`   
**Description:** Set the background color of created background image. **Note: This is not evaluated if `$from` argument is passed to the constructor.**   

| Params        |Description                     |
|---------------|--------------------------------|
| r      |Value of red component.   |
| g      |Value of green component.  |
| b      |Value of blue component.   |
| a      |A value between 0 and 255. 255 indicates completely opaque while 0 indicates completely transparent.   |
---

`TextToImage::addTexts(Text $text, Text ...$texts): TextToImage`   
**Description:** Adds text to specified or generated background image.  

| Params        |Description                     |
|---------------|--------------------------------|
| text     |`Text` to add to image |
| texts     |Further `Text` to add to image |

Multiple text can all be added at once or over steps.
```php
$hello  = Text::from('Hello');
$world  = Text::from('World')->position(0, 15);
$foo    = Text::from('Foo')->position(0, 30);
$bar    = Text::from('Bar')->position(0, 45);
$foobar = Text::from('Foobar')->position(0, 60)->color(255, 0, 0);


$text_image = new TextToImage();
$text_image->addTexts($hello, $world, $foo);
$text_image->addTexts($bar);
echo $text_image->setBackgroundColor(0, 0, 0)->addTexts($foobar)->render();
```

---

`TextToImage::render(string $save_as = null, string $ext = null): string`   
**Description:** Renders modified image to a file or return contents.   

| Params        |Description                     |
|---------------|--------------------------------|
| save_as      |If specified, image content will be saved at the provided path..   |
| ext      |Image processor. possible values: `jpg`, `jpeg`, `png` or `gif`.  |
---


### `Text` Documentations
`Text::__construct(string $from = '')` or `Text::__from(string $text): Text`   
**Description:** Creates Text instance.  

| Params        |Description                            |
|---------------|---------------------------------------|
|text     |The text that will be written on image.   |


`Text::position(int $x, int $y = 0): Text`    
**Description:** Sets the position of specified text on image. 
  
| Params        |Description                        |
|---------------|-----------------------------------|
|x              |The X position.                    |
|y	            |The Y position.                    |
---

`Text::font(int $size, string $path = null): Text`   
**Description:** Sets the font/size of specified text.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|size           |The font size of text.             |
|path      |The path to font file.             |
---


`Text::color(int $r = 255, int $g = 255, int $b = 255, int $a = 255): Text`   
**Description:** Sets the color of specified text.
  
| Params        |Description                        |
|---------------|-----------------------------------|
| r      |Value of red component.   |
| g      |Value of green component.  |
| b      |Value of blue component.   |
| a      |A value between 0 and 255. 255 indicates completely opaque while 0 indicates completely transparent.   |
---

`Text::shadow(int $position_x = null, int $position_y = null, array $color = []): Text`   
**Description:** Adds shadow to specified text.  
  
| Params        |Description                        |
|---------------|-----------------------------------|
|position_x     |The shadow's X position.           |
|position_y	    |The shadow's Y position.           |
|color	        | Array [r(red), g(green), b(blue), a(alpha)] See color method.                |
```php
Text::from('FooBar')->shadow(1, 1, [0, 0, 255]);
// Create Text with half the max opacity
Text::from('FooBar')->shadow(1, 1, [0, 0, 255, 255 / 2]);
```
---


`Text::rotate(float $degrees): Text`   
**Description:** Rotates specified text to a specific angle.

| Params        |Description                        |
|---------------|-----------------------------------|
| degrees      |The angle in degrees.   |
---


`Text::update(Closure $closure): Text`   
**Description:** Runtime text update.

| Params        |Description                        |
|---------------|-----------------------------------|
| degrees      |The function to call before render. If specified, this closure will be called with three arguments `TextToImage`, `Text` and `GdFont`   |
```php
$text = Text::from('FooBar')->shadow(1, 1, [0, 0, 255]);
$text->update(function (TextToImage $text_to_image, Text $text, $image) {
    // Basic centering of text.
    $text->position(intval($text_to_image->getHeight() / 2), intval($text_to_image->getWidth() / 2));
});
```
---


