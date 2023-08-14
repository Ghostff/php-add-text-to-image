<?php declare(strict_types=1);

namespace Ghostff\TextToImage;

use RuntimeException;

class TextToImage
{
    private $from = '';
    private $texts = [];
    private $width = null;
    private $height = null;
    private $bg_color = [255, 255, 255, 127];
    /**
     * @var BackgroundLayer[]
     */
    private $bg_layers = [];

    public function getHeight(): int
    {
        $this->loadDimensions();
        return $this->height;
    }

    public function getWidth(): int
    {
        $this->loadDimensions();
        return $this->width;
    }

    protected function loadDimensions()
    {
        if ($this->from == '') {
            if (!isset($this->height) || !isset($this->width)) {
                $this->width = 200;
                $this->height = 200;
            }
            return;
        }
        if (isset($this->height) && isset($this->width)) {
            return;
        }
        $this->height = $this->width = 0;
        if (!is_readable($this->from)) {
            throw new \RuntimeException('Image to write text to not specified or does not exist.');
        }

        $ext = $this->getExtension($this->from);
        if ($ext == 'jpg' || $ext == 'jpeg') {
            $image = imagecreatefromjpeg($this->from);
        } elseif ($ext == 'png') {
            $image = imagecreatefrompng($this->from);
        } elseif ($ext == 'gif') {
            $image = imagecreatefromgif($this->from);
        } else {
            throw new \RuntimeException("{$ext} not supported, implement it yourself.");
        }
        if ($this->height === 0) {
            $this->height = imagesy($image);
            $this->width = imagesx($image);
        }
        imagedestroy($image);
    }

    /**
     * Constructor.
     *
     * @param string $from The image text will be added to. If not specified a blank image 200x200 will be created.
     */
    public function __construct(string $from = '')
    {
        $this->from = $from;
    }

    /**
     * Set background image dimension.
     *  Note: This is not evaluated if a $path is specified in a constructor.
     *
     * @param int $width  the image width
     * @param int $height the image height
     *
     * @return $this
     */
    public function setDimension(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * Set the background color of created background image.
     *  Note: This is not evaluated if a path is specified in a constructor.
     *
     * @param int $r value of red component
     * @param int $g value of green component
     * @param int $b value of blue component
     * @param int $a A value between 0 and 255. 255 indicates completely opaque while 0 indicates completely transparent.
     *
     * @return $this
     */
    public function setBackgroundColor(int $r = 255, int $g = 255, int $b = 255, int $a = 255): self
    {
        $this->bg_color = [$r, $g, $b, 127 - ($a >> 1)];

        return $this;
    }

    /**
     * $transparent = imagecolorallocatealpha($image, 0,0,0, 80);.
    imagefilledrectangle($image, 0, 0, $imageX, 60, $transparent);
     * @param int $position_x
     * @param int $position_y
     *
     * @return $this
     */
    public function addBackgroundLayer(BackgroundLayer $bg_layer): self
    {
        $this->bg_layers[] = $bg_layer;

        return $this;
    }

    /**
     * Adds texts to specified or background image.
     *
     * @return $this
     */
    public function addTexts(Text ...$text): self
    {
        $this->texts = array_merge($this->texts, $text);

        return $this;
    }

    /**
     * Renders modified image to a file or return contents.
     *
     * @param string|null $save_as if specified, image content will be saved at the provided path
     * @param string|null $ext     Image processor. possible values: jpg|jpeg|png|gif
     */
    public function render(string $save_as = null, string $ext = null): string
    {
        if ($this->from == '') {
            $this->loadDimensions();
            $image = @imagecreate($this->height, $this->width);
            imagecolorallocatealpha($image, ...$this->bg_color);
            $ext = $ext ?: $this->getExtension($save_as ?? '.png');
        } else {
            $this->height = $this->width = 0;
            if (!is_readable($this->from)) {
                throw new \RuntimeException('Image to write text to not specified or does not exist.');
            }

            $ext = $this->getExtension($this->from);
            if ($ext == 'jpg' || $ext == 'jpeg') {
                $image = imagecreatefromjpeg($this->from);
            } elseif ($ext == 'png') {
                $image = imagecreatefrompng($this->from);
            } elseif ($ext == 'gif') {
                $image = imagecreatefromgif($this->from);
            } else {
                throw new \RuntimeException("{$ext} not supported, implement it yourself.");
            }
        }

        foreach ($this->bg_layers as $bg_layer) {
            $bg_layer_color = imagecolorallocatealpha($image, ...$bg_layer->getColor());
            $bg_layer_position = $bg_layer->getPosition();
            imagefilledrectangle(
                $image,
                $bg_layer_position[0],
                $bg_layer_position[1],
                (isset($bg_layer_position[2])) ? $bg_layer_position[2] : imagesx($image),
                (isset($bg_layer_position[3])) ? $bg_layer_position[3] : imagesy($image),
                $bg_layer_color
            );
        }

        /** @var Text $text */
        foreach ($this->texts as $text) {
            if (($font = $text->getFont()) !== '' && !is_readable($font)) {
                throw new \RuntimeException("Font \"{$font}\" not found.");
            }

            if ($this->height === 0) {
                $this->height = imagesy($image);
                $this->width = imagesx($image);
            }

            $closure = $text->getCallback();
            $closure && $closure($this, $text, $image);

            [$position_x, $position_y] = $text->getPosition();
            if (!empty($shadow_color = $text->getShadowColor())) {
                [$x, $y] = $text->getShadow();
                $this->write($image, $text, $x + $position_x, $y + $position_y, $shadow_color);
            }

            $this->write($image, $text, $position_x, $position_y, $text->getColor());
        }

        imagesavealpha($image, true);

        $save_as = $save_as ?? fopen('php://memory', 'r+');
        if ($ext == 'jpg' || $ext == 'jpeg') {
            imagejpeg($image, $save_as);
        } elseif ($ext == 'png') {
            imagepng($image, $save_as);
        } elseif ($ext == 'gif') {
            imagegif($image, $save_as);
        }

        imagedestroy($image);
        if (is_resource($save_as)) {
            rewind($save_as);

            return stream_get_contents($save_as);
        }

        return '';
    }

    /**
     * Gets files extension.
     */
    private function getExtension(string $filename): string
    {
        return (($position = strrpos($filename, '.')) !== false) ? mb_strtolower(substr($filename, $position + 1)) : '';
    }

    private function calculateTextBox($text, $font_file, $font_size, $font_angle)
    {
        /************
        simple function that calculates the *exact* bounding box (single pixel precision).
        The function returns an associative array with these keys:
        left, top:  coordinates you will pass to imagettftext
        width, height: dimension of the image you have to create
         *************/
        $rect = imagettfbbox($font_size, $font_angle, $font_file, $text);
        $minX = min([$rect[0], $rect[2], $rect[4], $rect[6]]);
        $maxX = max([$rect[0], $rect[2], $rect[4], $rect[6]]);
        $minY = min([$rect[1], $rect[3], $rect[5], $rect[7]]);
        $maxY = max([$rect[1], $rect[3], $rect[5], $rect[7]]);

        return [
            'left' => abs($minX) - 1,
            'top' => abs($minY) - 1,
            'width' => $maxX - $minX,
            'height' => $maxY - $minY,
            'box' => $rect,
        ];
    }

    /**
     * Select appropriate image render type.
     *
     * @param resource    $image
     * @param int         $font_size
     * @param string      $text
     * @param string|null $font
     * @param float       $degrees
     */
    private function write($image, Text $text, int $x, int $y, array $color)
    {
        $color[3] = 127 - ($color[3] >> 1);
        $font = $text->getFont();
        $label = $text->getText();
        $rotation = $text->getRotation();
        $font_size = $text->getFontSize();
        $scale_font_size = $text->shouldScaleFontSize();

        if ($scale_font_size) {
            $bbox = $this->calculateTextBox($label, $font, $font_size, $rotation);
            $image_width = imagesx($image);
            $scale = $image_width / $bbox['width'];
            if ($scale < 1) {
                $font_size = $font_size * $scale * 0.95;
            }
        }

        if ($font !== '') {
            imagettftext($image, $font_size, -$rotation, $x, $y, imagecolorallocatealpha($image, ...$color), $font, $label);
        } elseif ($rotation > 0) {
            // create a tmp image
            $text_width = imagefontwidth($font_size) * strlen($label);
            $text_height = imagefontheight($font_size);
            $text_image = imagecreate($text_width + 3, $text_height + 3);

            imagecolorallocatealpha($text_image, 0, 0, 0, 127);
            // write to the temp image
            imagestring($text_image, $font_size, 2, 2, $label, imagecolorallocatealpha($text_image, ...$color));
            // rotate the temp image
            $text_image = imagerotate($text_image, -$rotation, 0);
            // copy the temp image back to the real image
            imagecopy($image, $text_image, $x, $y, 0, 0, imagesx($text_image), imagesy($text_image));
            imagedestroy($text_image);
        } else {
            imagestring($image, $font_size, $x, $y, $label, imagecolorallocatealpha($image, ...$color));
        }
    }
}
