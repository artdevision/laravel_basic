<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Technical task

You have 3 tables - users , posts , comments

User has right to do CRUD with post and comments which means any user can create any post and leave any comment under any post.
But , only owner of the post or the comment has right to update it or delete it , other users have no right to update and delete others’ posts or comments

Requirements:
1. Do it using Laravel Framework , version 9
2. Database should be Postgres

Endpoints:
1. GET /api/users should return all users with posts and all comments which the current user left under any post

2. GET /api/posts should return all posts belong to current user and all comments of each user

3. POST /api/posts

4. POST /api/comments
## Install project
``cp .env.example .env``

``composer install``

``./vendor/bin/sail build --no-cache``

## Startup
``./vendor/bin/sail up``

## Run tests

``./vendor/bin/sail test``

## Routes

Endpoints optimized by post relation

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

## API Documentation

http://localhost:8880/api/documentation
