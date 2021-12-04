<?php declare(strict_types=1);

namespace Ghostff\TextToImage;

use Closure;

class Text
{
    private $text              = '';
    private $position_x        = 0;
    private $position_y        = 0;
    private $font              = '';
    private $font_size         = 5;
    private $color             = [0xff, 0xff, 0xff, 0xff];
    private $shadow_color      = [];
    private $shadow_position_x = 0;
    private $shadow_position_y = 0;
    private $rotation          = 0.0;
    private $callback          = null;


    public function getText(): string {
        return $this->text;
    }

    public function getPosition(): array {
        return [$this->position_x, $this->position_y];
    }

    public function getFont(): string {
        return $this->font;
    }

    public function getFontSize(): int {
        return $this->font_size;
    }

    public function getColor(): array {
        return $this->color;
    }

    public function getShadow(): array {
        return [$this->shadow_position_x, $this->shadow_position_y];
    }

    public function getShadowColor(): array {
        return $this->shadow_color;
    }

    public function getRotation(): float {
        return $this->rotation;
    }

    public function getCallback() {
        return $this->callback;
    }

    /**
     * Static instance for a Text class.
     *
     * @param string $text
     *
     * @return static
     */
    public static function from(string $text): self
    {
        return new self($text);
    }

    /**
     * Constructor.
     *
     * @param string $text  The text to add.
     * @return $this
     */
    public function __construct(string $text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Generic Set.
     *
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
        int $position_x        = 0,
        int $position_y        = 0,
        array $color           = [255, 255, 255, 255],
        string $font           = '',
        int $font_size         = 5,
        int $shadow_position_x = 0,
        int $shadow_position_y = 0,
        array $shadow_color    = [0, 0, 0, 0]
    ): self
    {
        $this->position_x        = $position_x;
        $this->position_y        = $position_y;
        $this->color             = $color + [0, 0, 0, 255];
        $this->font_size         = $font_size;
        $this->font              = $font;
        $this->shadow_position_x = $shadow_position_x;
        $this->shadow_position_y = $shadow_position_y;
        $this->shadow_color      = $shadow_color + [0, 0, 0, 255];

        return $this;
    }

    /**
     * Sets the position of added text.
     *
     * @param int $x    The X position.
     * @param int $y    The Y position.
     * @return $this
     */
    public function position(int $x, int $y = 0): self
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
     *  The red, green and blue parameters are integers between 0 and 255 or hexadecimals between 0x00 and 0xFF.
     *
     * @param int $r    Value of red component .
     * @param int $g    Value of green component.
     * @param int $b    Value of blue component.
     * @param int $a    A value between 0 and 255. 255 indicates completely opaque while 0 indicates completely transparent.
     *
     * @return $this
     */
    public function color(int $r = 255, int $g = 255, int $b = 255, int $a = 255): self
    {
        $this->color = [$r, $g, $b, $a];

        return $this;
    }

    /**
     * Sets a shadow for specified text.
     *
     * @param int|null $position_x  The shadow x position.
     * @param int|null $position_y  The shadow y position.
     * @param array $color          Array [r(red), g(green), b(blue), a(alpha)] See color method.
     * @return $this
     */
    public function shadow(int $position_x = null, int $position_y = null, array $color = []): self
    {
        $this->shadow_position_x = $position_x;
        $this->shadow_position_y = $position_y;
        $this->shadow_color      = $color + [0, 0, 0, 255];

        return $this;
    }

    /**
     * Rotate current text to a specific angle.
     *
     * @param float $degrees    The angle in degrees.
     *
     * @return $this
     */
    public function rotate(float $degrees): self
    {
        $this->rotation = $degrees;

        return $this;
    }

    /**
     * Sets a callback that will be evaluated before the image is rendered.
     *  The closure takes 2 argument,
     *      callback(TextToImage: $text_to_image, Text $text)
     *
     * @param Closure $closure
     *
     * @return $this
     * @example:
     *         ->update(function (TextToImage $text_to_image, Text $text) {
     *              $text->position_x = intval($text_to_image->height / 2);
     *              $text->position_y = intval($text_to_image->width / 2);
     *          });
     */
    public function update(Closure $closure): self
    {
        $this->callback = $closure;

        return $this;
    }
}