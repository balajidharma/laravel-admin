<h1 align="center">Laravel Admin</h1>
<h3 align="center">Admin Panel package for your Laravel projects.</h3>
<p align="center">
<a href="https://packagist.org/packages/balajidharma/laravel-admin"><img src="https://poser.pugx.org/balajidharma/laravel-admin/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/balajidharma/laravel-admin"><img src="https://poser.pugx.org/balajidharma/laravel-admin/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/balajidharma/laravel-admin"><img src="https://poser.pugx.org/balajidharma/laravel-admin/license" alt="License"></a>
</p>

## Table of Contents

- [Installation](#installation)

## Installation
- Install the package via composer
```bash
composer require balajidharma/laravel-admin
```
- Publish the migration and views
```bash
php artisan vendor:publish --provider="BalajiDharma\LaravelAdmin\AdminServiceProvider"
```
- Run the migrations
```bash
php artisan migrate
```
