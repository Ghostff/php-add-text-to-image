<?php

declare(strict_types=1);

/**
 *
 * @license
 *
 * New BSD License
 *
 * Copyright (c) 2017, ghostff community
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *      1. Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *      2. Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *      3. All advertising materials mentioning features or use of this software
 *      must display the following acknowledgement:
 *      This product includes software developed by the ghostff.
 *      4. Neither the name of the ghostff nor the
 *      names of its contributors may be used to endorse or promote products
 *      derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY ghostff ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL GHOSTFF COMMUNITY BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

class TextToImage
{
    private $maps              = [];
    private $work_with         = null;
    private $create            = [];
    /** @var null|string  */
    private $text              = null;
    private $position_x        = 0;
    private $position_y        = 0;
    private $font              = null;
    private $font_size         = 5;
    private $color             = [255, 255, 255];
    private $shadow_color      = [];
    private $shadow_position_x = null;
    private $shadow_position_y = null;

    /**
     * TextToImage constructor.
     *
     * @param string $image_path    The path to image to modify.
     * @param array $create         An an array of properties for creating new image if $image_path is ''
     */
    protected function __construct(string $image_path, array $create = [])
    {
        $this->work_with = $image_path;
        $this->create    = $create;
    }

    /**
     * Select appropriate image render type.
     *
     * @param resource $image
     * @param int $font_size
     * @param int $x
     * @param int $y
     * @param string $text
     * @param int $color
     * @param string|null $font
     */
    private function write($image, int $font_size, int $x, int $y, string $text, int $color, string $font = null)
    {
        if ($font !== null) {
            imagettftext($image, $font_size, 0, $x, $y, $color, $font, $text);
        } else {
            imagestring($image, $font_size, $x, $y, $text, $color);
        }
    }

    /**
     * Opens Specified image for modification.
     *
     * @param Closure ...$closures  A sets of image modifications.
     * @return $this
     */
    public function open(Closure ...$closures): self
    {
        $this->maps = array_merge($this->maps, $closures);

        return $this;
    }

    /**
     * Writes modifications to image and return new imag path.
     *
     * @param string|null $save_path    The path to save modified image, if null, image is outputted to browser.
     * @return string
     */
    public function close(string $save_path = null)
    {
        if (count($this->create) != 0)
        {
            $ext   = $this->create[2];
            $image = @imagecreate($this->create[0], $this->create[1]);
            imagecolorallocate($image, ...$this->create[3]);
        }
        else
        {
            if (!is_readable($this->work_with)) {
                throw new RuntimeException('Image to write text to not specified or does not exist.');
            }

            $ext = strtolower(pathinfo($this->work_with, PATHINFO_EXTENSION));

            if ($ext == 'jpg' || $ext == 'jpeg') {
                $image = imagecreatefromjpeg($this->work_with);
            } elseif ($ext == 'png') {
                $image = imagecreatefrompng($this->work_with);
            } elseif ($ext == 'gif') {
                $image = imagecreatefromgif($this->work_with);
            } else {
                throw new RuntimeException("{$ext} not supported, implement it yourself.");
            }
        }

        /** @var TextToImage $map */
        foreach ($this->maps as $closure)
        {
            $closure($map = new self(''));

            if ($map->font !== null && ! is_readable($map->font)) {
                throw new RuntimeException("Font \"{$map->font}\" not found.");
            }

            $new_color = imagecolorallocate($image, $map->color[0], $map->color[1], $map->color[2]);

            if (count($map->shadow_color) != 0) {
                $shadow = imagecolorallocate($image, $map->shadow_color[0], $map->shadow_color[1], $map->shadow_color[2]);
                $this->write(
                    $image,
                    $map->font_size,
                    $map->shadow_position_x + $map->position_x,
                    $map->shadow_position_y + $map->position_y,
                    $map->text,
                    $shadow ?? $new_color,
                    $map->font
                );
            }

            $this->write($image, $map->font_size, $map->position_x, $map->position_y, $map->text, $new_color, $map->font);
        }

        $save_as = $save_path ? "$save_path.{$ext}" : null;

        if ($ext == 'jpg' || $ext == 'jpeg') {
            imagejpeg($image, $save_as);
        } elseif ($ext == 'png') {
            imagepng($image, $save_as);
        } elseif ($ext == 'gif') {
            imagegif($image, $save_as);
        }
        imagedestroy($image);

        return $save_path;
    }

    /**
     * Sets up a new image for modification.
     *
     * @param string $image_path    The image path.
     * @return static
     */
    public static function setImage(string $image_path): self
    {
        return new self($image_path);
    }

    /**
     * Create a new image for modification.
     *
     * @param int $width        The width of the image.
     * @param int $height       The height of the image.
     * @param string $ext       The image format e.g png, jpeg or gif
     * @param array $bg_color   An array [r, g, b] of image background color.
     * @return static
     */
    public static function createImage(int $width, int $height, string $ext = 'png', array $bg_color = [255, 255, 255]): self
    {
        return new self('', [$width, $height, $ext, $bg_color]);
    }

    /**
     * Generic Set.
     *
     * @param string $text                      Text to add.
     * @param int $position_x                   Text X position.
     * @param int $position_y                   Text Y position.
     * @param array $color                      Text color [r, g, b]
     * @param string|null $font                 Text font file path.
     * @param int $font_size                    Text font size.
     * @param int|null $shadow_position_x       Text shadow position x.
     * @param int|null $shadow_position_y       Text shadow position y.
     * @param array $shadow_color               Text shadow color.
     * @return $this
     */
    public function set(
        string $text,
        int $position_x        = 0,
        int $position_y        = 0,
        array $color           = [255, 255, 255],
        string $font           = null,
        int $font_size         = 5,
        int $shadow_position_x = null,
        int $shadow_position_y = null,
        array $shadow_color    = [0, 0, 0]
    ): self
    {
        $this->text              = $text;
        $this->position_x        = $position_x;
        $this->position_y        = $position_y;
        $this->color             = $color;
        $this->font_size         = $font_size;
        $this->font              = $font;
        $this->shadow_position_x = $shadow_position_x;
        $this->shadow_position_y = $shadow_position_y;
        $this->shadow_color      = $shadow_color;

        return $this;
    }

    /**
     * Adds a text.
     *
     * @param string $text  The text to add.
     * @return $this
     */
    public function add(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Sets the position of added text.
     *
     * @param int $x    The X position.
     * @param int $y    The Y position.
     * @return $this
     */
    public function position(int $x, int $y): self
    {
        $this->position_x = $x;
        $this->position_y = $y;

        return $this;
    }

    /**
     * Sets a font file and size for specified text.
     *
     * @param int $size             The text font size.
     * @param string|null $path     The text font file path.
     * @return $this
     */
    public function font(int $size, string $path = null): self
    {
        $this->font_size = $size;
        $this->font      = $path;

        return $this;
    }

    /**
     * Sets a color for specified text.
     *
     * @param int $r    Red
     * @param int $g    Green
     * @param int $b    Blue.
     * @return $this
     */
    public function color(int $r = 255, int $g = 255, int $b = 255): self
    {
        $this->color = [$r, $g, $b];

        return $this;
    }

    /**
     * Sets a shadow for specified text.
     *
     * @param int|null $position_x  The shadow x position.
     * @param int|null $position_y  The shadow y position.
     * @param array $color          Array [r, g, b] or the shadow color.
     * @return $this
     */
    public function shadow(int $position_x = null, int $position_y = null, array $color = []): self
    {
        $this->shadow_position_x = $position_x;
        $this->shadow_position_y = $position_y;
        $this->shadow_color      = $color;

        return $this;
    }

}