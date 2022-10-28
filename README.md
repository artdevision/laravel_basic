<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Install project
``cp .env.example .env``

``composer install``

``./vendor/bin/sail build --no-cache``

## Startup
``./vendor/bin/sail up``

## Run tests

``./vendor/bin/sail test``

## Routes
```shell
  POST       api/comments/{id} ..................................... Api\CommentsController@update
  DELETE     api/comments/{id} ..................................... Api\CommentsController@delete
  GET|HEAD   api/posts .................................................. Api\PostsController@list
  POST       api/posts/create ......................................... Api\PostsController@create
  GET|HEAD   api/posts/{id} ............................................ Api\PostsController@index
  POST       api/posts/{id} ........................................... Api\PostsController@update
  DELETE     api/posts/{id} ........................................... Api\PostsController@delete
  GET|HEAD   api/posts/{post_id}/comments .................... Api\CommentsController@getAllByPost
  POST       api/posts/{post_id}/comments .......................... Api\CommentsController@create
  GET|HEAD   api/user ............................................................................ 
  GET|HEAD   api/users .................................................. Api\UsersController@list

```
