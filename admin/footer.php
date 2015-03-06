<?php if (empty($no_visible_elements)) { ?>
  <!-- content ends -->
  </div><!--/#content.col-md-0-->
<?php } ?>
</div><!--/fluid-row-->
<hr>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div id="myModalLabel" class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3 id="myModalTitle">Settings</h3>
      </div>
      <div class="modal-body">
        <p id="myModalText">Here settings can be configured...</p>
      </div>
      <div class="modal-footer">
        <a href="#" id="myModalClose" class="btn btn-default" data-dismiss="modal">Close</a>
        <a href="#" id="myModalSave" class="btn btn-primary" data-dismiss="modal">Save changes</a>
      </div>
    </div>
  </div>
</div>

<?php if (empty($no_visible_elements)) { ?>
  <footer class="row">
    <p class="col-md-9 col-sm-9 col-xs-12 copyright">&copy; <a href="http://www.thulasidas.com" target="_blank">Manoj Thulasidas</a> 2013 - <?php echo date('Y') ?></p>

    <p class="col-md-3 col-sm-3 col-xs-12 powered-by"><a
        href="http://ads-ez.com/ads/pub.php">Ads EZ Server</a> from <a href="http://ads-ez.com/" target="_blank">Ads EZ Classifieds</a></p>
  </footer>
<?php } ?>

</div><!--/.fluid-container-->

<!-- external javascript -->

<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-editable.min.js"></script>
<script src="js/bootstrap.lightbox.js"></script>
<script src="js/bootstrap-tour.min.js"></script>
<script src="js/bootstrapValidator.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/fileinput.min.js"></script>
<script src="js/bootbox.min.js"></script>
<!-- application script for Charisma demo -->
<script src="js/charisma.js"></script>
</body>
</html>
