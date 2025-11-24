<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('medical:send-reminders')
    ->dailyAt('09:00')
    ->timezone('Europe/Paris')
    ->emailOutputOnFailure(config('mail.from.address'));
