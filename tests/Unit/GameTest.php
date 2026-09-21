<?php

use App\Game\Board;
use App\Game\CpuPlayer;

it('finds winners across rows, columns and diagonals', function (array $line) {
    $cells = array_fill(0, 9, null);
    foreach ($line as $i) {
        $cells[$i] = 'O';
    }

    expect(Board::winner($cells))->toBe(['O', $line]);
})->with(array_map(fn ($l) => [$l], Board::LINES));

it('reports no winner on an unfinished board', function () {
    expect(Board::winner(['X', 'O', null, null, 'X', null, null, null, 'O']))->toBeNull();
});

it('finds the winning move for a mark', function () {
    expect(Board::winningMove(['X', 'X', null, null, 'O', null, null, 'O', null], 'X'))->toBe(2);
});

it('takes a win when the dice say so', function () {
    $cpu = new CpuPlayer(fn () => 0.0);

    expect($cpu->chooseMove(['O', 'O', null, 'X', 'X', null, null, null, null], 'O', 'X'))->toBe(2);
});

it('sometimes misses wins and blocks, so it stays beatable', function () {
    $cpu = new CpuPlayer(fn () => 0.99);
    $cells = ['O', 'O', null, 'X', 'X', null, 'X', null, null];

    $moves = collect(range(1, 200))->map(fn () => $cpu->chooseMove($cells, 'O', 'X'))->unique();

    expect($moves->count())->toBeGreaterThan(1)
        ->and($moves->every(fn ($m) => $cells[$m] === null))->toBeTrue();
});
