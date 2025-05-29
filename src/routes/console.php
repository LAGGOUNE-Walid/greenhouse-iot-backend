<?php
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:listen-for-dead-nodes')->hourly();