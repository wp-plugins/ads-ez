<?php
$cfgDir = dirname(dirname(__FILE__));
?>
<div class="col-lg-8 col-sm-12">
  <h4>Quick Start</h4>
  <ul>
    <li><strong><a href='banners-new.php'>Upload your banners</a></strong> to the <code>banners</code> folder (<code><?php echo $cfgDir; ?>/banners</code>) on your server and hit the <b><a href="banners-batch.php">Batch Process</a></b> menu item to provide banner data.</li>
    <li>Get the <strong><a href="invocation.php">invocation codes</a></strong> and place them on your websites to start serving ads.</li>
    <li>Take a <strong><a class="restart" href="#">tour</a></strong> any time you would like to go through the application features.</li>
  </ul>
  <h4>WordPress and Shortcodes</h4>
  <p>If you are using the Ads EZ ad server as a WordPress plugin, you can use <a href='http://codex.wordpress.org/Shortcode' target='_blank' class='popup-long'>shortcodes</a> to place your ads on your posts and pages. Use the shortcode <code>[adsez]</code>,<code>[ads-ez]</code> or <code>[adsZz]</code>.</p>
  <p>The supported parameters are <code>type</code> (which can be <code>banner</code> or <code>html</code>), <code>size</code> (ad size in the format <code>width x height</code>) and <code>cat</code> (ad category). To see the sizes and categories available, please visit the <a href='invocation.php'>Invocation</a> page.</p>

  <h4>Context-Aware Help</h4>
  <p>Most of the admin pages of this application have a blue help button near the right hand side top corner. Clicking on it will give instructions and help specific to the task you are working on. All configuration options have a help button associated with it, which gives you a popover help bubble when you hover over it. Finally, almost every button in the admin interface has popover help associated with it. If you need further assistance, please see the <a href='#' id='showSupportChannels'>support channels</a> available.</p>
</div>
<div class="col-lg-4 col-sm-12">
  <h4>Play with a Demo</h4>
  <ul>
    <li>If you would like to play with the admin interface without messing up your installation, <a href="http://demo.thulasidas.com/ads-ez" title='Visit the demo site to play with the admin interface' data-toggle='tooltip' target="_blank">please visit Ads EZ demo site</a>.</li>
  </ul>
  <div id='supportChannels'>
    <h4>Need Support?</h4>
    <ul>
      <li>Please check the carefully prepared <a href="http://www.thulasidas.com/plugins/ads-ez#faq" class="popup-long" title='Your question or issue may be already answered or resolved in the FAQ' data-toggle='tooltip'> Plugin FAQ</a> for answers.</li>
      <li>For the lite version, you may be able to get support from the <a href='https://wordpress.org/support/plugin/ads-ez/' class='popup-long' title='WordPress forums have community support for this plugin' data-toggle='tooltip'>WordPress support forum</a>.</li>
      <li>For preferential support and free updates, you can purchase a <a href='http://buy.thulasidas.com/support' class='popup btn-xs btn-info' title='Support contract costs only $4.95 a month, and you can cancel anytime. Free updates upon request, and support for all the products from the author.' data-toggle='tooltip'>Support Contract</a>.</li>
      <li>For one-off support issues, you can raise a one-time paid <a href='http://buy.thulasidas.com/ezsupport' class='popup btn-xs btn-primary' title='Support ticket costs $0.95 and lasts for 72 hours' data-toggle='tooltip'>Support Ticket</a> for prompt support.</li>
      <li>Please include a link to your blog when you contact the plugin author for support.</li>
    </ul>
  </div>
  <h4>Happy with this plugin?</h4>
  <ul>
    <li>Please leave a short review and rate it at <a href="https://wordpress.org/plugins/ads-ez/" class="popup-long" title='Please help the author and other users by leaving a short review for this plugin and by rating it' data-toggle='tooltip'>WordPress</a>. Thanks!</li>
  </ul>
</div>
<div class="clearfix"></div>

<hr />
<p class="center-text"> <a class="btn btn-primary center-text restart" href="#" data-toggle='tooltip' title='Start or restart the tour any time' id='restart'><i class="glyphicon glyphicon-globe icon-white"></i>&nbsp; Start Tour</a>
  <a class="btn btn-primary center-text showFeatures" href="#" data-toggle='tooltip' title='Show the features of this plugin and its Pro version'><i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Show Features</a>
</p>
<?php
if (isset($_REQUEST['inframe'])) {
  ?>
  <style type="text/css">
    .tour-step-background {
      background: transparent;
      border: 2px solid blue;
    }
    .tour-backdrop {
      opacity:0.2;
    }
  </style>
  <?php
}
?>
<script>
  $(document).ready(function () {
    if (!$('.tour').length && typeof (tour) === 'undefined') {
      var tour = new Tour({backdrop: true,
        onShow: function (t) {
          var current = t._current;
          var toShow = t._steps[current].element;
          var dad = $(toShow).parent('ul');
          var gdad = dad.parent();
          dad.slideDown();
          if (dad.hasClass('accordion')) {
            gdad.siblings('.accordion').find('ul').slideUp();
          }
          else if (dad.hasClass('dropdown-menu')) {
            gdad.siblings('.dropdown').find('ul').hide();
          }
        }
      });
      tour.addStep({
        element: "#dashboard",
        placement: "right",
        title: "Dashboard",
        content: "Welcome to Ads EZ! When you login to your Ads EZ Admin interface, you will find yourself in the Dashboard. Depending on the version of our app, you may see informational messages, statistics etc on this page."
      });
      tour.addStep({
        element: "#account",
        placement: "left",
        title: "Quick Access to Your Account",
        content: "Click here if you would like to logout or modify your profile (your password and email Id)."
      });
      tour.addStep({
        element: "#update",
        placement: "left",
        title: "Updates and Upgrades",
        content: "If you would like to check for regular updates, or install a purchased module or Pro upgrade, visit the update page by clicking this button."
      });
      tour.addStep({
        element: "#standAloneMode",
        placement: "left",
        title: "Standalone Mode",
        content: "Open Ads EZ Admin in a new window independent of WordPress admin interface. The standalone mode still uses WP authentication, and cannot be accessed unless logged in."
      });
      tour.addStep({
        element: "#tour",
        placement: "right",
        title: "Tour",
        content: "This page is the starting point of your tour. You can always come here to relaunch the tour, if you wish."
      });
      tour.addStep({
        element: "#goPro",
        placement: "right",
        title: "Upgrade Your App to Pro",
        content: "To unlock the full potential of this app, you may want to purchase the Pro version. You will get an link to download it instantly. It costs only $20.95 and adds tons of features. These Pro features are highlighted by a red icon on this menu bar."
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#banners",
        placement: "right",
        title: "Manage Your Banners",
        content: "In this section, you can manage your banners."
      });
      tour.addStep({
        element: "#banners",
        placement: "right",
        title: "View and Modify Your Banners",
        content: "Click here to see all your banners in a neat table format. By clicking on the entries in the table, you can modify the banner data, such as the link it would point to and the title (hover tooltip)."
      });
      tour.addStep({
        element: "#banners-edit",
        placement: "right",
        title: "Detailed Editing of Your Banner Meatdata",
        content: "To get finer control over the banner metadata, you may want to use this page. In the Pro version, it will contain even more details like banner expiry, authentication and statistics. By default, this page shows only those pages that do not have all the necessary metadata. If you want to see all your banners, please click the corresponding button on the page."
      });
      tour.addStep({
        element: "#banners-batch",
        placement: "right",
        title: "Batch Process Your Uploaded Banners",
        content: "After you upload new banners to your server, please visit this <b>Batch Process</b> page to enter the necessary info, such as the URL target and the mouseover text for the banner. If there are not new banners found, this page will be empty."
      });
      tour.addStep({
        element: "#banners-new",
        placement: "right",
        title: "Create a New Banner",
        content: "<p class='red'>This is a Pro feature.</p><p>It allows you to upload multiple banners and edit their meta data in a neat interface. In this lite version, you can upload the banner to your <code>banners</code> folder or any subfolder below it and run the <b>Batch Process</b> to enter the meta data.</p>"
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#htmlAds",
        placement: "right",
        title: "Manage Your HTML Ads",
        content: "In this section, you can manage your HTML Ads."
      });
      tour.addStep({
        element: "#htmlAds",
        placement: "right",
        title: "View and Modify HMTL Ads",
        content: "<p class='red'>This is a Pro feature.</p><p>The Pro version allows you to store and serve HTML and JavaScript ads such as AdSense and other providers. The lite version is limited to banner ads. On this page, you will be able to see all your HTML/JS ads and edit them.</p>"
      });
      tour.addStep({
        element: "#htmlAds-edit",
        placement: "right",
        title: "Create a New HMTL Ad",
        content: "<p class='red'>This is a Pro feature.</p><p>The Pro version allows you to store and serve HTML and JavaScript ads such as AdSense and other providers. The lite version is limited to banner ads. Here, you can store a new HTML/JS ad.</p>"
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#categories",
        placement: "right",
        title: "Categories and Statistics",
        content: "In this section, you can manage your categories and statistics."
      });
      tour.addStep({
        element: "#categories",
        placement: "right",
        title: "View and Modify Your Categories",
        content: "Click here to see all your ad categories in a neat table format. By clicking on the entries in the table, you can modify the category data, such as its name and comment."
      });
      tour.addStep({
        element: "#categories-new",
        placement: "right",
        title: "Create a New Category",
        content: "<p class='red'>This is a Pro feature.</p><p>It allows you to create new ad categories. In the lite version, you have three default categories that you can modify according to your preference. If you need modify the number of categories, you will have to do direct database manipulation to add or delete categories.</p>"
      });
      tour.addStep({
        element: "#stats",
        placement: "right",
        title: "Ad Serving Statistics",
        content: "<p class='red'>This is a Pro feature.</p><p>Here you can see how your ads are being served, and their performance."
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#invocation",
        placement: "right",
        title: "Integration",
        content: "In this section, you can manage how your app talks to the rest of your websites."
      });
      tour.addStep({
        element: "#invocation",
        placement: "right",
        title: "Invocation Code for Your Sites",
        content: "<p>On this page, you will be able to generate the invocation codes (HTML snippets that you would paste on your websites or emails) to serve your ads.</p>"
      });
      tour.addStep({
        element: "#openx",
        placement: "right",
        title: "Replace OpenX with Ads EZ",
        content: "<p class='red'>This is a Pro feature.</p><p>Ads EZ is designed to be drop-in replacement for OpenX. Here, you can generate the <code>.htaccess</code> directives that will make your Ads EZ app look like your OpenX server to the world.</p>"
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#options",
        placement: "right",
        title: "Configuration",
        content: "In this section, you can configure your Ads EZ installation."
      });
      tour.addStep({
        element: "#options",
        placement: "right",
        title: "Configuration Options",
        content: "On this page, you will set up your Ads EZ server by providing the configuration options."
      });
      tour.addStep({
        element: "#advanced",
        placement: "right",
        title: "Advanced Tools and Options",
        content: "<p class='red'>This is a Pro feature.</p><p>On this page, you will find advanced options like caching, CDN support, Banner Validation etc.</p>"
      });
      tour.addStep({// The first on ul unroll is ignored. Bug in BootstrapTour?
        element: "#profile",
        placement: "right",
        title: "Manage Your Account",
        content: "Set your account parameters or log off."
      });
      tour.addStep({
        element: "#profile",
        placement: "right",
        title: "Manage Your Profile",
        content: "Click here if you would like to modify your profile (your password and email Id)."
      });
      tour.addStep({
        orphan: true,
        placement: "right",
        title: "Done",
        content: "<p>You now know the Ads EZ interface. Congratulations!</p>"
      });
    }
    $("#showSupportChannels").click(function (e) {
      e.preventDefault();
      var bg = $("#supportChannels").css("backgroundColor");
      var fg = $("#supportChannels").css("color");
      $("#supportChannels").css({backgroundColor: "yellow", color: "black"});
      setTimeout(function () {
        $("#supportChannels").css({backgroundColor: bg, color: fg});
      }, 500);
    });
    $(".restart").click(function (e) {
      e.preventDefault();
      tour.restart();
    });
    $(".restart").click(function (e) {
      e.preventDefault();
      tour.restart();
    });
    $(".showFeatures").click(function (e) {
      e.preventDefault();
      $("#features").toggle();
      if ($("#features").is(":visible")) {
        $(this).html('<i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Hide Features');
      }
      else {
        $(this).html('<i class="glyphicon glyphicon-thumbs-up icon-white"></i>&nbsp; Show Features');
      }
    });
  });
</script>
