<?php

if (!function_exists('__')) {

  function __($str, $domain) {
    return $str;
  }

}

if (!function_exists('putDefaultOptions')) {

  function putDefaultOptions($db, $options) {
    $row = array();
    foreach ($options as $k => $o) {
      $row[$k] = $o['value'];
    }
    $rowDB = $db->getMetaData('options_meta');
    $row = array_merge($row, $rowDB);
    $db->putMetaData('options_meta', $row);
  }

}

if (!function_exists('randString')) {

  function randString($len = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyz';
    $chars .= strtoupper($chars) . '0123456789';
    $charLen = strlen($chars) - 1;
    $string = '';
    for ($i = 0; $i < $len; $i++) {
      $pos = rand(0, $charLen);
      $string .= $chars[$pos];
    }
    return $string;
  }

}

$options = array();
$options['salt'] = array('name' => __('DB Security Salt', 'ads-ez'),
    'type' => 'hidden',
    'value' => randString(),
    'help' => __('Not visible to the end user', 'ads-ez'));
$options['badge_enable'] = array('name' => __('Enable Badge', 'ads-ez'),
    'help' => __('Ads EZ can show a small badge near the bottom left corner of your badges and ads, similar to traditional ad providers.', 'ads-ez'),
    'type' => 'checkbox',
    'value' => 0);
$options['badge_target'] = array('name' => __('Badge Target', 'ads-ez'),
    'value' => 'http://ads-ez.com/ads/',
    'help' => __('The URL target where your users will be taken to when they click on the badge.', 'ads-ez'));
$options['badge_short_text'] = array('name' => __('Badge Short Text', 'ads-ez'),
    'value' => 'EZ',
    'help' => __('The short text that is displayed as the badge. When the user hovers over the badge, it will expand to the long text.', 'ads-ez'));
$options['badge_long_text'] = array('name' => __('Badge Long Text', 'ads-ez'),
    'value' => 'Ads by Ads EZ',
    'help' => __('The long text that is displayed as the badge on mouseover. When the user hovers over the badge, it will expand from the short text to the long text.', 'ads-ez'));
$options['fallback_ad'] = array('name' => __('Fallback Ad', 'ads-ez'),
    'value' => 'fallback.php',
    'help' => __('If no ads can be found for the category and size requested, a fallback can be served. Please ensure that the fallback ad gracefully scales to various sizes. The default fallback ad (<code>fallback.php</code> in the installation folder) is a good starting point to design your own. If you need <a class="goPro" href="http://buy.thulasidas.com/customization" data-product="customization">customization</a> help, please contact the author.', 'ads-ez'));
