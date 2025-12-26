<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('frontend.home');
    return redirect()->route('admin.login');
});
// admin part
Route::get('admin/login', 'App\Http\Controllers\Auth\Admin\LoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'App\Http\Controllers\Auth\Admin\LoginController@login');
Route::post('admin/logout', 'App\Http\Controllers\Auth\Admin\LoginController@logout')->name('admin.logout');

Route::get('admin/change-password', 'App\Http\Controllers\Auth\Admin\ChangePasswordController@changePassword')->name('admin.change-password');
Route::post('admin/change-password', 'App\Http\Controllers\Auth\Admin\ChangePasswordController@updatePassword')->name('admin.update-password');

Route::get('admin/forget-password', 'App\Http\Controllers\Auth\Admin\ForgotPasswordController@showForgetPasswordForm')->name('admin.forget.password.get');
Route::post('admin/forget-password', 'App\Http\Controllers\Auth\Admin\ForgotPasswordController@submitForgetPasswordForm')->name('admin.forget.password.post');
Route::get('admin/reset-password/{token}', 'App\Http\Controllers\Auth\Admin\ForgotPasswordController@showResetPasswordForm')->name('admin.reset.password.get');
Route::post('admin/reset-password', 'App\Http\Controllers\Auth\Admin\ForgotPasswordController@submitResetPasswordForm')->name('admin.reset.password.post');

// member part
Route::get('member/login', 'App\Http\Controllers\Auth\Member\LoginController@showLoginForm')->name('member.login');
Route::post('member/login', 'App\Http\Controllers\Auth\Member\LoginController@login');
Route::post('member/logout', 'App\Http\Controllers\Auth\Member\LoginController@logout')->name('member.logout');

Route::get('member/change-password', 'App\Http\Controllers\Auth\Member\ChangePasswordController@changePassword')->name('member.change-password');
Route::post('member/change-password', 'App\Http\Controllers\Auth\Member\ChangePasswordController@updatePassword')->name('member.update-password');

Route::get('member/forget-password', 'App\Http\Controllers\Auth\Member\ForgotPasswordController@showForgetPasswordForm')->name('member.forget.password.get');
Route::post('member/forget-password', 'App\Http\Controllers\Auth\Member\ForgotPasswordController@submitForgetPasswordForm')->name('member.forget.password.post');
Route::get('member/reset-password/{token}', 'App\Http\Controllers\Auth\Member\ForgotPasswordController@showResetPasswordForm')->name('member.reset.password.get');
Route::post('member/reset-password', 'App\Http\Controllers\Auth\Member\ForgotPasswordController@submitResetPasswordForm')->name('member.reset.password.post');


