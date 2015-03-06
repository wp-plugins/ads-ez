<?php require('header.php'); ?>
<div>
  <ul class="breadcrumb">
    <li>
      <a href="#">Home</a>
    </li>
    <li>
      <a href="#">HTML Ads</a>
    </li>
  </ul>
</div>

<?php
openBox("HTML Ads", "th-large", 12);
?>
<p>This feature is available in the Pro version of this program, which allows you to create and edit HTML ads in a neat interface. </p>
<p>In this lite version, you have only banner ads.</p>
<?php
closeBox();
include('promo.php');
require('footer.php');
