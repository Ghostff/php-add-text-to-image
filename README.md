# PHP-Add-text-to-image  
Adds text to an image. PHP >= 7.0  
  
### Construct
`__construct($text, $position_x, $position_y, $color, $image, $font, $font_size, $save_path)`
  
| Params        |Description                        | Type      |
|---------------|-----------------------------------|-----------
|text           |Text to add to image               | string    |
|position_x	    |X position of text in image        | int       |
|position_y	    |Y position of text in image        | int       |
|color          |Text color                         | array     |
|image          |The image to write text on         | string    |
|font           |Text Font (file path)              | string    |
|font_size      |Font size                          | int       |
|save_path      |Save path. If image will be saved  | string    |

  
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
### methods
`setPosition(int $position_x : 0, int $position_y : 0): TextToImage`   
**Description:** Sets the position of text on image.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|position_x     |The X position.               |
|position_y	    |The Y position.        |

---

`setFont(string $font_path): TextToImage`   
**Description:** Sets text font file path.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|font_path     |The name of the font.               |


---

`setFontSize(int $size : 5): TextToImage`   
**Description:** Sets text font size.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|size     |The font size of text.               |

---

`setColor(int $r : 255, int $g : 255, int $b : 255): TextToImage`   
**Description:** Sets text color.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|r     |Red.               |
|g	    |Green.        |
|b	    |Blue.        |

---

`setImage(string $image_path): TextToImage`  
**Description:** Sets the image text will be written on.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|image_path     |The name of the image.               |

---

`setShadow(int $position_x, int $position_y, array $color : [0, 0, 0]): TextToImage`   
**Description:** Sets image shadow, color and position. Calling this method without passing args will enable default shadow.  
**Example:** `$tti->setShadow();`, `$tti->setShadow(null, null, [255, 0, 0]);`
  
| Params        |Description                        |
|---------------|-----------------------------------|
|position_x     |The shadow's X position.               |
|position_y	    |The shadow's Y position.        |
|color	    |The shadow's color.        |

---

`render(string $save_as ): ?string`   
**Description:** Renders Text upon image. If `$save_as` is specified, image will be saved as specified file.
  
| Params        |Description                        |
|---------------|-----------------------------------|
|save_as     |The file to write rendered image to.               |
