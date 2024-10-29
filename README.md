# Mako CSRF
A middleware wrapper around Mako's [session tokens](https://makoframework.com/docs/10.0/learn-more:sessions#usage:security) as an anti-CSRF mechanism.

## Installation
1. Install the composer package:
    ```bash
    composer require inventor96/mako-csrf
    ```

1. Enable the package in Mako:  
    `app/config/application.php`:
    ```php
    [
        'packages' => [
            'web' => [
                \inventor96\MakoCSRF\CSRFPackage::class
            ],
        ],
    ];
    ```

1. Register the middleware:
    `app/http/routing/middleware.php`:
    ```php
    $dispatcher->registerGlobalMiddleware(\inventor96\MakoCSRF\CSRFMiddleware::class);
    ```

    It's also recommended you set a priority lower than the Mako default of 100:
    `app/http/routing/middleware.php`:
    ```php
    $dispatcher->setMiddlewarePriority(\inventor96\MakoCSRF\CSRFMiddleware::class, 25);
    ```
    At a bare minimum, it should be processed before any other middleware that might cause a change in application.

## Configuration
The default configuration works out of the box, but you may want to change these values for the sake of obfuscation. If you would like to override the default configuration, create a new file at `app/config/packages/csrf/csrf.php`.

The following configuration items and their defaults are as follows:
```php
return [
	/*
	 * ---------------------------------------------------------
	 * Form name
	 * ---------------------------------------------------------
	 *
	 * The name of the form field that will contain the CSRF token.
	 * This applies to both the generated HTML element, as well as the field that is checked in the middleware.
	 */
	'form_name' => 'mako_csrf_token',

	/*
	 * ---------------------------------------------------------
	 * View variable name
	 * ---------------------------------------------------------
	 *
	 * The name of the variable that will be made available in views to contain the CSRF token.
	 */
	'view_var_name' => 'mako_csrf_token',

	/*
	 * ---------------------------------------------------------
	 * Missing token message
	 * ---------------------------------------------------------
	 *
	 * The message of the `BadRequestException` when the CSRF token is missing.
	 */
	'missing_token_message' => 'The CSRF token is missing.',

	/*
	 * ---------------------------------------------------------
	 * Bad token message
	 * ---------------------------------------------------------
	 *
	 * The message of the `BadRequestException` when the CSRF token is invalid.
	 */
	'bad_token_message' => 'The CSRF token is invalid.',
];
```

## Usage
### Middleware
The middleware will automatically require a valid CSRF token for any state-changing HTTP verb (e.g. `POST`, `DELETE`, etc.), and act as a pass-thru for others (e.g. `GET`, `HEAD`, etc.). To override this in either direction, you'll need to override the configuration on a per-route basis using the `$required` parameter.
```php
$routes->post('/articles/{id}', [Articles::class, 'update'])
    ->middleware(CSRFMiddleware::class, required: false);
```

### Views
There are two variables made available in views, and both are based on the `view_var_name` config option. If you change the config option, replace `mako_csrf_token` with your new value in the following examples:
- `$mako_csrf_token`: The CSRF token value itself.
- `$mako_csrf_token_input`: An HTML hidden input element containing the CSRF token with the `name` attribute set to the `form_name` config option. This can be used directly in HTML forms.
    ```html
    <form>
        {{ raw:$mako_csrf_token_input }}
        <!-- The generated element would look like <input type="hidden" name="mako_csrf_token" value="..." /> -->
    </form>
    ```