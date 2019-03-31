<?php require $_SERVER['DOCUMENT_ROOT'].'config/init.php'; ?>
<?php 
  require 'inc/checklogin.php';
  $page_title = "Banner Page";
  
  require CLASS_PATH.'banner.php';
  $banner = new Banner();

require 'inc/header.php'; ?>

    <div class="container body">
      <div class="main_container">
        <?php require 'inc/menu.php' ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
          	<?php flash(); ?>
            <div class="page-title">
              <div class="title_left">
                <h3>Banner Page</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                 	<a href="javascript:;" id="addBanner" class="btn btn-success">
                 		<i class="fa fa-plus"></i> Add Banner
                 	</a>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Banner List</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <table class="table table-bordered jambo_table">
                      	<thead>
                      		<th>S.N</th>
                      		<th>Title</th>
                      		<th>Thumbnail</th>
                      		<th>Link</th>
                      		<th>Status</th>
                      		<th>Action</th>
                      	</thead>
                      	<tbody>
                      		<?php 
                      			$all_banners = $banner->getAllBanner();
                      			if($all_banners){
                      				foreach($all_banners as $key=>$banner_data){
                      			?>
                      			<tr>
                      				<td><?php echo ($key+1); ?></td>
                              <td><?php echo $banner_data->title; ?></td>
                              <td>
                                  <?php 
                                      if(!empty($banner_data->image) && file_exists(UPLOAD_DIR.'banner/'.$banner_data->image)){
                                      ?>
                                          <img src="<?php echo UPLOAD_URL.'banner/'.$banner_data->image; ?>" alt="" class="img img-responsive img-thumbnail" style="max-width: 150px;">
                                      <?php
                                      }
                                  ?>
                              </td>
                              <td>
                                  <a href="<?php echo $banner_data->link; ?>" class="btn-link" target="_banner"><?php echo $banner_data->link; ?></a>
                              </td>
                              <td><?php echo $banner_data->status; ?></td>
                      				<td>
                                <a href="javascript:;" data-edit='<?php echo json_encode($banner_data, JSON_HEX_APOS); ?>'  onclick="editBanner(this)" class="btn-link">Edit</a>
                                 / 
                                <?php 
                                    $token = substr(md5("del-banner-".$banner_data->id."-".$session->getSession('session_token')), 3, 15);
                                    $url = "process/banner?id=".$banner_data->id."&act=".$token;
                                ?>
                                <a href="<?php echo $url; ?>" class="btn-link" onclick="return confirm('Are you sure you want to delete this banner?');">
                                  Delete
                                </a>
                              </td>
                      				
                      			</tr>
                      			<?php		
                      				}
                      			}
                      		?>
                      	</tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

       <?php include 'inc/copy.php'; ?>
      </div>
    </div>





<div class="modal" tabindex="-1" role="dialog" id="add-banner-modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Banner Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    
	    <form action="process/banner" method="post" enctype="multipart/form-data" class="form form-horizontal">
			<div class="modal-body">
				<div class="form-group row">
					<label for="" class="col-sm-3">Title:</label>
					<div class="col-sm-9">
						<input type="text" name="title" required placeholder="Enter Banner Title" class="form-control" id="title">
					</div>
				</div>

				<div class="form-group row">
					<label for="" class="col-sm-3">Link:</label>
					<div class="col-sm-9">
						<input type="url" name="link" required placeholder="Enter Banner Link" class="form-control" id="link">
					</div>
				</div>

				<div class="form-group row">
					<label for="" class="col-sm-3">Status:</label>
					<div class="col-sm-9">
						<select name="status" required id="status" class="form-control">
							<option value="Active">Active</option>
							<option value="Inactive">Inactive</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="" class="col-sm-3">Image:</label>
					<div class="col-sm-4">
						<input type="file" name="image" required id="image" accept="image/*">
					</div>
          <div class="col-sm-4">
            <img src="" id="thumbnail" alt="" class="img img-responsive img-thumbnail">
          </div>
				</div>


			</div>
			<div class="modal-footer">
        <input type="hidden" name="banner_id" id="banner_id" value="">
				<button type="submit" class="btn btn-success">
					<i class="fa fa-send"></i>
					Save changes
				</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal">
					<i class="fa fa-trash"></i>
					Close
				</button>
			</div>
		</form>

    </div>
  </div>
</div>

<?php require 'inc/footer.php';?>
<script>
	$('#addBanner').click(function(){
      $('.modal-title').html('Banner Add');

      $('#title').val('');
      $('#link').val('');
      $('#status').val('Active');

      $('#thumbnail').attr('src', "<?php echo ADMIN_IMAGES_URL.'no-image.jpg'; ?>");
      $('#banner_id').val('');
      $('#image').attr('required');


		$('#add-banner-modal').modal('show');
	});

  function editBanner(elem){
    var banner_data = $(elem).data('edit');

    if(banner_data){

      if(typeof(banner_data) != 'object'){
        banner_data = $.parseJSON(banner_data);
      }

      $('.modal-title').html('Update Banner');

      $('#title').val(banner_data.title);
      $('#link').val(banner_data.link);
      $('#status').val(banner_data.status);

      if(null != banner_data.image){
        $('#thumbnail').attr('src', "<?php echo UPLOAD_URL.'banner/'; ?>"+banner_data.image);
      }
      $('#banner_id').val(banner_data.id);
      $('#image').removeAttr('required');
      
      $('#add-banner-modal').modal('show');
    }
  }
</script>