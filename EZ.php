<?php

require_once 'DbHelper.php';
require_once 'lib/PhpFastCache.php';
$cache = new PhpFastCache();
require_once 'lib/Logger.php';
$log = new Logger();

// Suppress errors on AJAX requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  error_reporting(E_ERROR | E_PARSE);
// CORS headers
  header("access-control-allow-origin: *", true);
  header("access-control-allow-methods: GET, POST, PUT, DELETE, OPTIONS", true);
}

if (!class_exists("EZ")) {

  class EZ {

    static $options = array();
    static $openx = array();
    static $salt = "";
    static $cacheTimeout = 600;
    static $isInstallingWP = false;
    static $isInWP = false;
    static $isPro = false;
    static $isUpdating = false;

    static function getAllAds() {
      global $db;
      $banners = $db->getData('banners');
      $bannerTargets = array();
      foreach ($banners as $k => $b) {
        $bannerTargets[$b['file']] = $b;
      }
      return $bannerTargets;
    }

    static function getCatId($name) { // Frontend version of getId with caching
      $key = "active-categories";
      $activeCategories = self::getTransient($key);
      if (!$activeCategories) {
        global $db;
        $rows = $db->getData('categories', array('id', 'name'), array('active' => 1));
        foreach ($rows as $r) {
          $activeCategories[$r['id']] = $r['name'];
        }
        self::setTransient($key, $activeCategories, self::$cacheTimeout);
      }
      $id = array_keys($activeCategories, $name);
      return $id[0];
    }

    static function getCatName($id) { // Frontend version of getId with caching
      $key = "active-categories";
      $activeCategories = self::getTransient($key);
      if (!$activeCategories) {
        global $db;
        $rows = $db->getData('categories', array('id', 'name'), array('active' => 1));
        foreach ($rows as $r) {
          $activeCategories[$r['id']] = $r['name'];
        }
        self::setTransient($key, $activeCategories, self::$cacheTimeout);
      }
      $name = '';
      if (!empty($activeCategories[$id])) {
        $name = $activeCategories[$id];
      }
      return $name;
    }

    static function catNameIsActive($name) {
      $id = self::getCatId($name);
      return !empty($id);
    }

    static function catIdIsActive($id) {
      $name = self::getCatName($name);
      return !empty($name);
    }

    static function getAdBySize($size = "300x250", $type = "", $activeOnly = false) {
      return self::getAdByCategory("", $type, $size, $activeOnly);
    }

    static function getAdByCategory($category = "", $type = "", $size = "300x250", $activeOnly = false) {
      $key = "banners-$category-$size";
      $banners = self::getTransient($key);
      if (!$banners) {
        global $db;
        $when = array('size' => $size);
        if ($activeOnly) {
          $when['active'] = 1;
        }
        if (!empty($category) && self::catNameIsActive($category)) {
          $catId = self::getCatId($category);
          $when['category'] = $catId;
        }
        $banners = $db->getData('banners', '*', $when);
        self::setTransient($key, $banners, self::$cacheTimeout);
      }
      $key = "htmlAds-$category-$size";
      $htmlAds = self::getTransient($key);
      if (!$htmlAds) {
        global $db;
        $when = array('size' => $size);
        if ($activeOnly) {
          $when['active'] = 1;
        }
        if (!empty($category) && self::catNameIsActive($category)) {
          $catId = self::getCatId($category);
          $when['category'] = $catId;
        }
        $htmlAds = $db->getData('htmlAds', '*', $when);
        self::setTransient($key, $htmlAds, self::$cacheTimeout);
      }
      if ($type == 'banners') {
        $ads = $banners;
      }
      else if ($type == 'htmlAds') {
        $ads = $htmlAds;
      }
      else {
        $ads = array_merge($banners, $htmlAds);
      }
      if (empty($ads)) {
        // @trigger_error("No banners found for " . print_r($when, true), E_USER_ERROR);
      }
      else {
        $rand = array_rand($ads);
        return $ads[$rand];
      }
    }

    static function getTargets() {
      $key = 'targets';
      $targets = self::getTransient($key);
      if (!$targets) {
        global $db;
        $targets = $db->getColData('banners', 'target');
        self::setTransient($key, $targets, self::$cacheTimeout);
      }
      return $targets;
    }

    static function md5($password) {
      return md5($password . self::$salt);
    }

    static function authenticate() {
      global $db;
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!empty($_POST['myusername']) && !empty($_POST['mypassword'])) {
          $myusername = $_POST['myusername'];
          $mypassword = $_POST['mypassword'];
          $mypassword = self::md5($mypassword);
          $result = $db->getData('administrator', '*', "username='$myusername' and password='$mypassword'");
          $count = count($result);
          // If result matches $myusername and $mypassword, table row must be 1 row
          if ($count == "1") {
            $row = $result[0];
          }
          else {
            $row = 1;
          }
        }
        else {
          $row = 2;
        }
      }
      return $row;
    }

    static function login() {
      if (!session_id()) {
        session_start();
      }
      $row = self::authenticate();
      if (is_array($row)) {
        $_SESSION['ads-ez-admin'] = self::md5($row['username']);
        $_SESSION['ads-ez-password'] = self::md5($row['password']);
        session_write_close();
        header("location: index.php");
      }
      else {
        $error = $row;
        header("location: login.php?error=$error");
        exit();
      }
    }

    static function logout() {
      session_start();
      session_unset();
      session_destroy();
      session_write_close();
      setcookie(session_name(), '', 0, '/');
      session_regenerate_id(true);
      header("Location: login.php?error=3");
      exit();
    }

    static function isActive() {
      if (!function_exists('is_plugin_active')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
      }
      $plgSlug = basename(dirname(__FILE__)) . "/ads-ez.php";
      return is_plugin_active($plgSlug);
    }

    static function isInWP() {
      self::$isInWP = false;
      if (isset($_REQUEST['wp'])) {
        self::$isInWP = true;
        return true;
      }
      if (function_exists('is_user_logged_in')) {
        self::$isInWP = true;
        return true;
      }
      foreach (array("../../..", "../../../..", "../../../../..") as $dir) {
        $wpHeader = "$dir/wp-blog-header.php";
      if (@file_exists($wpHeader)) {
        self::$isInWP = true;
        return true;
      }
      }
      return self::$isInWP;
    }

    static function isLoggedInWP() {
      // check from front-end, admin and ajax
      foreach (array("../../..", "../../../..", "../../../../..") as $dir) {
        $wpHeader = "$dir/wp-blog-header.php";
        if (@file_exists($wpHeader)) {
          require_once $wpHeader;
          break;
        }
      }
      if (function_exists('is_user_logged_in')) {
        self::$isInWP = true;
        if (is_user_logged_in()) {
          return true;
        }
        else {
          return false;
        }
      }
    }

    static function isLoggedIn() {
      if (!session_id()) {
        session_start();
        session_write_close();
      }
      if (self::isLoggedInWP()) {
        return true;
      }
      else {
        if (self::$isInWP) {
          return false;
        }
      }
      if (empty($_SESSION['ads-ez-admin'])) {
        return false;
      }
      if (empty($_SESSION['ads-ez-password'])) {
        return false;
      }
      global $db;
      $result = $db->getData('administrator', '*');
      $row = $result[0];
      $admin = self::md5($row['username']);
      $password = self::md5($row['password']);
      $isLoggedin = $_SESSION['ads-ez-admin'] == $admin &&
              $_SESSION['ads-ez-password'] == $password;
      if (!$isLoggedin) {
        self::logout();
      }
      return $isLoggedin;
    }

    static function urlExists($url) {//se passar a URL existe
      $c = curl_init();
      curl_setopt($c, CURLOPT_URL, $url);
      curl_setopt($c, CURLOPT_HEADER, 1); //get the header
      curl_setopt($c, CURLOPT_NOBODY, 1); //and *only* get the header
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); //get the response as a string from curl_exec(), rather than echoing it
      curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); //don't use a cached version of the url
      if (!curl_exec($c)) {
        return false;
      }
      else {
        return true;
      }
      //$httpcode=curl_getinfo($c,CURLINFO_HTTP_CODE);
      //return ($httpcode<400);
    }

    static function validate_url($url) {
      $format = "Use the format http[s]://[www].site.com[/file[?p=v]]";
      if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $text = "$format";
        return $text;
      }
      $pattern = '#^(http(?:s)?\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$#';
      if (!preg_match($pattern, $url)) {
        $text = "$format";
        return $text;
      }
      if (!self::urlExists($url)) {
        $text = "URL not accessible";
        return $text;
      }
      return true;
    }

    static function validate_email($s) {
      if (!filter_var($s, FILTER_VALIDATE_EMAIL)) {
        return "Bad email address";
      }
      return true;
    }

    static function validate_notNull($s) {
      $s = trim($s);
      if (empty($s)) {
        return "Null value not allowed";
      }
      return true;
    }

    static function validate_number($s) {
      if (!is_numeric($s)) {
        return "Need a number here";
      }
      return true;
    }

    static function validate_alnum($s) {
      $aValid = array('_', '-');
      $s = str_replace($aValid, '', $s);
      if (!ctype_alnum($s)) {
        return "Please use only letters, numbers, - and _";
      }
      return true;
    }

    // AJAX CRUD implementation. Create.
    static function create($table) { // creates a new DB record
      if (!EZ::isLoggedIn()) {
        http_response_code(400);
        die("Please login before modifying $table!");
      }
      global $db;
      if (!$db->tableExists($table)) {
        http_response_code(400);
        die("Wrong table name: $table!");
      }
      $row = $_REQUEST;
      if (!empty($row['pk'])) {
        http_response_code(400);
        die("Primary key supplied for new record");
      }
      unset($row['id']);
      if (empty($row)) {
        http_response_code(400);
        die("Empty data");
      }
      switch ($table) {
        case 'banners':
          $file = $row['file'];
          if ($row['target'] == 'Empty' || empty($row['target'])) {
            http_response_code(400);
            die("Empty target for $file!");
          }
          if ($row['category'] == 'Empty' || empty($row['category'])) {
            http_response_code(400);
            die("Empty category for $file!");
          }
          if ($row['title'] == 'Empty') {
            $row['title'] = "";
          }
          $row['md5'] = md5($row['file']);
          $id = self::getId('categories', array('name' => $row['category']));
          $row['category'] = $id;
          $row['size'] = "{$row['width']}x{$row['height']}";
          break;
        case 'htmlAds':
          if ($row['name'] == 'Empty' || empty($row['name'])) {
            http_response_code(400);
            die("Empty name!");
          }
          if ($row['category'] == 'Empty' || empty($row['category'])) {
            http_response_code(400);
            die("Empty category!");
          }
          if ($row['html'] == 'Empty' || empty($row['html'])) {
            http_response_code(400);
            die("Empty HTML code!");
          }
          if ($row['height'] == 'Empty' || empty($row['height'])) {
            http_response_code(400);
            die("Height not specified!");
          }
          if ($row['width'] == 'Empty' || empty($row['width'])) {
            http_response_code(400);
            die("Width not specified!");
          }
          $id = self::getId('categories', array('name' => $row['category']));
          $row['category'] = $id;
          $row['size'] = "{$row['width']}x{$row['height']}";
          break;
        case 'categories':
          if ($row['name'] == 'Empty' || empty($row['name'])) {
            http_response_code(400);
            die("Empty name!");
          }
          break;
        case 'openx':
          break;
        default:
          http_response_code(400);
          die("Unknown table accessed: $table");
      }
      if (isset($row['active']) && trim($row['active']) == 'Active') {
        $row['active'] = 1;
      }
      else {
        $row['active'] = 0;
      }
      $lastInsertId = $db->getInsertId();
      if (!$db->putRowData($table, $row)) {
        http_response_code(400);
        die("Database Insert Error in $table!");
      }
      $newInserId = $db->getInsertId();
      if ($lastInsertId == $newInserId) {
        http_response_code(400);
        die("Database Insert Error in $table, duplicate unique key!");
      }
      http_response_code(200);
      return $newInserId;
    }

    // AJAX CRUD implementation. Delete.
    static function read() {
      // not implemented because $db->getData() does a decent job of it
    }

    // AJAX CRUD implementation. Update.
    static function update($table, $meta = false) { // updates an existing DB record
      if (!EZ::isLoggedIn()) {
        http_response_code(400);
        die("Please login before modifying $table!");
      }
      global $db;
      if (!$db->tableExists($table)) {
        http_response_code(400);
        die("Wrong table name: $table!");
      }
      $row = array();
      extract($_POST, EXTR_PREFIX_ALL, 'posted');
      if (empty($posted_pk)) {
        http_response_code(400);
        die("Empty primary key");
      }
      if (empty($posted_name)) {
        http_response_code(400);
        die("Empty name ($posted_name) in data");
      }
      if (!isset($posted_value)) { // Checkbox, unchecked
        $posted_value = 0;
      }
      if (is_array($posted_value)) { // Checkbox (from checklist), checked
        $posted_value = 1;
      }
      if (!empty($posted_validator)) { // a server-side validator is specified
        $fun = "validate_$posted_validator";
        if (method_exists('EZ', $fun)) {
          $valid = self::$fun($posted_value);
        }
        else {
          http_response_code(400);
          die("Unknown validator ($posted_validator) specified");
        }
        if ($valid !== true) {
          http_response_code(400);
          die("$valid");
        }
      }
      if ($meta) {
        $row[$posted_pk] = $posted_value;
        $status = $db->putMetaData($table, $row);
      }
      else {
        $row['id'] = $posted_pk;
        $row[$posted_name] = $posted_value;
        $status = $db->putRowData($table, $row);
      }
      if (!$status) {
        http_response_code(400);
        die("Database Insert Error in $table!");
      }
      http_response_code(200);
      echo $posted_pk;
      exit();
    }

    static function mkCatNames($showInactive = false) {
      global $db;
      $catNames = array();
      $categories = $db->getData('categories', '*');
      foreach ($categories as $cat) {
        extract($cat);
        if ($active || $showInactive) {
          $catNames[$id] = $name;
        }
        else {
          $catNames[$id] = 'Inactive';
        }
      }
      return $catNames;
    }

    static function mkCatSource($showInactive = false) {
      global $db;
      $catSource = "[";
      $categories = $db->getData('categories', '*');
      foreach ($categories as $cat) {
        extract($cat);
        if ($active || $showInactive) {
          $catSource .= "{value: '$id', text: '$name'},";
        }
      }
      $catSource .= "]";
      return $catSource;
    }

    static function mkSelectSource($options) {
      $source = "[";
      foreach ($options as $o) {
        $source .= "{value: '$o', text: '$o'},";
      }
      $source .= "]";
      return $source;
    }

    // AJAX CRUD implementation. Delete.
    static function delete() {

    }

    static function getId($table, $when) {
      global $db;
      $row = $db->getData($table, 'id', $when);
      return $row[0]['id'];
    }

    static function getOptions() {
      if (!empty(self::$options)) {
        return self::$options;
      }
      global $db;
      if ($db->tableExists('options_meta')) {
        self::$options = $db->getMetaData('options_meta');
      }
      return self::$options;
    }

    static function getOpenX() {
      if (!empty(self::$openx)) {
        return self::$openx;
      }
      $key = "openx";
      $openx = self::getTransient($key);
      if (!$openx) {
        global $db;
        if ($db->tableExists('openx')) {
          $openx = $db->getColData2('openx', 'zoneid', 'size', array('active' => 1));
        }
        self::$openx = $openx;
        self::setTransient($key, $openx, self::$cacheTimeout);
      }
      return $openx;
    }

    static function putDefaultOptions($options) {
      global $db;
      $row = array();
      foreach ($options as $k => $o) {
        $row[$k] = $o['value'];
      }
      $rowDB = $db->getMetaData('options_meta');
      $row = array_merge($row, $rowDB);
      $db->putMetaData('options_meta', $row);
    }

    static function renderOption($pk, $option) {
      self::rmTransient('options');
      $optionsDB = EZ::getOptions();
      if (isset($optionsDB[$pk])) {
        $value = $optionsDB[$pk];
        $option['value'] = $value;
      }
      return self::renderRow($pk, $option);
    }

    static function renderRow($pk, $option) {
      $value = "";
      $type = 'text';
      $more_help = "";
      $dataValue = "";
      $dataTpl = "";
      $dataMode = "data-mode='inline'";
      $dataSource = "";
      extract($option);
      if ($type == 'hidden') {
        $tr = '';
        return $tr;
      }
      $dataType = "data-type='$type'";
      if (!empty($more_help)) {
        $clickHelp = "class='btn-help'";
      }
      else {
        $clickHelp = '';
      }
      $tr = "<tr><td>$name</td>";
      switch ($type) {
        case 'no-edit':
          $class = "black";
          break;
        case 'checkbox' :
          $class = "xedit-checkbox";
          $dataType = "data-type='checklist'";
          $dataValue = "data-value='$value'";
          if ($value) {
            $class .= ' btn-sm btn-success';
            $value = "";
          }
          else {
            $class .= ' btn-sm btn-danger';
            $value = "";
          }
          break;
        case 'category':
          $class = "xedit";
          $dataType = "data-type='select'";
          $dataValue = "data-value='$value'";
          if (!empty($value)) {
            $value = self::getCatName($value);
          }
          $dataSource = 'data-source="' . self::mkCatSource() . '"';
          break;
        case 'select':
          $class = "xedit";
          $dataType = "data-type='select'";
          $dataValue = "data-value='$value'";
          $dataSource = 'data-source="' . self::mkSelectSource($options) . '"';
          break;
        case 'file': // special case, return from here
          $type = '';
          $dataTpl = '';
          $class = 'red';
          $value = "<input data-pk='$pk' id='fileinput' type='file' class='file' data-show-preview='false' data-show-upload='false'>";
          break;
        case 'submit':
        case 'button':
          $class = "btn btn-primary btn-ez";
          break;
        case 'dbselect':
        case 'select':
        case 'dbeditableselect':
        case 'editableselect':
        case 'text':
        case 'textarea':
        default :
          $class = "xedit";
          if ($dataTpl == 'none') {
            $dataTpl = '';
          }
          else {
            $dataTpl = "data-tpl='<input type=\"text\" style=\"width:450px\">'";
          }
          break;
      }
      if (!empty($validator)) {
        $valid = "data-validator='$validator'";
      }
      else {
        $valid = "";
      }
      if (empty($slug)) {
        $slug = "$pk-value";
      }
      if (!empty($button)) {
        $fun = "proc_$reveal";
        $options = self::$options;
        if (!empty($options[$reveal])) {
          $revealOption = $options[$reveal];
        }
        else {
          $revealOption = '';
        }
        if (method_exists("EZ", $fun)) {
          $dataReveal = @self::$fun($revealOption);
        }
        else {
          $dataReveal = "data-value='$revealOption' class='btn-sm btn-success reveal'";
        }
        $reveal = "<a href='#' style='float:right' $dataReveal>$button</a>";
      }
      else {
        $reveal = '';
      }
      $tr .= "\n<td style='width:70%'><a id='$slug' class='$class' data-name='$slug' data-pk='$pk' $dataType $dataTpl $dataMode $dataValue $dataSource $valid>$value $reveal</a></td>\n<td class='center-text'><a style='font-size:1.5em' data-content='$help' data-help='$more_help' data-toggle='popover' data-placement='left' data-trigger='hover' title='$name' $clickHelp><i class='glyphicon glyphicon-question-sign blue'></i></a></td></tr>\n";
      if ($type == 'hidden') {
        $tr = '';
      }
      return $tr;
    }

    static function isUpdateAvailable() { // not ready yet
      return false;
    }

    static function randString($len = 32) {
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

    static function flashMsg($msg, $class, $noflash = false) {
      if ($noflash) {
        $fun = "show";
      }
      else {
        $fun = "flash";
      }
      $msg = str_replace(array("\n", "<pre>", "</pre>"), '', $msg);
      if (!empty($msg)) {
        $msg = str_replace("\n", '\n', $msg);
        echo '<script>$(document).ready(function() {' .
        $fun . $class . '("' . $msg . '");
        });
        </script>';
      }
    }

    static function flashError($msg) {
      self::flashMsg($msg, 'Error');
    }

    static function showError($msg) {
      self::flashMsg($msg, 'Error', true);
    }

    static function flashWarning($msg) {
      self::flashMsg($msg, 'Warning');
    }

    static function showWarning($msg) {
      self::flashMsg($msg, 'Warning', true);
    }

    static function flashSuccess($msg) {
      self::flashMsg($msg, 'Success');
    }

    static function showSuccess($msg) {
      self::flashMsg($msg, 'Success', true);
    }

    static function flashInfo($msg) {
      self::flashMsg($msg, 'Info');
    }

    static function showInfo($msg) {
      self::flashMsg($msg, 'Info', true);
    }

    static function toggleMenu($header) {
      $options = EZ::getOptions();
      if (!empty($options['menu_placement'])) {
        $menuPlacement = $options['menu_placement'];
      }
      else {
        $menuPlacement = 'Auto';
      }
      if (self::$isInWP) { // standalone?
        $standAlone = !isset($_REQUEST['inframe']) && (@strpos($_SERVER["HTTP_REFERER"], 'wp-admin/options-general.php') === false);
      }
      else {
        $standAlone = true;
      }
      $topMenu = $menuPlacement == 'Top' || ($menuPlacement == 'Auto' && !$standAlone);
      if ($topMenu) {
        $search = array('<div class="col-sm-2 col-lg-2">',
            '<div class="sidebar-nav">',
            '<div class="nav-canvas">',
            '<ul class="nav nav-pills nav-stacked main-menu">',
            '<li class="accordion">',
            '<ul class="nav nav-pills nav-stacked">',
            '<a href="#">',
            '<div id="content" class="col-lg-10 col-sm-10">');
        $replace = array('<div>',
            '<div>',
            '<div>',
            '<ul class="nav nav-pills main-menu">',
            '<li class="dropdown">',
            '<ul class="dropdown-menu">',
            '<a href="#" data-toggle="dropdown">',
            '<div id="content" class="col-lg-12 col-sm-12">');
        $header = str_replace($search, $replace, $header);
      }
      return $header;
    }

    static function showService() {
      $select = rand(0, 4);
      echo "<div class='pull-right' style='margin-left:10px;'><a href='http://www.thulasidas.com/professional-php-services/' target='_blank' title='Professional Services' data-content='The author of this plugin may be able to help you with your WordPress or plugin customization needs and other PHP related development. Find a plugin that almost, but not quite, does what you are looking for? Need any other professional PHP/jQuery dev services? Click here!' data-toggle='popover' data-trigger='hover' data-placement='left'><img src='img/svcs/300x250-0$select.jpg' border='0' alt='Professional Services from the Plugin Author' /></a></div>";
    }

    static function setTransient($key, $val, $timeout = 0) {
      $key = 'ads-ez-' . $key;
      if (empty($timeout)) {
        $timeout = self::$cacheTimeout;
      }
      if (function_exists('set_transient')) {
        return set_transient($key, $val, $timeout);
      }
      else {
        global $cache;
        return $cache->set($key, $val, $timeout);
      }
    }

    static function getTransient($key) {
      $key = 'ads-ez-' . $key;
      if (function_exists('get_transient')) {
        return get_transient($key);
      }
      else {
        global $cache;
        return $cache->get($key);
      }
    }

    static function rmTransient($key) {
      $key = 'ads-ez-' . $key;
      global $cache;
      return $cache->delete($key);
    }

  }

}

EZ::$isInWP = isset($_REQUEST['wp']);
EZ::$isUpdating = isset($_REQUEST['update']);
EZ::$isPro = file_exists('options-advanced.php');

// construct DB object after defining EZ
$db = new DbHelper();

if (!EZ::isLoggedInWP()) {
  require_once 'admin/lang.php';
}

EZ::$options = EZ::getOptions(); // to prime the static variable and the cache
if (!empty(EZ::$options['salt'])) {
  EZ::$salt = EZ::$options['salt'];
}
if (!empty(EZ::$options['cache_timeout'])) {
  EZ::$cacheTimeout = EZ::$options['cache_timeout'];
}

// For 4.3.0 <= PHP <= 5.4.0
if (!function_exists('http_response_code')) {

  function http_response_code($newcode = NULL) {
    static $code = 200;
    if ($newcode !== NULL) {
      header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
      if (!headers_sent()) {
        $code = $newcode;
      }
    }
    return $code;
  }

}
