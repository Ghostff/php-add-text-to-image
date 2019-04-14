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
    /** @var string|null  */
    private $text = null;
    /** @var int|null  */
    private $position_x = null;
    /** @var int|null  */
    private $position_y = null;
    /** @var string|null  */
    private $font = null;
    /** @var int|null  */
    private $font_size = null;
    /** @var array|null  */
    private $color = null;
    /** @var string|null  */
    private $image_path = null;
    /** @var string|null  */
    private $save_path = null;
    /** @var null|array  */
    private $shadow_color = null;
    /** @var null|int  */
    private $shadow_position_x = null;
    /** @var null|int */
    private $shadow_position_y = null;

    /**
     * Img constructor.
     *
     * @param string      $text         The text to add to image.
     * @param int         $position_x   The X position of text.
     * @param int         $position_y   The Y position of text.
     * @param array       $color        The Color of ext.
     * @param string|null $image        The Image to add text to.
     * @param string|null $font         The font of text to add to image.
     * @param int         $font_size    The font size of text to add to image.
     * @param string      $save_path    The save location of new image.
     */
    public function __construct(
        string $text,
        int $position_x = 0,
        int $position_y = 0,
        array $color = [255, 255, 255],
        string $image = null,
        string $font = null,
        int $font_size = 5,
        string $save_path = null
    )
    {
        $this->text         = $text;
        $this->position_x   = $position_x;
        $this->position_y   = $position_y;
        $this->color        = $color;
        $this->image_path   = $image;
        $this->font_size    = $font_size;
        $this->font         = $font;
        $this->save_path    = $save_path;
    }

    /**
     * Sets the position of text.
     *
     * @param int $position_x   The X position of text.
     * @param int $position_y   The Y position of text.
     * @return \TextToImage
     */
    public function setPosition(int $position_x, int $position_y): TextToImage
    {
        $this->position_x = $position_x;
        $this->position_y = $position_y;

        return $this;
    }

    /**
     * Sets text font size/
     *
     * @param int $size The font size of text.
     * @return \TextToImage
     */
    public function setFontSize(int $size): TextToImage
    {
        $this->font_size = $size;

        return $this;
    }

    /**
     * Sets text font path.
     *
     * @param string $font_path The text font path.
     * @return \TextToImage
     */
    public function setFont(string $font_path): TextToImage
    {
        $this->font = $font_path;

        return $this;
    }

    /**
     * Sets text color.
     *
     * @param int $r    Red.
     * @param int $g    Green.
     * @param int $b    Blue
     * @return \TextToImage
     */
    public function setColor(int $r = 255, int $g = 255, int $b = 255): TextToImage
    {
        $this->color = [$r, $g, $b];

        return $this;
    }

    /**
     * Sets the image text will be written on.
     *
     * @param string $image_path    The path to the image.
     * @return \TextToImage
     */
    public function setImage(string $image_path): TextToImage
    {
        $this->image_path = $image_path;

        return $this;
    }

    /**
     * Sets image save path. Used if image is to be save in a file.
     *
     * @param string $save_path The image save path.
     * @return \TextToImage
     */
    public function setSavePath(string $save_path): TextToImage
    {
        $this->save_path = $save_path;

        return $this;
    }

    /**
     * Sets image shadow, color and position.
     *  Calling this method without args will enable shadow.
     *
     * @param int|null $position_x  Shadow X position.
     * @param int|null $position_y  Shadow Y position.
     * @param array    $color       Shadow color.
     * @return $this
     */
    public function setShadow(int $position_x = null, int $position_y = null, array $color = [0, 0, 0])
    {
        $this->shadow_position_x = $position_x;
        $this->shadow_position_y = $position_y;
        $this->shadow_color      = $color;

        return $this;
    }

    /**
     * Select appropriate image render type.
     *
     * @param resource $image
     * @param int      $font_size
     * @param int      $x
     * @param int      $y
     * @param string   $text
     * @param int      $color
     */
    private function resolveRender($image, int $font_size, int $x, int $y, string $text, int $color)
    {
        if ($this->font) {
            imagettftext ($image, $font_size, 0, $x, $y, $color , $this->font, $text);
        }
        else {
            imagestring($image, $font_size, $x, $y, $text, $color);
        }
    }

    /**
     * Renders Text upon image.
     *
     * @param string|null $save_as The name to save new rendered image as.
     * @return string|null
     */
    public function render(string $save_as = null): ?string
    {
        if ($this->save_path && ! is_readable($this->save_path)) {
            mkdir($this->save_path, 755, true);
        }

        if (! $this->image_path || ($this->image_path && ! is_readable($this->image_path))) {
            throw new RuntimeException('Image to write text to not specified or does not exist.');
        }

        if ($this->font && ! is_readable($this->font)) {
            throw new RuntimeException('Font not found.');
        }

        $ext = strtolower(pathinfo($this->image_path, PATHINFO_EXTENSION));

        if ($ext == 'jpg' || $ext == 'jpeg') {
            $image = imagecreatefromjpeg($this->image_path);
        }
        elseif ($ext == 'png') {
            $image = imagecreatefrompng($this->image_path);
        }
        elseif ($ext == 'gif') {
            $image = imagecreatefromgif($this->image_path);
        }
        else {
            throw new RuntimeException("{$ext} not supported, implement it yourself.");
        }

        $new_color  = imagecolorallocate($image, $this->color[0], $this->color[1], $this->color[2]);
        if ($this->shadow_color) {
            $shadow = imagecolorallocate($image, $this->shadow_color[0], $this->shadow_color[1], $this->shadow_color[2]);
            $this->resolveRender(
                $image,
                $this->font_size,
                $this->shadow_position_x ?? ($this->position_x + 1),
                $this->shadow_position_y ?? ($this->position_y + 1),
                $this->text,
                $shadow ?? $new_color
            );
        }

        $this->resolveRender($image, $this->font_size, $this->position_x, $this->position_y, $this->text, $new_color);

        $save_as = $save_as ? "{$this->save_path}/{$save_as}.{$ext}" : null;
        imagepng($image, $save_as);
        imagedestroy($image);

        return $save_as;
    }
}