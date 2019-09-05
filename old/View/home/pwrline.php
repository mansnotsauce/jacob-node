<?php include("View/includes/head.php"); ?>
<body>
    <?php include("View/includes/home-nav.php"); ?>
    <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>
        <div id="modal-video">
            <form id="addvideoform" action="" method="POST">
                <p class="result-message"></p>
                <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
                Video Name:
                <input type="text" name="video_name" class="form-control" placeholder="Video Title Here" required/>
                Video Link:
                <input type="text" name="video_link" class="form-control" placeholder="https://youtu.be/j_JfmpAzqQ8" required/>
                Video Description:
                <textarea name="video_description" class="form-control"></textarea>            
                <button type="submit" class="addVideoBtn"><img src="/assets/images/icons/videos.png">Add Video</button>
            </form>
        </div>
        <div id="modal-edit-video">
            <form id="updatevideoform" action="" method="POST">
                <input type="hidden" name="id">
                <input type="hidden" name="par">
                <p class="result-message"></p>
                <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
                Video Name:
                <input type="text" name="video_name" class="form-control" placeholder="Video Title Here" required/>
                Video Link:
                <input type="text" name="video_link" class="form-control" placeholder="https://youtu.be/j_JfmpAzqQ8" required/>
                Video Description:
                <textarea name="video_description" class="form-control"></textarea>                
                <button type="submit" class="updateVideoBtn">Update</button>
            </form>
        </div>
        <div id="confirmbox" class="modal-box">
            <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
            <div class="h4 center">Are you sure you want to delete?</div>
            <input type="hidden" name="id">
            <input type="hidden" name="par">
            <div class="clear30"></div>
            <div class="center">
                <button type="button" class="btn btn-secondary cancelbtn">Cancel</button>
                <button type="button" class="btn btn-danger deletebtnconfirm">Delete</button>
            </div>
        </div>
    <?php endif; ?>
    <div id="content" class="content">
        <div class="mini-container container">        	
            <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>
                <button class="addvideoopen addVideoBtn"><img src="/assets/images/icons/videos.png">Add Video</button>        	
            <?php endif; ?>
            <?php if(count($data['videos'])>0): ?>
            	<div class="video-section-title">
            		Latest Video
            	</div>
            	<div class="clear40"></div>
            	<div class="latest-video video-0">
            		<div class="video-title">
            			<?php echo $data['videos'][0]['name']; ?>
            			<div class="video-date" param="0">            				
                            <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>
                                <img class="editimg" param="<?php echo $data['videos'][0]['id']; ?>" src="/assets/images/editbtn.png">
                                <img class="closebtnimg" param="<?php echo $data['videos'][0]['id']; ?>" src="/assets/images/closebtn.png">
                            <?php endif; ?>
            			</div>
            		</div>
            		<div class="videowrapper">
    					<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $data['videos'][0]['link']; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
    				</div>
            	</div>
            <?php endif; ?>
            <?php if(count($data['videos'])!=1): ?>
            	<div class="video-section-title">
            		Video Archive
            	</div>
            <?php endif; ?>
        	<div class="video-archive">
        		<div class="row">
                    <?php if(count($data['videos'])>0): ?>
                        <?php for($x=1;$x<count($data['videos']);$x++): ?>                        
                            <div class="col-xs-12 col-sm-6 video-<?php echo $x; ?>">                                
                                <div class="videobox">
                                    <div class="video-title">
                                        <?php echo $data['videos'][$x]['name']; ?>
                                        <div class="video-date" param="<?php echo $x; ?>">                                            
                                            <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>
                                                <img class="editimg" param="<?php echo $data['videos'][$x]['id']; ?>" src="/assets/images/editbtn.png">
                                                <img class="closebtnimg" param="<?php echo $data['videos'][$x]['id']; ?>" src="/assets/images/closebtn.png">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="videowrapper">
                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $data['videos'][$x]['link']; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>                        
                        <?php endfor; ?>
                    <?php else: ?>
                        <div class="clear100"></div>
                        <div class="h3 center">No videos available.</div>
                    <?php endif; ?>
        		</div>
        		<div class="clear50"></div>
                <?php if(count($data['videos'])>10): ?>
            		<div class="center">
    	        		<a class="view-more">VIEW MORE<br><img src="/assets/images/icons/double-arrow-down.png" class="double-arrow-down"></a>
    	        	</div>
                <?php endif; ?>
        	</div>
        </div>
    </div>
    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
        $(document).ready(function(){       
            $("#addvideoform").submit(function(e){
                e.preventDefault();
                $.post("/api/add-video",{"category":"pwrline","name":$("input[name=video_name]").val(),"description":$("textarea[name=video_description]").val(),"video_link":$("input[name=video_link]").val(),"type":"youtube"},function(data){
                    var result = JSON.parse(data);                    
                    if(result.success) {
                        $(".result-message").addClass("text-success").html("<strong>Successfully added video!</strong>");   
                        $("#addvideoform input[type=text], #addvideoform textarea").empty();
                        setTimeout(function(){ location.reload(); }, 3000);
                    } else {
                        $(".result-message").addClass("text-danger").html("<strong>Failed to add video. Try again!</strong>");
                    }

                    setTimeout(function(){ 
                        $(".result-message").removeClass("text-success").html("");
                        $(".result-message").removeClass("text-danger").html("");
                    }, 3000);
                })
            });

            $(".addvideoopen").click(function(){                
                $("#modal-video,#cover").show();
            });

            $("#modal-video .closebtn").click(function(){
                $("#modal-video,#cover").hide();
            });

            $(".closebtnimg").click(function(){          
                var par = $(this).parent();   
                $("#confirmbox,#cover").show();
                $("#confirmbox input[name=id]").val($(this).attr("param"));
                $("#confirmbox input[name=par]").val($(par).attr("param"));
            })

            $(".editimg").click(function(){
                var par = $(this).parent();
                var id = $(this).attr("param");
                $.post("/api/get_video_byid",{"id":id},function(data){
                    var result = JSON.parse(data);                    
                    $("#updatevideoform input[name=par]").val($(par).attr("param"));
                    $("#updatevideoform input[name=id]").val(result.id);
                    $("#updatevideoform input[name=video_name]").val(result.name);
                    $("#updatevideoform input[name=video_link]").val("https://youtu.be/"+result.link);
                    $("#updatevideoform textarea[name=video_description]").val(result.description);                    
                    $("#modal-edit-video,#cover").show();
                })                  
            })

            $("#updatevideoform").submit(function(e){
                e.preventDefault();
                var par = $("#updatevideoform input[name=par]").val();
                $(".video-"+par+" .videowrapper").html('<div id="spinner" style="text-align:center;"><img src="/assets/images/spinner.gif" style="margin: 60px;height: 40px;"></div>'); 
                $.post("/api/update_video_byid",{"data":$( this ).serialize()},function(data){
                    if(data) {
                        setTimeout(function(){ location.reload(); }, 2000);
                    }
                    $("#modal-edit-video,#cover").hide();
                })                                
            })

            $("#modal-edit-video .closebtn").click(function(){
                $("#modal-edit-video, #cover").hide();
            })

            $("#confirmbox .cancelbtn, #confirmbox .closebtn").click(function(){
                $("#confirmbox,#cover").hide();
            })

            $("#confirmbox .deletebtnconfirm").click(function(){
                var id = $("#confirmbox input[name=id]").val();
                var par = $("#confirmbox input[name=par]").val();
                $(".video-"+par+" .videowrapper").html('<div id="spinner" style="text-align:center;"><img src="/assets/images/spinner.gif" style="margin: 60px;height: 40px;"></div>');                
                $("#confirmbox,#cover").hide();                
                 $.post("/api/delete-video",{"id":id},function(data){
                    var result = JSON.parse(data);                    
                    if(result.success) {                        
                        location.reload();
                    }
                })
            })


        });
    </script>
</body>
</html>