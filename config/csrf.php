<?php
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