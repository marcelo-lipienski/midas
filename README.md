# Midas Authorization

Midas Authorization is a PSR7-compliant middleware that aims to be both succinct and powerful.

## Instalation

If you don't have composer, first install it by following the official docs at [https://getcomposer.org/download/](https://getcomposer.org/download/).

After you have installed composer, you can install this library in your project by running:

```
composer require mlipienski/midas
```

## Concept

Midas Authorization works on a strategy-basis. This means it can check authorization against virtually any source, such as database, API and so-on.
By exposing a simple Factory that takes a Strategy as first argument and a callable as the second, it allows you to write custom authorization checks without having to write a new Strategy.

## Usage

Midas Authorization can be used as a middleware at any project that implements PSR7

```php
// Slim microframework example

$app->add(
    new Midas\AuthorizationMiddleware(
        Midas\Factories\StrategyFactory::use(
            Midas\Strategis\JWTStrategy::class,
            function ($credentials) {
                // your logic
                return true
            }
        )
    )
);
```