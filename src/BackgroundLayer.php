<?php declare(strict_types=1);

namespace Ghostff\TextToImage;

class BackgroundLayer
{
    private $position_x1        = 0;
    private $position_y1        = 0;
    private $position_x2        = null;
    private $position_y2        = null;
    private $color             = [0xff, 0xff, 0xff, 0xff];

    public function getPosition(): array {
        return [$this->position_x1, $this->position_y1, $this->position_x2, $this->position_y2];
    }

    public function getColor(): array {
        return $this->color;
    }

    public static function create(): self
    {
        return new self();
    }

    /**
     * Generic Set.
     *
     * @param int $position_x1                   Left X position.
     * @param int $position_y1                   Left Y position.
     * @param int $position_x2                   Right X position. If value is null than it will equals the width of image.
     * @param int $position_y2                   Right Y position. If value is null than it will equals the height of image.
     * @param array $color                      Text color [r, g, b]
     * @return $this
     */
    public function set(
        int $position_x1        = 0,
        int $position_y1        = 0,
        int $position_x2        = null,
        int $position_y2        = null,
        array $color           = [255, 255, 255, 255]
    ): self
    {
        $this->position_x1        = $position_x1;
        $this->position_y1        = $position_y1;
        $this->position_x2        = $position_x2;
        $this->position_y2        = $position_y2;
        $this->color             = $color + [0, 0, 0, 255];

        return $this;
    }

    /**
     * Sets the position of added background layer.
     *
     * @param int $x1    Left X position.
     * @param int $y1    Left Y position.
     * @param int $x2    Right X position. If value is null than it will equals the width of image.
     * @param int $y2    Right Y position. If value is null than it will equals the height of image.
     * @return $this
     */
    public function position(int $x1, int $y1 = 0, int $x2=null, int $y2=null): self
    {
        $this->position_x1 = $x1;
        $this->position_y1 = $y1;
        $this->position_x2 = $x2;
        $this->position_y2 = $y2;

        return $this;
    }

    /**
     * Sets a color for background layer.
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

}