<?php 
$s3_display_url = $this->config->item('s3_display_url');
$bucket = $this->config->item('bucket_name');
if($members!=""){
                $proj_admin = get_project_info($project_id);
		foreach ($members as $mem) {
                    $is_allocated = get_project_allocated($project_id,$mem->user_id);
                    $name = 'upload/user/'.$mem->profile_image;
                    if(($mem->profile_image != '' || $mem->profile_image != NULL) && $this->s3->getObjectInfo($bucket,$name)) {
                        $src_member =  $s3_display_url.'upload/user/'.$mem->profile_image;
                    } 
                    else
                    {
                        $src_member = $s3_display_url.'upload/user/no_image.jpg'; 
                    }
                    ?>
                    <li class="customer-user_li">
                        <div class="people-block">
                            <div class="people-img">
                                <img src="<?php echo $src_member;?>" alt="photo1" class="img-polaroid img-circle" >
                                <?php if($proj_admin['project_added_by'] != $mem->user_id && $is_allocated <= '0' ){ ?>
                                <a onclick="removeUser('<?php echo $mem->project_users_id;?>','<?php echo $mem->user_id;?>','<?php echo $mem->project_id;?>');" href="javascript:void(0)" >
                                    <i class="stripicon iconredcolse" style="right: 57px !important"></i></a>
                                <?php } ?>
                            </div>
                            <script type="text/javascript">
                                    $(document).ready(function(){
                                        $('#edit_member_projectrate_<?php echo $mem->project_users_id;?>').editable({
                                            url: SIDE_URL + "project/update_project_member_rate",
                                            params:{project_user_id : <?php echo $mem->project_users_id;?>,user_id:<?php echo $mem->user_id;?>,project_id:<?php echo $mem->project_id;?>},
                                            type: "post",
                                            pk: 1,
                                            mode: "popup",
                                            showbuttons: !0,
                                            validate: function(e) {

                                                var s = /^[0-9 .]*$/;
                                                return s.test($.trim(e)) ? void 0 : "Please enter only number."
                                            },
                                            success: function() {}  
                                        });      
                                    });
                            </script>
                            <p> <?php echo ucwords($mem->first_name)." ".ucwords($mem->last_name);?> </p>
                            <?php if($mem->is_customer_user == 1){ echo "<p>(External)</p>"; }else{ ?>
                            <div><input type="checkbox" name="new_project_owner" id="new_project_owner" value="<?php echo $mem->project_users_id.'&'.$mem->user_id; ?>"  <?php if($mem->is_project_owner == '1'){ echo "checked='checked'"; } ?> /> <span style="display: inline;position: relative;top: -2px;">Admin</span></div>
                            <?php } ?>
                            <?php if($this->session->userdata('pricing_module_status')=='1'){?>
                            <p><span>Project rate</span></p>
                            <span><?php echo $this->session->userdata('currency');?><a href="#" data-name="project_rate" class="font-family_customer" data-emptytext="Not set" data-placeholder="Enter amount"  data-type="text" data-pk="1" class="txt-style edit_title" id="edit_member_projectrate_<?php echo $mem->project_users_id;?>"><?php if($mem->project_rate!='0'){echo $mem->project_rate;}?></a></span>
                           <?php }?>
                            <!--</span>-->
                        </div>
                    </li>
<?php }	}?>
            <?php
                if($is_owner=='1'){ ?>
                    <li class="customer-user_li">
                        <div class="people-block">
                            <a onclick="listmem('<?php echo $project_id;?>');" class="btn" data-toggle="modal"
                                href="#users_list" style="height: 88px; vertical-align: text-bottom; color: darkgray"
                                id="add_users_<?php echo $project_id;?>" data-dismiss="modal" ><br/>Click to <br/>Add Users</a>
                        </div>
                    </li>
                <?php } ?>