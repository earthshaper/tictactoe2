<?php

namespace App\NativeComponents;

use App\Game\Board;
use App\Game\CpuPlayer;
use Illuminate\Support\Arr;
use Native\Mobile\Edge\Element;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\Facades\Device;

class TicTacToe extends NativeComponent
{
    public const MODE_CPU = 0;

    public const MODE_FRIEND = 1;

    public const CPU_NAME = 'Bleep';

    /** @var array<int, string|null> */
    public array $cells = [];

    /** Bound to the mode button group: 0 = vs CPU, 1 = two players. */
    public int $mode = self::MODE_CPU;

    public string $turn = 'X';

    /** Who opens the next round — alternates so the CPU gets to start sometimes. */
    public string $starter = 'X';

    public ?string $winner = null;

    /** @var array<int> */
    public array $winLine = [];

    public bool $draw = false;

    /** @var array{X: int, O: int, draw: int} */
    public array $scores = ['X' => 0, 'O' => 0, 'draw' => 0];

    public string $message = '';

    /** Unix time (float seconds) at which the CPU makes its pending move. */
    public ?float $cpuMoveAt = null;

    /** Fake "thinking" time so the CPU doesn't answer instantly. */
    public int $cpuDelayMs = 650;

    public function mount(): void
    {
        $this->newRound();
    }

    public function render(): Element
    {
        // A `native:poll` in the view re-renders while the CPU is thinking;
        // each re-render lands here, and once the delay is up it plays.
        if ($this->cpuMoveAt !== null && microtime(true) >= $this->cpuMoveAt) {
            $this->cpuMoveAt = null;
            $this->playCpuMove();
        }

        return $this->view('tic-tac-toe');
    }

    public function play(int $index): void
    {
        if ($this->isOver() || $this->cpuThinking() || $this->cells[$index] !== null) {
            return;
        }

        if ($this->vsCpu() && $this->turn === 'O') {
            return;
        }

        $this->place($index);

        if (! $this->isOver() && $this->vsCpu()) {
            $this->queueCpuMove();
        }
    }

    public function newRound(): void
    {
        $this->cells = array_fill(0, 9, null);
        $this->winner = null;
        $this->winLine = [];
        $this->draw = false;
        $this->cpuMoveAt = null;
        $this->turn = $this->starter;

        if ($this->vsCpu() && $this->turn === 'O') {
            $this->message = Arr::random(['My turn first. Beep boop.', 'Bleep goes first!', 'Watch and learn...']);
            $this->queueCpuMove();
        } else {
            $this->message = $this->vsCpu()
                ? Arr::random(['Your move!', 'You go first. Pick a square!', 'Ready when you are.'])
                : "{$this->turn} goes first!";
        }
    }

    public function playAgain(): void
    {
        $this->starter = $this->starter === 'X' ? 'O' : 'X';
        $this->newRound();
    }

    public function resetScores(): void
    {
        $this->scores = ['X' => 0, 'O' => 0, 'draw' => 0];
        $this->starter = 'X';
        $this->newRound();
    }

    public function updatedMode(): void
    {
        $this->resetScores();
    }

    public function vsCpu(): bool
    {
        return $this->mode === self::MODE_CPU;
    }

    public function isOver(): bool
    {
        return $this->winner !== null || $this->draw;
    }

    public function cpuThinking(): bool
    {
        return $this->cpuMoveAt !== null;
    }

    public function nameFor(string $mark): string
    {
        if ($this->vsCpu()) {
            return $mark === 'X' ? 'You' : self::CPU_NAME;
        }

        return "Player {$mark}";
    }

    private function queueCpuMove(): void
    {
        $this->cpuMoveAt = microtime(true) + $this->cpuDelayMs / 1000;
    }

    private function playCpuMove(): void
    {
        if ($this->isOver() || $this->turn !== 'O') {
            return;
        }

        $this->place((new CpuPlayer)->chooseMove($this->cells, 'O', 'X'));
    }

    private function place(int $index): void
    {
        $this->cells[$index] = $this->turn;

        if ($result = Board::winner($this->cells)) {
            [$this->winner, $this->winLine] = $result;
            $this->scores[$this->winner]++;
            $this->message = $this->winMessage($this->winner);
            $this->buzz();

            return;
        }

        if (Board::isFull($this->cells)) {
            $this->draw = true;
            $this->scores['draw']++;
            $this->message = Arr::random(["It's a draw!", 'Stalemate! Nobody wins.', 'A tie! Great minds...']);

            return;
        }

        $this->turn = $this->turn === 'X' ? 'O' : 'X';
        $this->message = $this->turnMessage();
    }

    private function turnMessage(): string
    {
        if (! $this->vsCpu()) {
            return "Player {$this->turn}'s turn";
        }

        return $this->turn === 'X'
            ? Arr::random(['Your turn!', 'Hmm, your move.', 'Top that!', 'Go on then...', 'Your move, human.'])
            : Arr::random(['Bleep is thinking...', 'Calculating...', 'Hmm, let me see...', 'Processing...']);
    }

    private function winMessage(string $mark): string
    {
        if (! $this->vsCpu()) {
            return "Player {$mark} wins!";
        }

        return $mark === 'X'
            ? Arr::random(['You win! Nicely done!', 'Victory! Bleep is sad now.', 'You beat the robot!', 'Winner winner!'])
            : Arr::random(['Bleep wins! Beep boop!', 'Robots rule! Try again?', 'Gotcha! Bleep wins.']);
    }

    private function buzz(): void
    {
        try {
            Device::vibrate();
        } catch (\Throwable) {
            // No haptics outside a device (tests, Jump fallbacks) — that's fine.
        }
    }
}
