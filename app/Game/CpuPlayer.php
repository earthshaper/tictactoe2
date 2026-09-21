<?php

namespace App\Game;

use Closure;

/**
 * A deliberately beatable opponent. It spots wins and threats, but only
 * acts on them some of the time — otherwise it plays a random open cell.
 */
class CpuPlayer
{
    /** Chance the CPU takes a winning move when it has one. */
    public const TAKE_WIN = 0.75;

    /** Chance the CPU blocks the player's winning move. */
    public const BLOCK = 0.5;

    /** Chance the CPU grabs the open center instead of a random cell. */
    public const TAKE_CENTER = 0.3;

    /** @var Closure(): float Returns a float in [0, 1). Injectable for tests. */
    private Closure $roll;

    public function __construct(?Closure $roll = null)
    {
        $this->roll = $roll ?? fn () => mt_rand() / (mt_getrandmax() + 1);
    }

    public function chooseMove(array $cells, string $me, string $opponent): int
    {
        $win = Board::winningMove($cells, $me);
        if ($win !== null && ($this->roll)() < self::TAKE_WIN) {
            return $win;
        }

        $block = Board::winningMove($cells, $opponent);
        if ($block !== null && ($this->roll)() < self::BLOCK) {
            return $block;
        }

        if ($cells[4] === null && ($this->roll)() < self::TAKE_CENTER) {
            return 4;
        }

        $open = Board::emptyCells($cells);

        return $open[array_rand($open)];
    }
}
