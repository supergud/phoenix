<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Windwalker\Form\Field\ListField;
use Windwalker\Html\Option;
use Windwalker\Ioc;

/**
 * The SqlListField class.
 *
 * @since  1.0
 */
class SqlListField extends ListField
{
	/**
	 * Property valueField.
	 *
	 * @var  string
	 */
	protected $valueField = 'id';

	/**
	 * Property textField.
	 *
	 * @var  string
	 */
	protected $textField = 'title';

	/**
	 * prepareOptions
	 *
	 * @return  Option[]
	 */
	protected function prepareOptions()
	{
		$valueField = $this->get('value_field', $this->valueField);
		$textField  = $this->get('text_field', $this->textField);
		$attribs    = $this->get('option_attribs', array());

		$items = $this->getItems();

		$options = array();

		foreach ($items as $item)
		{
			$options[] = $this->createOption($item, $valueField, $textField, $attribs);
		}

		return $options;
	}

	/**
	 * createOption
	 *
	 * @param object $item
	 * @param string $valueField
	 * @param string $textField
	 * @param array  $attribs
	 *
	 * @return  Option
	 */
	protected function createOption($item, $valueField = 'id', $textField = 'title', $attribs = array())
	{
		$value = isset($item->$valueField) ? $item->$valueField : null;
		$text  = isset($item->$textField)  ? $item->$textField : null;

		$level = !empty($item->level) ? $item->level - 1 : 0;

		if ($level < 0)
		{
			$level = 0;
		}

		return new Option(str_repeat('- ', $level) . $text, $value, $attribs);
	}

	/**
	 * getItems
	 *
	 * @return  \stdClass[]
	 */
	protected function getItems()
	{
		$db = Ioc::getDatabase();

		$query = $this->get('query', $this->get('sql'));

		if (is_callable($query))
		{
			$handler = $query;

			$query = $db->getQuery(true);

			call_user_func($handler, $query, $this);
		}

		if (!$query)
		{
			return array();
		}

		return (array) $db->setQuery($query)->loadAll();
	}
}
