# Laravel API Interaction

# Installation

```shell script
composer require marksihor/laravel-api-interaction 
```

Publish the config file if You need to change something:

```shell script
php artisan vendor:publish --provider="ApiInteraction\\ApiInteractionServiceProvider" --tag=config
```

Publish the migrations (if need to log requests)
```shell script
php artisan vendor:publish --provider="ApiInteraction\\ApiInteractionServiceProvider" --tag=migrations
```

# License

MIT
