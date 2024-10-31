<div class="modal riddle-modal fade" id="shortcodeModal" tabindex="-1" role="dialog" aria-labelledby="shortcodeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-10">
            <h2 class="riddle-page-title mb-4">Riddle shortcode</h2>
          </div>
          <div class="col-xl-2" style="text-align: right;">
            <a onclick="return false;" href="#" data-dismiss="modal"><img src="<?php echo RIDDLE_IMAGE_PATH; ?>/icons/ic-x.png" alt="Riddle Close Button Icon"></a>
          </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-8 my-auto">
                <input type="text" class="riddle-form-field form-control" value="Loading..." id="riddle-shortcode-modal-input" readonly>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-cyan btn-riddle btn-sm" id="riddle-shortcode-modal-button-copy">COPY SHORTCODE</button>
            </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <p>Simply ‘paste’ this shortcode into your Wordpress page/post to view your riddle.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>