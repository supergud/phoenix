<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Record\Record;

/**
 * The AdminRepositoryInterface class.
 *
 * @since  {DEPLOY_VERSION}
 */
interface AdminRepositoryInterface
{
	const ORDER_POSITION_FIRST = 'first';
	const ORDER_POSITION_LAST  = 'last';

	/**
	 * reorder
	 *
	 * @param array  $orders
	 * @param string $orderField
	 *
	 * @return  boolean
	 */
	public function reorder($orders = array(), $orderField = null);

	/**
	 * reorder
	 *
	 * @param array  $conditions
	 * @param string $orderField
	 *
	 * @return bool
	 */
	public function reorderAll($conditions = array(), $orderField = null);

	/**
	 * getReorderConditions
	 *
	 * @param Record $record
	 *
	 * @return  array  An array of conditions to add to ordering queries.
	 */
	public function getReorderConditions(Record $record);

	/**
	 * Method to set new item ordering as first or last.
	 *
	 * @param   Record $record    Item table to save.
	 * @param   string $position `first` or other are `last`.
	 *
	 * @return  void
	 */
	public function setOrderPosition(Record $record, $position = self::ORDER_POSITION_LAST);
}
