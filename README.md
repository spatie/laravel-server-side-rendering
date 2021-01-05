# Server side rendering JavaScript in your Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-server-side-rendering.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-server-side-rendering)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-server-side-rendering/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-server-side-rendering.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-server-side-rendering)

Making server side rendering a bit less hard in Laravel.

```blade
<html>
    <head>
        <title>My server side rendered app</title>
        <script defer src="{{ mix('app-client.js') }}">
    </head>
    <body>
        {!! ssr('js/app-server.js') !!}
    </body>
</html>
```

This package is a Laravel bridge for the [spatie/server-side-rendering](https://github.com/spatie/server-side-rendering) library. Before getting started, dig through the readme to learn about the underlying concepts and caveats. This readme also assumes you already have some know-how about building server rendered JavaScript apps.

Vue and React example apps are available at [spatie/laravel-server-side-rendering-examples](https://github.com/spatie/laravel-server-side-rendering-examples) if you want to see it in action.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-server-side-rendering.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-server-side-rendering)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-server-side-rendering
```

The service provider and `Ssr` alias will be automatically registered.

You can optionally publish the config file if you want to tweak things.

```bash
php artisan vendor:publish --provider="Spatie\Ssr\SsrServiceProvider" --tag="config"
```

## Usage

### Prerequisites

First you'll need to pick an engine to execute your scripts. The server-side-rendering library ships with V8 and Node engines. By default, the package is configured to use node, since you probably already have that installed on your system.

Set up the `NODE_PATH` environment variable in your .env file to get started:

```
NODE_PATH=/path/to/my/node
```

You'll also need to ensure that a `storage/app/ssr` folder exists, or change the `ssr.node.temp_path` config value to something else.

If you'd rather use the V8 engine, you can skip the previous two steps. You'll need to have the [v8js extension](https://github.com/phpv8/v8js) installed though.

### Configuration

Besides the above, no configuration's required. If you need to tweak things anyway, the [config file](https://github.com/spatie/laravel-server-side-rendering/blob/master/config/ssr.php) is well documented.

### Setting up your scripts

You'll need to build two scripts: a server script and a client script. Refer to your frontend-framework-of-choice's documentation on how to build those.

```js
mix.js('resources/js/app-client.js', 'public/js')
   .js('resources/js/app-server.js', 'public/js');
```

The server script should be passed to the `ssr` function, the client script should be loaded manually. The package assumes you're using Laravel Mix, and will resolve the path for you. You can opt out of this behaviour by setting `mix` to `false` in the config file.

```blade
{!! ssr('js/app-server.js') !!}
<script src="{{ mix('js/app-client.js') }}">
```

Your server script should call a `dispatch` function to send the rendered html back to the view. Here's a quick example of a set of Vue scripts for a server-rendered app. Read the [spatie/server-side-rendering](https://github.com/spatie/server-side-rendering#core-concepts) readme for a full explanation of how everything's tied together.

```js
// resources/js/app.js

import Vue from 'vue';
import App from './components/App';

export default new Vue({
    render: h => h(App),
});
```

```js
// resources/js/app-client.js

import app from './app';

app.$mount('#app');
```

```js
// resources/js/app-server.js

import app from './app';
import renderVueComponentToString from 'vue-server-renderer/basic';

renderVueComponentToString(app, (err, html) => {
    if (err) {
        throw new Error(err);
    }

    dispatch(html);
});
```

### Rendering an app in your view

The package exposes an `ssr` helper to render your app.

```blade
<html>
    <head>
        <title>My server side rendered app</title>
        <script defer src="{{ mix('js/app-client.js') }}"></script>
    </head>
    <body>
        {!! ssr('js/app-server.js')->render() !!}
    </body>
</html>
```

A facade is available too.

```blade
<html>
    <head>
        <title>My server side rendered app</title>
        <script defer src="{{ mix('js/app-client.js') }}"></script>
    </head>
    <body>
        {!! Ssr::entry('js/app-server.js')->render() !!}
    </body>
</html>
```

Rendering options can be chained after the function or facade call.

```blade
<html>
    <head>
        <title>My server side rendered app</title>
        <script defer src="{{ mix('js/app-client.js') }}"></script>
    </head>
    <body>
        {!! ssr('js/app-server.js')->context('user', $user)->render() !!}
    </body>
</html>
```

Available options are documented at [spatie/server-side-rendering](https://github.com/spatie/server-side-rendering#rendering-options).

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
