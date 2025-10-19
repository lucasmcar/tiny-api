<?php


$api->get('/users', 'UserController@index');
$api->get('/profile', 'UserController@profile')->middleware('auth');
$api->post('/login', 'AuthController@login');