<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Phoenix\Html\HtmlHeader;
use Windwalker\Core\Security\CsrfProtection;
use Windwalker\Utilities\ArrayHelper;

/**
 * The CoreScript class.
 *
 * @see  AbstractPhoenixScript
 *
 * @since  1.0
 */
abstract class CoreScript extends AbstractPhoenixScript
{
	/**
	 * requireJS
	 *
	 * @return  void
	 */
	public static function requireJS()
	{
		if (!static::inited(__METHOD__))
		{
			static::getAsset()->addScript(static::phoenixName() . '/js/core/require.min.js');
		}
	}

	/**
	 * underscore
	 *
	 * @param boolean $noConflict
	 *
	 * @return  void
	 */
	public static function underscore($noConflict = true)
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$onload = '_.erbTemplate = _.templateSettings; _.bladeTemplate = _.templateSettings = { evaluate: /\{\%(.+?)\%\}/g, interpolate: /\{\!\!(.+?)\!\!\}/g, escape: /\{\{(.+?)\}\}/g };';

			$asset->addScript(static::phoenixName() . '/js/core/underscore.min.js', null, array('onload' => $onload));
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$asset->internalScript('var underscore = _.noConflict();');
		}
	}

	/**
	 * underscoreString
	 *
	 * @param bool $noConflict
	 *
	 * @return  void
	 */
	public static function underscoreString($noConflict = true)
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/core/underscore.string.min.js');
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$js = <<<JS
(function(s) {
	var us = function(underscore)
	{
		underscore.string = underscore.string || s;
	};
	us(window._ || (window._ = {}));
	us(window.underscore || (window.underscore = {}));
})(s);
JS;

			$asset->internalScript($js);
		}
	}

	/**
	 * backbone
	 *
	 * @param bool  $noConflict
	 * @param array $options
	 *
	 * @return  void
	 */
	public static function backbone($noConflict = false, $options = array())
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			JQueryScript::core(ArrayHelper::getValue($options, 'jquery_no_conflict', false));
			static::underscore(ArrayHelper::getValue($options, 'jquery_no_conflict', true));

			$asset->addScript(static::phoenixName() . '/js/core/backbone.min.js');
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$asset->internalScript(';var backbone = Backbone.noConflict();');
		}
	}

	/**
	 * simpleUri
	 *
	 * @param bool $noConflict
	 *
	 * @return  void
	 */
	public static function simpleUri($noConflict = false)
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/core/simple-uri.min.js');
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$asset->internalScript(';var SimpleURI = URI.noConflict();');
		}
	}

	/**
	 * moment
	 *
	 * @param bool $timezone
	 *
	 * @return  void
	 */
	public static function moment($timezone = false)
	{
		if (!static::inited(__METHOD__))
		{
			static::addJS(static::phoenixName() . '/js/datetime/moment.min.js');
		}

		if (!static::inited(__METHOD__) && $timezone)
		{
			static::addJS(static::phoenixName() . '/js/datetime/moment-timezone.min.js');
		}
	}

	/**
	 * csrfToken
	 *
	 * @param string $token
	 *
	 * @return  void
	 */
	public static function csrfToken($token = null)
	{
		if (!static::inited(__METHOD__, get_defined_vars()))
		{
			// Inject Token to meta
			HtmlHeader::addMetadata('csrf-token', $token ?: CsrfProtection::getFormToken());
		}
	}
}
