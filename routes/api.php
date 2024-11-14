<?php

use App\Http\Controllers\API\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::post('login', [AuthController::class, 'login'])
    ->name('api.login');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::put('/user/profile', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'job' => ['required', 'string', 'max:255'],
        'phone_number' => ['required', 'string', 'max:20'],
        'date_of_birth' => ['required', 'date'],
        'address' => ['required', 'string', 'max:255'],
        'about' => ['required', 'string'],
        'email' => [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            Rule::unique(User::class)->ignore($request->user()->id),
        ]
    ]);

    $request->user()->fill($validated);

    if ($request->user()->isDirty('email')) {
        $request->user()->email_verified_at = null;
    }

    $request->user()->save();

    return $request->user();
})->middleware('auth:sanctum');

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')->name('api.logout');
