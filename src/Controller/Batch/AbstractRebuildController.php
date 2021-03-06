<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Windwalker\Core\Controller\Traits\CsrfProtectionTrait;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Language\Translator;
use Windwalker\Data\Data;

/**
 * The AbstractRebuildController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class AbstractRebuildController extends AbstractBatchController
{
	use CsrfProtectionTrait;

	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'rebuild';

	/**
	 * doExecute
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function doExecute()
	{
		try
		{
			$this->record->rebuild();

			$ids = $this->model->getDataMapper()->findColumn('id', array('parent_id != 0'));

			foreach ($ids as $id)
			{
				$this->record->rebuildPath($id);
			}
		}
		catch (\Exception $e)
		{
			if (WINDWALKER_DEBUG)
			{
				throw $e;
			}

			$this->setRedirect($this->getFailRedirect(), $e->getMessage(), Bootstrap::MSG_DANGER);
		}

		$this->setRedirect($this->getSuccessRedirect(), $this->getSuccessMessage(), Bootstrap::MSG_SUCCESS);

		return true;
	}

	/**
	 * getSuccessMessage
	 *
	 * @param Data $data
	 *
	 * @return  string
	 */
	public function getSuccessMessage($data = null)
	{
		return Translator::translate($this->langPrefix . 'message.batch.' . $this->action . '.success');
	}
}
