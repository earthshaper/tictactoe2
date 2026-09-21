<?php

use App\NativeComponents\TicTacToe;
use Native\Mobile\Testing\Native;

function marks(array $cells, string $mark): int
{
    return count(array_filter($cells, fn ($c) => $c === $mark));
}

it('renders an empty board and scoreboard', function () {
    Native::test(TicTacToe::class)
        ->assertSee('Trick Tac Toe')
        ->assertSee('Boo')
        ->assertSee('Restart round')
        ->assertSet('cells', array_fill(0, 9, null))
        ->assertSet('turn', 'X');
});

it('lets the CPU answer after a thinking delay', function () {
    $test = Native::test(TicTacToe::class)
        ->press('play(4)');

    expect($test->get('cells')[4])->toBe('X')
        ->and($test->get('cpuMoveAt'))->not->toBeNull()
        ->and(marks($test->get('cells'), 'O'))->toBe(0);

    $test->instance()->cpuMoveAt = microtime(true) - 1;
    $test->firePolls();

    expect(marks($test->get('cells'), 'O'))->toBe(1)
        ->and($test->get('turn'))->toBe('X')
        ->and($test->get('cpuMoveAt'))->toBeNull();
});

it('ignores taps while the CPU is thinking or on taken cells', function () {
    $test = Native::test(TicTacToe::class)
        ->press('play(0)')
        ->press('play(1)')
        ->press('play(0)');

    expect(marks($test->get('cells'), 'X'))->toBe(1);
});

it('scores a win in two-player mode and highlights the line', function () {
    $test = Native::test(TicTacToe::class)
        ->set('mode', TicTacToe::MODE_FRIEND);

    foreach ([0, 3, 1, 4, 2] as $cell) {
        $test->press("play({$cell})");
    }

    $test->assertSet('winner', 'X')
        ->assertSet('winLine', [0, 1, 2])
        ->assertSee('Player X wins! Happy Halloween!')
        ->assertSee('Play again');

    expect($test->get('scores'))->toBe(['X' => 1, 'O' => 0, 'draw' => 0]);
});

it('detects a draw', function () {
    $test = Native::test(TicTacToe::class)->set('mode', TicTacToe::MODE_FRIEND);

    // X O X / X O O / O X X
    foreach ([0, 1, 2, 4, 3, 5, 7, 6, 8] as $cell) {
        $test->press("play({$cell})");
    }

    $test->assertSet('draw', true)->assertSet('winner', null);
    expect($test->get('scores')['draw'])->toBe(1);
});

it('alternates who starts each round and the CPU opens when it starts', function () {
    $test = Native::test(TicTacToe::class)->set('cpuDelayMs', 0)->call('playAgain');

    $test->assertSet('starter', 'O');
    expect(marks($test->get('cells'), 'O'))->toBe(1);
});

it('resets scores when switching modes', function () {
    $test = Native::test(TicTacToe::class)->set('mode', TicTacToe::MODE_FRIEND);

    foreach ([0, 3, 1, 4, 2] as $cell) {
        $test->press("play({$cell})");
    }

    $test->set('mode', TicTacToe::MODE_CPU);
    expect($test->get('scores'))->toBe(['X' => 0, 'O' => 0, 'draw' => 0]);
});
