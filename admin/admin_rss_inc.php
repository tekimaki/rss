<?php

// $Header$

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

foreach( $gBitSystem->mPackages as $pkg => $pkgInfo ) {
	if( is_file( $pkgInfo['path'].$pkg.'_rss.php' ) ) {
		$formRSSFeeds[$pkg.'_rss'] = array(
			'label' => $pkg,
		);
	}
}
$gBitSmarty->assign( "formRSSFeeds", $formRSSFeeds );

$formRSSSettings = array(
	'rssfeed_language' => array(
		'label' => 'Language',
	),
	'rssfeed_creator' => array(
		'label' => 'Creator',
	),
	'rssfeed_editor' => array(
		'label' => 'Editor',
		'note' => 'Email address for person responsible for editorial content. For RDF 2.0',
	),
	'rssfeed_webmaster' => array(
		'label' => 'Webmaster',
		'note' => 'Email address for person responsible for technical issues relating to channel. For RDF 2.0',
	),
	'rssfeed_image_url' => array(
		'label' => 'Image URL',
		'note' => 'Enter the full URL to an image that you want to associate with your RSS channels',
	),
	'rssfeed_css_url' => array(
		'label' => 'CSS File URL',
		'note' => 'Enter the full URL to a CSS file you want to use to style your RSS Feeds.',
	),
	'rssfeed_truncate' => array(
		'label' => 'Truncate RSS feed',
		'note' => 'Enter the number of characters you want to feed per item in the rss feeds. Default is 5000 characters.',
	),
);
$gBitSmarty->assign( "formRSSSettings", $formRSSSettings );

$formRSSOptions = array(
	'rssfeed_httpauth' => array(
		'label' => 'Enable HTTP Authentication',
		'note' => 'Use HTTP Authentication with SSL to enable Registered Users to gain access to Private Content Feeds.',
	),
);
$gBitSmarty->assign( "formRSSOptions", $formRSSOptions );

$cacheTimes = array(
	0      => tra( "(no cache)" ),
	60     => "1 ".tra( "minute" ),
	300    => "5 ".tra( "minutes" ),
	600    => "10 ".tra( "minutes" ),
	1800   => "30 ".tra( "minutes" ),
	3600   => "1 ".tra( "hour" ),
	7200   => "2 ".tra( "hours" ),
	14400  => "4 ".tra( "hours" ),
);
$gBitSmarty->assign( "cacheTimes", $cacheTimes );

$feedTypes = array(
	0 => "RSS 0.91",
	1 => "RSS 1.0",
	2 => "RSS 2.0",
	3 => "PIE 0.1",
	4 => "MBOX",
	5 => "ATOM",
	6 => "ATOM 0.3",
	7 => "OPML",
	8 => "HTML",
	9 => "JS",
);
$gBitSmarty->assign( "feedTypes", $feedTypes );

if( !empty( $_REQUEST['feed_settings'] ) ) {
	// save package specific RSS feed settings
	foreach( array_keys( $formRSSFeeds ) as $item ) {
		$package = preg_replace( "/^rss_/", "", $item );
		simple_set_toggle( $item, $package );
		simple_set_int( $item.'_max_records', $package );
		simple_set_value( $item.'_title', $package );
		simple_set_value( $item.'_description', $package );
	}

	// deal with the RSS settings
	foreach( array_keys( $formRSSSettings ) as $item ) {
		simple_set_value( $item, RSS_PKG_NAME );
	}
	simple_set_value( 'rssfeed_default_version' );
	simple_set_int( 'rssfeed_cache_time' );

	foreach( $formRSSOptions as $item => $data ) {
		simple_set_toggle( $item, RSS_PKG_NAME );
	}
}
?>
