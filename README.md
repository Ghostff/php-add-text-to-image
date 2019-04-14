# PHP-Text-to-image  
Adds text to an image. PHP >= 7.0  
  
### Construct
``__construct($text, $position_x, $position_y, $color, $image, $font, $font_size, $save_path)``
  
| Params        |Description                        |
|---------------|-----------------------------------|
|text		    |Text to add to image               |
|position_x	    |X position of text in image        |
|position_y	    |Y position of text in image        |
|color		    |Text color                         |
|font		    |Text Font (file path)              |
|font_size	    |Font size                          |
|save_path	    |Save path. If image will be saved  |

  
## Display image on browser  
```php  
header('Content-Type: image/jpg');  
  
(new TextToImage('Hey FooBar'))  
 ->setPosition(1, 1)  
 ->setShadow(1, 1, [255, 0, 0])  
 ->setFont(__DIR__ . '/GreatVibes-Regular.otf')  
 ->setFontSize(40)  
 ->setColor(255, 255, 255)  
 ->setImage('generic.jpg')  
 ->render();  
```  
  
## Save image to file.  
```php  
echo (new TextToImage('Hey FooBar'))  
 ->setImage('generic.jpg')  
 ->setPosition(1, 1)  
 ->setShadow(1, 1, [255, 0, 0])  
 ->setFont(__DIR__ . '/GreatVibes-Regular.otf')  
 ->setFontSize(40)  
 ->setColor(255, 255, 255)  
 ->setImage('generic.jpg')  
 ->setSavePath(__DIR__ . '/Rendered')  
 ->render('my-new-image'); // Output: Rendered/my-new-image.jpg  
```