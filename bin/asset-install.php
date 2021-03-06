<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use Windwalker\Core\Asset\AssetInstaller;

include_once __DIR__ . '/../../../autoload.php';

$assets = [
	'underscore' => [
		'underscore.js' => 'js/core/underscore.js',
		'underscore.min.js' => 'js/core/underscore.min.js',
	],
	'underscore.string' => [
		'dist/underscore.string.js' => 'js/core/underscore.string.js',
		'dist/underscore.string.min.js' => 'js/core/underscore.string.min.js',
	],
	'requirejs' => [
		'require.js' => 'js/core/require.js'
	],
	'backbone' => [
		'backbone.js' => 'js/core/backbone.js',
		'backbone.min.js' => 'js/core/backbone.min.js',
	],
	'punycode' => [
		'punycode.js' => 'js/string/punycode.js'
	],
	'jquery' => [
		'dist/jquery.js' => 'js/jquery/jquery.js',
		'dist/jquery.min.js' => 'js/jquery/jquery.min.js',
		'dist/jquery.min.map' => 'js/jquery/jquery.min.map',
	],
//	'jquery.ui' => [
//		'jquery.ui.core.js' => 'js/jquery/jquery.ui.core.js',
//		'ui/effect.js'      => 'js/jquery-ui/jquery.ui.effect.js',
//		'ui/widgets/draggable.js'  => 'js/jquery/jquery.ui.draggable.js',
//		'ui/widgets/droppable.js'  => 'js/jquery/jquery.ui.droppable.js',
//		'ui/widgets/resizable.js'  => 'js/jquery/jquery.ui.resizable.js',
//		'ui/widgets/selectable.js' => 'js/jquery/jquery.ui.selectable.js',
//		'ui/widgets/sortable.js'   => 'js/jquery/jquery.ui.sortable.js',
//	],
	'vue' => [
		'dist/vue.js' => 'js/vue/vue.js',
		'dist/vue.min.js' => 'js/vue/vue.min.js',
	],
	'vue-resource' => [
		'dist/vue-resource.js' => 'js/vue/vue-resource.js',
		'dist/vue-resource.min.js' => 'js/vue/vue-resource.min.js',
	],
	'vue-strap' => [
		'dist/vue-strap.js' => 'js/vue/vue-strap.js',
		'dist/vue-strap.min.js' => 'js/vue/vue-strap.min.js',
		'dist/vue-strap.js.map' => 'js/vue/vue-strap.js.map',
	],
	'vue-router' => [
		'dist/vue-router.js' => 'js/vue/vue-router.js',
		'dist/vue-router.min.js' => 'js/vue/vue-router.min.js',
	],
	'vuex' => [
		'dist/vuex.js' => 'js/vue/vuex.js',
		'dist/vuex.min.js' => 'js/vue/vuex.min.js',
		'dist/vuex.js.map' => 'js/vue/vuex.js.map',
	],
	'vue2-animate' => [
		'dist/vue2-animate.css' => 'css/vue/vue2-animate.css',
		'dist/vue2-animate.min.css' => 'css/vue/vue2-animate.min.css',
	],
	'moment' => [
		'moment.js' => 'js/datetime/moment.js',
		'min/moment.min.js' => 'js/datetime/moment.min.js',
	],
	'moment-timezone' => [
		'moment-timezone.js' => 'js/datetime/moment-timezone.js',
		'builds/moment-timezone.min.js' => 'js/datetime/moment-timezone.min.js',
	]
];

$app = new AssetInstaller(
	'phoenix',
	__DIR__ . '/../bower_components',
	__DIR__ . '/../src/Resources/asset',
	$assets
);

//// @Before jQueryUI
//$app->addHook('before-jquery.ui', function (AssetInstaller $app, $vendorName)
//{
//	// Build core
//	$coreFiles = [
//		'version',
//		'data',
//		'disable-selection',
//		'escape-selector',
//		'effect',
//		'focusable',
//		'ie',
//		'form',
//		'form-reset-mixin',
//		'keycode',
//		'labels',
//		'plugin',
//		'position',
//		'safe-active-element',
//		'safe-blur',
//		'scroll-parent',
//		'tabbable',
//		'unique-id',
//		'widget'
//	];
//
//	$content = '';
//
//	foreach ($coreFiles as $coreFile)
//	{
//		$coreFile = new SplFileInfo($app->getVendorPath() . '/jquery.ui/ui/' . $coreFile . '.js');
//
//		/** @var SplFileInfo $coreFile */
//		if ($coreFile->getBasename('.js') === 'core')
//		{
//			continue;
//		}
//
//		$content .= file_get_contents($coreFile->getPathname()) . "\n\n";
//	}
//
//	$dest = $app->getVendorPath() . '/jquery.ui/jquery.ui.core.js';
//
//	file_put_contents($dest, $content);
//
//	$app->out('Build jQueryUI core file to: ' . $dest);
//});
//
//// @After jQueryUI
//$app->addHook('after-jquery.ui', function (AssetInstaller $app, $vendorName)
//{
//	// Build core
//	$files = \Windwalker\Filesystem\Filesystem::files($app->getAssetPath() . '/js/jquery-ui');
//
//	foreach ($files as $file)
//	{
//		/** @var SplFileInfo $file */
//		$js = JSMinPlus::minify(file_get_contents($file->getPathname()));
//
//		$dest = $file->getPath() . '/' . $file->getBasename('.js') . '.min.js';
//		file_put_contents($dest, $js);
//		$app->out('Minify file to: ' . $dest);
//	}
//});

// @After RequireJS
$app->addHook('after-requirejs', function (AssetInstaller $app, $vendorName)
{
	$app->minify($app->getAssetPath() . '/js/core/require.js');
});

// @After Punycode
$app->addHook('after-punycode', function (AssetInstaller $app, $vendorName)
{
	$app->minify($app->getAssetPath() . '/js/string/punycode.js');
});

$app->execute();
