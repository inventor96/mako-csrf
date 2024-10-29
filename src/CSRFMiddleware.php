<?php
namespace inventor96\MakoCSRF;

use mako\http\Request;
use mako\http\Response;
use Closure;
use mako\config\Config;
use mako\http\exceptions\BadRequestException;
use mako\http\routing\middleware\MiddlewareInterface;
use mako\session\Session;

class CSRFMiddleware implements MiddleWareInterface {
	/**
	 * @var array An array of HTTP methods that would cause a change in the application state.
	 */
	protected const STATE_CHANGERS = [
		'POST',
		'PUT',
		'PATCH',
		'DELETE',
	];

	protected string $form_name;
	protected string $missing_token_message;
	protected string $bad_token_message;
	protected ?bool $required;

	public function __construct(protected Session $session, Config $config, ?bool $required = null) {
		$this->form_name = $config->get('csrf::csrf.form_name', 'mako_csrf_token');
		$this->missing_token_message = $config->get('csrf::csrf.missing_token_message', 'The CSRF token is missing.');
		$this->bad_token_message = $config->get('csrf::csrf.bad_token_message', 'The CSRF token is invalid.');
		$this->required = $required;
	}

	public function execute(Request $request, Response $response, Closure $next): Response {
		// check if a token is required
		$required = $this->required === true
			|| (
				$this->required !== false
				&& in_array($request->getMethod(), self::STATE_CHANGERS, true)
			);

		// check if the token is valid
		if ($required) {
			$req_token = $request->getData()->get($this->form_name);

			if ($req_token === null) {
				throw new BadRequestException($this->missing_token_message);
			}
			if (!$this->session->validateOneTimeToken($req_token)) {
				throw new BadRequestException($this->bad_token_message);
			}
		}

		/** @var Response */
		$response = $next($request, $response);

		return $response;
	}
}