<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::resource('/admin-ddkits-facebook', 'Ddkits\DdkitsFacebook\Controllers\DdkitsfacebookCont');
    Route::post('/admin-ddkits-facebook-post', ['as' => 'admin-ddkits-facebook.post', 'uses' => 'Ddkits\DdkitsFacebook\Controllers\DdkitsfacebookCont@postToPage']);
    Route::post('/admin-ddkits-facebook-post-live', ['as' => 'admin-ddkits-facebook.postlive', 'uses' => 'Ddkits\DdkitsFacebook\Controllers\DdkitsfacebookCont@postToPageLive']);
});
