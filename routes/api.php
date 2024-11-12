<?php

use Illuminate\Support\Facades\Route;

Route::match(['post', 'put'], '/', \ticketeradigital\bsale\Controllers\WebHook::class);
