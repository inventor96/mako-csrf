<?php
namespace inventor96\MakoCSRF;

use mako\application\Package;
use mako\config\Config;
use mako\session\Session;
use mako\view\ViewFactory;

class CSRFPackage extends Package {
	protected string $packageName = 'inventor96/mako-csrf';
	protected string $fileNamespace = 'csrf';

	/**
	 * @inheritDoc
	 */
	function bootstrap(): void {
		// get dependencies
		/** @var Session */
		$session = $this->container->get(Session::class);
		/** @var Config */
		$config = $this->container->get(Config::class);

		// get variables
		$form_name = $config->get('csrf::csrf.form_name', 'mako_csrf_token');
		$view_var_name = $config->get('csrf::csrf.view_var_name', 'mako_csrf_token');
		$csrf_token = $session->generateOneTimeToken();

		// register view variables
		$this->container->get(ViewFactory::class)->autoAssign('*', fn() => [
			$view_var_name => $csrf_token,
			$view_var_name . '_input' => '<input type="hidden" name="' . $form_name . '" value="' . $csrf_token . '"/>',
		]);
	}
}