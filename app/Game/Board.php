<?php

namespace App\Game;

/**
 * Pure tic-tac-toe rules: cells are indexed 0-8, left-to-right, top-to-bottom.
 * Each cell holds 'X', 'O', or null.
 */
class Board
{
    public const LINES = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // rows
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // columns
        [0, 4, 8], [2, 4, 6],            // diagonals
    ];

    /** @return array{0: string, 1: array<int>}|null [winner, winning line] */
    public static function winner(array $cells): ?array
    {
        foreach (self::LINES as $line) {
            [$a, $b, $c] = $line;

            if ($cells[$a] !== null && $cells[$a] === $cells[$b] && $cells[$a] === $cells[$c]) {
                return [$cells[$a], $line];
            }
        }

        return null;
    }

    public static function isFull(array $cells): bool
    {
        return ! in_array(null, $cells, true);
    }

    /** @return array<int> */
    public static function emptyCells(array $cells): array
    {
        return array_keys(array_filter($cells, fn ($cell) => $cell === null));
    }

    /** The cell that would complete a line for $mark, if one exists. */
    public static function winningMove(array $cells, string $mark): ?int
    {
        foreach (self::emptyCells($cells) as $index) {
            $cells[$index] = $mark;

            if (self::winner($cells) !== null) {
                return $index;
            }

            $cells[$index] = null;
        }

        return null;
    }
}
