<script>
   
    $(document).ready(function(){
        $("#api_access_status").bootstrapToggle();
	$('#api_access_status').bootstrapToggle().on('change', function (e, data) {
        var t=$(this).prop('checked')?1:0;
        upadteapiaccess(t);
	});
    });

</script>
<?php 
    if($app_info){
        $client_id = $app_info[0]->client_id;
        $client_secret = $app_info[0]->client_secret;
    }else{
        $client_id = 0;
        $client_secret =0;
    }
?>

        
            <div class="form-group">
                <label class="control-label padding-top-7 col-md-3"><b>Activate Apis Access:</b> </label>
                <div class="relative-position " >
                    <input type="checkbox" name="api_access_status"  data-toggle="toggle" data-style="android" data-onstyle="primary" class="bts_toggle" data-offstyle="danger"  value=""  id="api_access_status" <?php if($client_id !='0'){ echo "checked";}?>>
                    <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="<?php echo $client_id; ?>"/>
		</div>
            </div>
        
        <div class="row" id="app_data">
            <?php if($app_info){?>
            <div class="col-md-12 form-group" >
                <div class="col-md-3">
                    <label class="control-label"><b>Client ID</b> </label>
                </div>
                <div>
                    <?php echo $client_id;?>
                </div>
            </div>
            <div class="col-md-12 form-group" >
                <div class="col-md-3" >
                    <label class="control-label "><b>Client Secret</b> </label>
                </div>
                <div >
                    <?php echo $client_secret;?>
                </div>
            </div>
            <div class="col-md-12 form-group">
                <div class="col-md-3" >
                    <label class="control-label "><b>Token Generate URL</b> </label>
                </div>
                <div >
                    <?php echo base_url()."OAuth2/token";?>
                </div>
            </div>
            <?php } ?>
        </div>
  