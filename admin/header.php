<?php
error_reporting(E_ALL);

if (empty($no_visible_elements)) {
  require_once 'lock.php';
}

include_once('../debug.php');

function insertAlerts($width = 10) {
  ?>
  <div style="display:none" class="alert alert-info col-lg-<?php echo $width; ?>" role="alert">
    <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <span id="alertInfoText"></span>
  </div>
  <div style="display:none" class="alert alert-success col-lg-<?php echo $width; ?>" role="alert">
    <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <span id="alertSuccessText"></span>
  </div>
  <div style="display:none" class="alert alert-warning col-lg-<?php echo $width; ?>" role="alert">
    <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <span id="alertWarningText"></span>
  </div>
  <div style="display:none" class="alert alert-danger col-lg-<?php echo $width; ?>" role="alert">
    <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <span id="alertErrorText"></span>
  </div>
  <?php
}

function openRow($help = "") {
  if (empty($help)) {
    $help = "You can roll-up or temporarily suppress this box. For more help, click on the friendly Help button near the top right corner of this page, if there is one.";
  }
  else {
    ?>
    <a href="#" class="btn btn-primary btn-help" style="float:right" data-content="<?php echo $help; ?>"><i class="glyphicon glyphicon-question-sign large"></i> Help</a>
  <?php }
  ?>
  <div class="row">
    <?php
    return $help;
  }

  function closeRow() {
    ?>
  </div><!-- row -->
  <?php
}

function openCell($title, $icon = "edit", $size = "12", $help = "") {
  if (empty($help)) {
    $help = "You can roll-up or temporarily suppress this box. For more help, click on the friendly Help button near the top right corner of this page, if there is one.";
  }
  ?>
  <div class="box col-md-<?php echo $size; ?>">
    <div class="box-inner">
      <div class="box-header well" data-original-title="">
        <h2>
          <i class="glyphicon glyphicon-<?php echo $icon; ?>"></i>
          <?php echo $title; ?>
        </h2>
        <div class="box-icon">
          <a href="#" class="btn btn-help btn-round btn-default"
             data-content="<?php echo $help; ?>">
            <i class="glyphicon glyphicon-question-sign"></i>
          </a>
          <a href="#" class="btn btn-minimize btn-round btn-default">
            <i class="glyphicon glyphicon-chevron-up"></i>
          </a>
          <a href="#" class="btn btn-close btn-round btn-default">
            <i class="glyphicon glyphicon-remove"></i>
          </a>
        </div>
      </div>
      <div class="box-content">
        <?php
      }

      function closeCell() {
        ?>
      </div>
    </div>
  </div><!-- box -->
  <?php
}

function openBox($title, $icon = "edit", $size = "12", $help = "") {
  $help = openRow($help);
  openCell($title, $icon, $size, $help);
}

function closeBox() {
  closeCell();
  closeRow();
}

function showScreenshot($id) {
  $img = "../screenshot-$id.png";
  $iSize = getimagesize($img);
  $width = $iSize[0] . 'px';
  echo "<img src='$img' alt='screenshot' class='col-sm-12' style='max-width:$width'>";
}

function getHeader() {
  http_response_code(200);
  global $no_visible_elements;
  if (class_exists('EZ') && property_exists('EZ', 'isPro')) {
    $isPro = EZ::$isPro;
  }
  else {
    $isPro = false;
  }
  if (class_exists('EZ') && !empty(EZ::$options['theme'])) {
    $themeCSS = "css/bootstrap-" . strtolower(EZ::$options['theme']) . ".min.css";
  }
  else {
    $themeCSS = "css/bootstrap-cerulean.min.css";
  }
  ?>
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <title>Ads EZ - Your Personal Ad Server</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="Ads EZ - Your Personal Ad Server.">
      <meta name="author" content="Manoj Thulasidas">

      <!-- The styles -->
      <link id="bs-css" href="<?php echo $themeCSS; ?>" rel="stylesheet">
      <link href="css/bootstrap-editable.css" rel="stylesheet">
      <link href="css/bootstrap.lightbox.css" rel="stylesheet" media="screen">
      <link href="css/charisma-app.css" rel="stylesheet">
      <link href='css/bootstrap-tour.min.css' rel='stylesheet'>
      <link href='css/bootstrapValidator.css' rel='stylesheet'>
      <link href='css/dropzone.css' rel='stylesheet'>
      <link href="css/summernote.css" rel="stylesheet">
      <link href="css/font-awesome.min.css" rel="stylesheet">
      <link href="css/dataTables.bootstrap.css" rel="stylesheet">
      <link href="css/fileinput.min.css" rel="stylesheet">
      <style type="text/css">
        .popover{width:600px;}
        <?php
        if (class_exists('EZ') && empty(EZ::$options['breadcrumbs'])) {
          ?>
          .breadcrumb {display:none;}
          <?php
        }
        ?>
      </style>
      <!-- jQuery -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

      <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->

      <!-- The fav icon -->
      <link rel="shortcut icon" href="img/favicon.ico">

    </head>

    <body>
  <?php if (empty($no_visible_elements)) { ?>
        <!-- topbar starts -->
        <div class="navbar navbar-default" role="navigation">

          <div class="navbar-inner">
            <a id="index" class="navbar-brand" href="index.php"> <img alt="Ads EZ Logo" src="img/ads-ez.png" class="hidden-xs"/>
              <span>Your Own Ad Server</span></a>
            <div class="btn-group pull-right">
              <?php
              if (!EZ::$isInWP) {
                ?>

                <!-- user dropdown starts -->
                <button id="account" class="btn btn-default dropdown-toggle pull-right" data-toggle="dropdown">
                  <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs"> admin</span>
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="profile.php">Profile</a></li>
                  <li class="divider"></li>
                  <li><a href="login.php?logout">Logout</a></li>
                </ul>
                <!-- user dropdown ends -->
                <?php
              }
              else {
                $standaloneURL = plugins_url('index.php', __FILE__);
                ?>
                <a id="standAloneMode" href="<?php echo $standaloneURL; ?>" target="_blank" data-content="Open Ads EZ Admin in a new window independent of WordPress admin interface. The standalone mode still uses WP authentication, and cannot be accessed unless logged in." data-toggle="popover" data-trigger="hover" data-placement="left"  title='Standalone Admin Screen'><span class="btn btn-info"><i class="glyphicon glyphicon-resize-full"></i> Standalone Mode</span></a>
                <?php
              }
              ?>
              <a id="update" href="update.php" data-content="If you would like to check for regular updates, or install a purchased module or Pro upgrade, visit the update page." data-toggle="popover" data-trigger="hover" data-placement="left" title='Update Page'><span class="btn btn-info"><i class="fa fa-cog fa-spin"></i> Updates
                  <?php
                  if (!$isPro) {
                    ?>
                    &nbsp;<span class="badge red">Pro</span>
                    <?php
                  }
                  ?>
                </span>
              </a>&nbsp;
            </div>
          </div>
        </div>
        <!-- topbar ends -->
  <?php } ?>
      <div class="ch-container">
        <div class="row">
          <?php
          if (empty($no_visible_elements)) {
            ob_start();
            ?>
            <!-- left menu starts -->
            <div class="col-sm-2 col-lg-2">
              <div class="sidebar-nav">
                <div class="nav-canvas">
                  <div class="nav-sm nav nav-stacked">

                  </div>
                  <ul class="nav nav-pills nav-stacked main-menu">
                    <li id="dashboard"><a href="index.php"><i class="glyphicon glyphicon-home"></i><span> Dashboard</span></a>
                    </li>
                    <?php
                    if (!$isPro) {
                      ?>
                      <li id='goPro'><a href="pro.php" class="red goPro" data-toggle="popover" data-trigger="hover" data-content="Get the Pro version of this app for <i>only</i> $20.95. Tons of extra features. Instant download." data-placement="right" title="Upgrade to Pro"><i class="glyphicon glyphicon-shopping-cart"></i><span><b> Go Pro!</b></span></a></li>
                      <?php
                    }
                    ?>
                    <li class="accordion">
                      <a href="banners.php"><i class="glyphicon glyphicon-plus"></i><span> Banners</span></a>
                      <ul class="nav nav-pills nav-stacked">
                        <li id='banners'><a class="ajax-link" href="banners.php"><i class="glyphicon glyphicon-th-list"></i><span> All Banners</span></a></li>
                        <li id="banners-edit"><a class="ajax-link" href="banners-edit.php"><i class="glyphicon glyphicon-pencil"></i><span> Edit Banners</span></a></li>
                        <li id="banners-batch"><a class="ajax-link" href="banners-batch.php"><i class="glyphicon glyphicon-repeat"></i><span> Batch Process</span></a></li>
                        <li id="banners-new"><a class="ajax-link" href="banners-new.php"><i class="glyphicon glyphicon-flag red"></i><span> New Banners</span></a></li>
                      </ul>
                    </li>
                    <li class="accordion">
                      <a href="htmlAds.php"><i class="glyphicon glyphicon-plus"></i><span> HTML Ads</span></a>
                      <ul class="nav nav-pills nav-stacked">
                        <li id="htmlAds"><a class="ajax-link" href="htmlAds.php"><i class="glyphicon glyphicon-th-large red"></i><span> All HTML Ads</span></a></li>
                        <li id="htmlAds-edit"><a class="ajax-link" href="htmlAds-edit.php"><i class="glyphicon glyphicon-plus red"></i><span> New HTML Ad</span></a></li>
                      </ul>
                    </li>
                    <li class="accordion">
                      <a href="categories.php"><i class="glyphicon glyphicon-plus"></i><span> Categories &amp; Stats</span></a>
                      <ul class="nav nav-pills nav-stacked">
                        <li id="categories"><a class="ajax-link" href="categories.php"><i class="glyphicon glyphicon-folder-open"></i><span> Categories</span></a></li>
                        <li id="categories-new"><a class="ajax-link" href="categories-new.php"><i class="glyphicon glyphicon-plus red"></i><span> New Category</span></a></li>
                        <li id="stats"><a class="ajax-link" href="stats.php"><i class="glyphicon glyphicon-stats red"></i><span> Statistics</span></a></li>
                      </ul>
                    </li>
                    <li class="accordion">
                      <a href="invocation.php"><i class="glyphicon glyphicon-plus"></i><span> Integration</span></a>
                      <ul class="nav nav-pills nav-stacked">
                        <li id="invocation"><a class="ajax-link" href="invocation.php"><i class="glyphicon glyphicon-picture"></i><span> <b>Invocation Codes</b></span></a></li>
                        <li id="openx"><a class="ajax-link" href="openx.php"><i class="glyphicon glyphicon-trash red"></i><span> OpenX Replacement</span></a></li>
                      </ul>
                    </li>
                    <li class="accordion">
                      <a href="options.php"><i class="glyphicon glyphicon-plus"></i><span> Configuration</span></a>
                      <ul class="nav nav-pills nav-stacked">
                        <li id="options"><a href="options.php"><i class="glyphicon glyphicon-cog"></i><span> Options</span></a>
                        <li id="advanced"><a class="ajax-link" href="advanced.php"><i class="glyphicon glyphicon-cog red"></i><span> Advanced Tools</span></a></li>
                      </ul>
                    </li>
                    <?php
                    if (!EZ::$isInWP) {
                      ?>
                      <li class="accordion">
                        <a href="profile.php"><i class="glyphicon glyphicon-plus"></i><span> Your Account</span></a>
                        <ul class="nav nav-pills nav-stacked">
                          <li id="profile"><a href="profile.php"><i class="glyphicon glyphicon-lock"></i><span> Your Profile</span></a>
                          </li>
                          <li id="logout"><a href="login.php?logout"><i class="glyphicon glyphicon-ban-circle"></i><span> Logout</span></a>
                        </ul>
                      </li>
                      <?php
                    }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
            <!--/span-->
            <!-- left menu ends -->

            <noscript>
            <div class="alert alert-block col-md-12">
              <h4 class="alert-heading">Warning!</h4>

              <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                enabled to use this site.</p>
            </div>
            </noscript>

            <div id="content" class="col-lg-10 col-sm-10">
              <!-- content starts -->
              <?php
              if (EZ::isUpdateAvailable()) {
                ?>
                <div class="alert alert-info">
                  <a href="#" class="close" data-dismiss="alert">&times;</a>
                  <strong>Updates Available!</strong> Please update your Ads EZ server.
                </div>
                <?php
              }
            }
            $header = ob_get_clean();
            return $header;
          }

          $header = getHeader();
          if (method_exists('EZ', 'toggleMenu')) {
            $header = EZ::toggleMenu($header);
          }
          echo $header;
