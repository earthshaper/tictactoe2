@use('App\Icons\Ios')
@use('App\Icons\Android')

<native:top-bar title="Trick Tac Toe" font="display">
    <native:top-bar-action id="reset" label="Reset scores" @tap="resetScores"
                           :ios-icon="Ios::ArrowCounterclockwise" :android-icon="Android::RestartAlt" />
</native:top-bar>

<native:scroll-view class="w-full h-full bg-theme-background">
    <native:column class="w-full p-5 gap-5">

        <native:button-group :options="['vs ' . \App\NativeComponents\TicTacToe::CPU_NAME, '2 Players']" native:model="mode"
                             a11y-label="Game mode" />

        <native:row class="w-full items-center justify-center gap-2">
            <native:icon :ios="Ios::MoonStarsFill" :android="Android::NightsStay" :size="20" class="text-theme-draw" />
            <native:text class="text-sm text-theme-on-surface-variant text-center">Happy Halloween! Pumpkins vs. slime.</native:text>
        </native:row>

        {{-- Scoreboard --}}
        <native:row class="w-full gap-3">
            @foreach (['X', 'draw', 'O'] as $slot)
                @php
                    $active = $slot !== 'draw' && ! $this->isOver() && $turn === $slot;
                    $tone = match ($slot) { 'X' => 'player-x', 'O' => 'player-o', default => 'draw' };
                @endphp
                <native:column class="flex-1 items-center gap-1 py-3 rounded-2xl bg-theme-surface border-2 {{ $active ? 'border-theme-'.$tone : 'border-transparent' }}">
                    <native:text class="text-sm text-theme-on-surface-variant text-center">
                        {{ $slot === 'draw' ? 'Draws' : $this->nameFor($slot) }}
                    </native:text>
                    <native:text font="display" class="text-4xl text-theme-{{ $tone }} text-center">{{ $scores[$slot] }}</native:text>
                </native:column>
            @endforeach
        </native:row>

        {{-- Status line --}}
        <native:row class="w-full h-12 items-center justify-center gap-3">
            @if ($this->cpuThinking())
                <native:activity-indicator size="small" native:poll="100ms" class="text-theme-player-o" />
            @elseif ($winner)
                <native:icon :ios="Ios::TrophyFill" :android="Android::EmojiEvents" :size="28"
                             class="text-theme-{{ $winner === 'X' ? 'player-x' : 'player-o' }}" />
            @endif
            <native:text font="display" class="text-2xl text-theme-on-background text-center">{{ $message }}</native:text>
        </native:row>

        {{-- Board --}}
        <native:column class="w-full aspect-square gap-3 p-3 rounded-3xl bg-theme-surface-variant">
            @foreach ([[0, 1, 2], [3, 4, 5], [6, 7, 8]] as $row)
                <native:row class="w-full flex-1 gap-3">
                    @foreach ($row as $i)
                        @php
                            $mark = $cells[$i];
                            $tone = $mark === 'X' ? 'player-x' : 'player-o';
                            $inLine = in_array($i, $winLine, true);
                            $faded = $this->isOver() && ! $inLine;
                        @endphp
                        <native:pressable native:key="cell-{{ $i }}" @press="play({{ $i }})"
                                          press-scale="0.9"
                                          a11y-label="Row {{ intdiv($i, 3) + 1 }}, column {{ $i % 3 + 1 }}, {{ $mark ?? 'empty' }}"
                                          class="flex-1 h-full items-center justify-center rounded-2xl {{ $inLine ? 'bg-theme-'.$tone.'/20 border-4 border-theme-'.$tone : 'bg-theme-surface shadow-md' }} {{ $faded ? 'opacity-40' : '' }}">
                            @if ($mark === 'X')
                                <native:icon :ios="Ios::Xmark" :android="Android::Close" :size="64" class="text-theme-player-x" />
                            @elseif ($mark === 'O')
                                <native:icon :ios="Ios::Circle" :android="Android::RadioButtonUnchecked" :size="58" class="text-theme-player-o" />
                            @endif
                        </native:pressable>
                    @endforeach
                </native:row>
            @endforeach
        </native:column>

        @if ($this->isOver())
            <native:button label="Play again" variant="primary" size="lg"
                           :ios-icon="Ios::ArrowClockwise" :android-icon="Android::Replay"
                           @tap="playAgain" class="w-full" />
        @else
            <native:button label="Restart round" variant="secondary" @tap="newRound" class="w-full" />
        @endif

        <native:text class="text-sm text-theme-on-surface-variant text-center">
            @if ($this->vsCpu())
                You're the pumpkin X. {{ \App\NativeComponents\TicTacToe::CPU_NAME }} the ghost is the slimy O. Take turns starting each round.
            @else
                Pass the cursed phone! Take turns starting each round.
            @endif
        </native:text>
    </native:column>
</native:scroll-view>
