<?php
/**
 * MultiMaps - An extension allows users to display maps and coordinate data using multiple mapping services
 *
 * @link https://www.mediawiki.org/wiki/Extension:MultiMaps Documentation
 * @file MultiMaps.php
 * @defgroup MultiMaps
 * @ingroup Extensions
 * @author Pavel Astakhov <pastakhov@yandex.ru>
 * @license GPL-2.0-or-later
 */

// Check to see if we are being called as an extension or directly
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is an extension to MediaWiki and thus not a valid entry point.' );
}

define( 'MultiMaps_VERSION', '0.7.3 -- OGF' );

// Register this extension on Special:Version
$wgExtensionCredits['parserhook'][] = [
	'path'				=> __FILE__,
	'name'				=> 'MultiMaps',
	'version'			=> MultiMaps_VERSION,
	'url'				=> 'https://www.mediawiki.org/wiki/Extension:MultiMaps',
	'author'			=> '[https://www.mediawiki.org/wiki/User:Pastakhov Pavel Astakhov]',
	'descriptionmsg'	=> 'multimaps-desc',
	'license-name'		=> 'GPL-2.0-or-later'
];

// Tell the whereabouts of files
$dir = __DIR__;
$egMultiMapsScriptPath = ( $wgExtensionAssetsPath === false ? $wgScriptPath . '/extensions' : $wgExtensionAssetsPath ) . '/MultiMaps';

// Allow translations for this extension
$wgMessagesDirs['MultiMaps'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['MultiMapsMagic'] = $dir . '/MultiMaps.i18n.magic.php';

// Include the settings file.
require_once $dir . '/Settings.php';

// Specify the function that will initialize the parser function.
/**
 * @codeCoverageIgnore
 */
$wgHooks['ParserFirstCallInit'][] = static function ( Parser &$parser ) {
	$parser->setFunctionHook( 'multimaps', 'MultiMaps::renderParserFunction_showmap' );
	return true;
};

// Preparing classes for autoloading
// TODO: $wgAutoloadClasses = array_merge( $wgAutoloadClasses, include 'MultiMaps.classes.php' );
$wgAutoloadClasses['MultiMaps'] = $dir . '/MultiMaps.body.php';

$wgAutoloadClasses['MultiMaps\\BaseMapService'] = $dir . '/includes/BaseMapService.php';
$wgAutoloadClasses['MultiMaps\\Bounds'] = $dir . '/includes/Bounds.php';
$wgAutoloadClasses['MultiMaps\\Geocoders'] = $dir . '/includes/Geocoders.php';
$wgAutoloadClasses['MultiMaps\\GeoCoordinate'] = $dir . '/includes/GeoCoordinate.php';
$wgAutoloadClasses['MultiMaps\\MapServices'] = $dir . '/includes/MapServices.php';
$wgAutoloadClasses['MultiMaps\\Point'] = $dir . '/includes/Point.php';

$wgAutoloadClasses['MultiMaps\\BaseMapElement'] = $dir . '/includes/mapelements/BaseMapElement.php';
$wgAutoloadClasses['MultiMaps\\Marker'] = $dir . '/includes/mapelements/Marker.php';
$wgAutoloadClasses['MultiMaps\\Line'] = $dir . '/includes/mapelements/Line.php';
$wgAutoloadClasses['MultiMaps\\Polygon'] = $dir . '/includes/mapelements/Polygon.php';
$wgAutoloadClasses['MultiMaps\\Rectangle'] = $dir . '/includes/mapelements/Rectangle.php';
$wgAutoloadClasses['MultiMaps\\Circle'] = $dir . '/includes/mapelements/Circle.php';

// define modules that can later be loaded during the output
$wgResourceModules['ext.MultiMaps'] = [
	'scripts' => [ 'multimaps.js' ],
	'localBasePath' => $dir . '/resources',
	'remoteExtPath' => 'MultiMaps/resources',
];

// OGF service
$wgAutoloadClasses["MultiMaps\OGF"] = $dir . '/services/OGF/OGF.php';
$wgResourceModules['ext.MultiMaps.OGF'] = [
	'scripts' => [ 'ext.ogf.js' ],
	'localBasePath' => $dir . '/services/OGF',
	'remoteExtPath' => 'MultiMaps/services/OGF',
];

// Leaflet service
$wgAutoloadClasses['MultiMaps\\Leaflet'] = $dir . '/services/Leaflet/Leaflet.php';
$wgResourceModules['ext.MultiMaps.Leaflet'] = [
	'scripts' => [ 'ext.leaflet.js' ],
	'localBasePath' => $dir . '/services/Leaflet',
	'remoteExtPath' => 'MultiMaps/services/Leaflet',
];

// Google service
$wgAutoloadClasses['MultiMaps\\Google'] = $dir . '/services/Google/Google.php';
$wgResourceModules['ext.MultiMaps.Google'] = [
	'scripts' => [ 'ext.google.js' ],
	'localBasePath' => $dir . '/services/Google',
	'remoteExtPath' => 'MultiMaps/services/Google',
];

// Yandex service
$wgAutoloadClasses['MultiMaps\\Yandex'] = $dir . '/services/Yandex/Yandex.php';
$wgResourceModules['ext.MultiMaps.Yandex'] = [
	'scripts' => [ 'ext.yandex.js' ],
	'localBasePath' => $dir . '/services/Yandex',
	'remoteExtPath' => 'MultiMaps/services/Yandex',
];

/**
 * Add files to phpunit test
 * @codeCoverageIgnore
 */
$wgHooks['UnitTestsList'][] = static function ( &$files ) {
	$files[] = __DIR__ . '/tests/phpunit/includes/BoundsTest.php';
	$files[] = __DIR__ . '/tests/phpunit/includes/GeoCoordinateTest.php';
	$files[] = __DIR__ . '/tests/phpunit/includes/GeocodersTest.php';
	$files[] = __DIR__ . '/tests/phpunit/includes/MapServicesTest.php';
	$files[] = __DIR__ . '/tests/phpunit/includes/PointTest.php';
	$files[] = __DIR__ . '/tests/phpunit/includes/mapelements/PolygonTest.php';
	$files[] = __DIR__ . '/tests/phpunit/includes/mapelements/RectangleTest.php';
	$files[] = __DIR__ . '/tests/phpunit/services/Google/GoogleTest.php';
	$files[] = __DIR__ . '/tests/phpunit/services/Leaflet/LeafletTest.php';
	$files[] = __DIR__ . '/tests/phpunit/services/Yandex/YandexTest.php';
	return true;
};
