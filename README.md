# Midas Authorization

Midas Authorization is a PSR7-compliant middleware that aims to be both succinct and powerful.

## Concept

Midas Authorization works on a strategy-basis. This means it can check authorization against virtually any source, such as database, API and so-on.

A Strategy _[raison d'être](https://en.wiktionary.org/wiki/raison_d%27etre)_ is to extract provided credentials from any given source and inject the extracted information into a given _callable_.

For example, _JWTStrategy_ will attempt to extract an user credentials from the Request HTTP Authorization header.

By exposing a simple Factory that takes a Strategy as first argument and a callable as the second, it allows you to write custom authorization checks without having to concern yourself of writing both credentials extractor and authorization logic.

## Instalation

If you don't have composer, first install it by following the official docs at [https://getcomposer.org/download/](https://getcomposer.org/download/).

After you have installed composer, you can install this library in your project by running:

```
composer require mlipienski/midas
```

## Usage

Midas Authorization can be used as a middleware at any project that implements PSR7

```php
// Slim microframework example

$app->add(
    new Midas\AuthorizationMiddleware(
        Midas\Factories\StrategyFactory::use(
            Midas\Strategies\JWTStrategy::class,
            function ($credentials) {
                // your logic
                return true
            }
        )
    )
);
```

## Contributing

I'm currently looking for devs and maintainers for this project.
If you want to be a part, check the "What's next?" section.


## What's next?

- Code refactor
- Check for coding standards
- Rewrite existing tests
- Add more tests (100% code coverage would be awesome)
- Manage Github repository (I really suck at VCS)
- Improve this README

## Authors

- [Marcelo Lipienski](https://github.com/marcelo-lipienski)

## License
This project is licensed under the terms of the MIT license.