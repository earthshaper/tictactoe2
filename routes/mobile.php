<?php

use App\NativeComponents\TicTacToe;
use Illuminate\Support\Facades\Route;

Route::native('/', TicTacToe::class);
