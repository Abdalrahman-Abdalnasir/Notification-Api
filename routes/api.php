<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;


Route::post('/notifications', [NotificationController::class, 'send']);
