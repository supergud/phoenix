<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model\Traits;

use Windwalker\Core\Package\Resolver\FieldDefinitionResolver;
use Phoenix\Form\NullFiledDefinition;
use Phoenix\Form\Renderer\InputRenderer;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Core\Mvc\MvcHelper;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Form\Validate\ValidateResult;
use Windwalker\Ioc;
use Windwalker\Utilities\ArrayHelper;

/**
 * The AbstractFormModel class.
 *
 * @since  1.0
 */
trait FormAwareRepositoryTrait
{
	/**
	 * Property formRenderer.
	 *
	 * @var callable
	 */
	protected $formRenderer = InputRenderer::class;

	/**
	 * getDefaultData
	 *
	 * @return array
	 */
	public function getFormDefaultData()
	{
		$sessionData = (array) $this['form.data'];

		try
		{
			$keyName = $this['keyName'] ? : $this->getKeyName();
		}
		catch (\DomainException $e)
		{
			$keyName = null;
		}

		$keyName = $keyName ? : 'id';

		$item = $this->getItem();

		if ($sessionData && ArrayHelper::getValue($sessionData, $keyName) == $item->$keyName)
		{
			return $sessionData;
		}

		return $item->dump(true);
	}

	/**
	 * getForm
	 *
	 * @param string|FieldDefinitionInterface $definition
	 * @param string                          $control
	 * @param bool|mixed                      $loadData
	 *
	 * @return Form
	 */
	public function getForm($definition = null, $control = null, $loadData = false)
	{
		$form = new Form($control);

		if (is_string($definition))
		{
			$definition = $this->getFieldDefinition($definition);
		}

		$form->defineFormFields($definition);

		if ($loadData === true)
		{
			$form->bind($this->getFormDefaultData());
		}
		elseif ($loadData)
		{
			$form->bind($loadData);
		}

		$renderer = $this->get('field.renderer', $this->formRenderer);

		if (class_exists($renderer))
		{
			$form->setRenderer(new $renderer);
		}

		Ioc::getDispatcher()->triggerEvent('onModelAfterGetForm', array(
			'form'       => $form,
			'model'      => $this,
			'control'    => $control,
			'definition' => $definition
		));

		return $form;
	}

	/**
	 * getFieldDefinition
	 *
	 * @param string $definition
	 * @param string $name
	 *
	 * @return FieldDefinitionInterface
	 */
	public function getFieldDefinition($definition = null, $name = null)
	{
		if (class_exists($definition) && is_subclass_of($definition, FieldDefinitionInterface::class))
		{
			return new $definition;
		}

		$name = $name ? : $this->getName();

		if (!$class = FieldDefinitionResolver::create(ucfirst($name) . '\\' . ucfirst($definition)))
		{
			$class = sprintf(
				'%s\Form\%s\%sDefinition',
				MvcHelper::getPackageNamespace($this, 2),
				ucfirst($name),
				ucfirst($definition)
			);

			if (!class_exists($class))
			{
				return new NullFiledDefinition;
			}
		}

		return new $class;
	}

	/**
	 * filter
	 *
	 * @param array  $data
	 * @param string $formDefine
	 *
	 * @return array
	 */
	public function prepareStore($data, $formDefine = 'edit')
	{
		$form = $this->getForm($formDefine);

		$form->bind($data);
		$form->filter();
		$form->prepareStore();

		return $form->getValues();
	}

	/**
	 * validate
	 *
	 * @param   array $data
	 * @param   Form  $form
	 *
	 * @return bool
	 * @throws ValidateFailException
	 */
	public function validate($data, Form $form = null)
	{
		$form = $form ? : $this->getForm('edit');

		$form->bind($data);

		if ($form->validate())
		{
			return true;
		}

		$errors = $form->getErrors();

		$msg = array();

		foreach ($errors as $error)
		{
			$field = $error->getField();

			if ($error->getResult() == ValidateResult::STATUS_REQUIRED)
			{
				$msg[ValidateResult::STATUS_REQUIRED][] = Translator::sprintf('phoenix.message.validation.required', $field->getLabel() ? : $field->getName(false));
			}
			elseif ($error->getResult() == ValidateResult::STATUS_FAILURE)
			{
				$msg[ValidateResult::STATUS_FAILURE][] = Translator::sprintf('phoenix.message.validation.failure', $field->getLabel() ? : $field->getName(false));
			}
		}

		throw new ValidateFailException($msg);
	}

	/**
	 * Method to get property FormRenderer
	 *
	 * @return  callable
	 */
	public function getFormRenderer()
	{
		return $this->formRenderer;
	}

	/**
	 * Method to set property formRenderer
	 *
	 * @param   callable $formRenderer
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setFormRenderer($formRenderer)
	{
		$this->formRenderer = $formRenderer;

		return $this;
	}
}
