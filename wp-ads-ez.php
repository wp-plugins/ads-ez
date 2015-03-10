<?php

if (!class_exists("AdsEZ")) {

  class AdsEZ {

    var $isPro, $strPro, $plgDir, $plgURL;
    var $ezTran, $domain;

    function AdsEZ() { //constructor
      $this->plgDir = dirname(__FILE__);
      $this->plgURL = plugin_dir_url(__FILE__);
      $this->isPro = file_exists("{$this->plgDir}/admin/options-advanced.php");
      $this->strPro = ' Lite';
      if ($this->isPro) {
        $this->strPro = ' Pro';
      }
      if (is_admin()) {
        require_once($this->plgDir . '/EzTran.php');
        $this->domain = 'ads-ez';
        $this->ezTran = new EzTran(__FILE__, "Ads EZ{$this->strPro}", $this->domain);
        $this->ezTran->setLang();
      }
    }

    function displayAd($atts, $content = '') {
      $query = "?wp";
      $vars = array("cat" => "", "type" => "banner", "size" => "300x250");
      $vars = shortcode_atts($vars, $atts);
      foreach ($vars as $k => $v) {
        if (!empty($v)) {
          $query .= "&$k=$v";
        }
      }
      list($w, $h) = explode("x", $vars['size']);
      $adLink = "<iframe src='{$this->plgURL}ad.php$query' frameborder='0 scrolling='no' width='$w' height='$h'></iframe>";
      return $adLink;
    }

    static function install() {
      $mOptions = "adsEz";
      $ezOptions = get_option($mOptions);
      if (empty($ezOptions)) {
        // create the necessary tables
        $isInstallingWP = true;
        chdir(dirname(__FILE__) . '/admin');
        require_once('dbSetup.php');
        $ezOptions['isSetup'] = true;
      }
      update_option($mOptions, $ezOptions);
    }

    static function uninstall() {
      $mOptions = "adsEz";
      delete_option($mOptions);
    }

    function printAdminPage() {
      $src = plugins_url("admin/index.php", __FILE__);
      ?>
      <script type="text/javascript">
        function calcHeight() {
          var w = window,
                  d = document,
                  e = d.documentElement,
                  g = d.getElementsByTagName('body')[0],
                  y = w.innerHeight || e.clientHeight || g.clientHeight;
          document.getElementById('the_iframe').height = y - 70;
        }
        if (window.addEventListener) {
          window.addEventListener('resize', calcHeight, false);
        }
        else if (window.attachEvent) {
          window.attachEvent('onresize', calcHeight)
        }
      </script>
      <?php

      echo "<iframe src='$src' frameborder='0' style='overflow:hidden;overflow-x:hidden;overflow-y:hidden;width:100%;position:absolute;top:5px;left:-10px;right:0px;bottom:0px' width='100%' height='900px' id='the_iframe' onLoad='calcHeight();'></iframe>";
    }

  }

} //End Class AdsEZ

if (class_exists("AdsEZ")) {
  $adsEz = new AdsEZ();

  add_action('admin_menu', 'adsEz_admin_menu');
  add_shortcode('adsez', array($adsEz, 'displayAd'));
  add_shortcode('adsEz', array($adsEz, 'displayAd'));
  add_shortcode('ads-ez', array($adsEz, 'displayAd'));

  if (!function_exists('adsEz_admin_menu')) {

    function adsEz_admin_menu() {
      global $adsEz;
      $mName = 'Ads EZ ' . $adsEz->strPro;
      add_options_page($mName, $mName, 'activate_plugins', basename(__FILE__), array($adsEz, 'printAdminPage'));
    }

  }

  $file = dirname(__FILE__) . '/ads-ez.php';
  register_activation_hook($file, array("AdsEZ", 'install'));
  register_deactivation_hook($file, array("AdsEZ", 'uninstall'));
}

