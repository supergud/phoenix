<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.list.name.cap$};

use Windwalker\Core\Controller\Controller;

/**
 * The SaveController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class SaveController extends Controller
{
	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$task = $this->input->get('task', 'filter');

		$class = __NAMESPACE__ . '\\' . ucfirst($task) . 'Controller';

		$this->hmvc($class);

		$this->setRedirect($this->router->http($this->app->get('route.matched')));

		return true;
	}
}