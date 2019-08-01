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
                Tags:
                <textarea name="tags" class="form-control" placeholder="Separate Tags by comma ','"></textarea>
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
                Tags:
                <textarea name="tags" class="form-control" placeholder="Separate Tags by comma ','"></textarea>
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
        <div class="container">  
            <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>
                <button class="addvideoopen addVideoBtn"><img src="/assets/images/icons/videos.png">Add Video</button>          
                <div class="clear50"></div><div class="clear20"></div>
            <?php endif; ?>      	
        	<div class="row">
                <?php if(count($data['videos'])>0): ?>
                    <?php for($x=0,$y=1;$x<count($data['videos']);$x++,$y++): ?>                                              
                        <div class="col-xs-12 col-sm-4 video-<?php echo $x; ?>">
                            <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>                                
                                <div class="closebtn videoclose" param="<?php echo $x; ?>">
                                    <img class="editimg" param="<?php echo $data['videos'][$x]['id']; ?>" src="/assets/images/editbtn.png">
                                    <img class="deleteimg" param="<?php echo $data['videos'][$x]['id']; ?>" src="/assets/images/closebtn.png"></div>
                            <?php endif; ?>
                            <div class="video-training-box">
                                <div class="videowrapper">                                    
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $data['videos'][$x]['link']; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
                                </div>
                                <div class="video-content">
                                    <div class="video-title"><?php echo $data['videos'][$x]['name']; ?><div class="line"></div></div>
                                    <div class="video-tags"><div class="tag-label">Tags:</div> 
                                        <?php $tags = explode(", ",$data['videos'][$x]['tags']); ?>
                                        <?php foreach ($tags as $key => $value) {
                                            echo '<div class="span">'.$value.'</div> ';
                                        } ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <?php if($y==3) {
                            echo "<div class='clear'></div>";
                            $y=0;
                        }
                        ?>
                    <?php endfor; ?>
                <?php else: ?>
                    <div class="clear100"></div>
                    <div class="h3 center">No videos available.</div>
                    <div class="clear100"></div>
                <?php endif; ?>
            </div>           	
        </div>
    </div>
    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
        $(document).ready(function(){       
            $("#addvideoform").submit(function(e){
                e.preventDefault();
                $.post("/api/add-video",{"category":"training","name":$("input[name=video_name]").val(),"description":$("textarea[name=video_description]").val(),"video_link":$("input[name=video_link]").val(),"type":"youtube","tags":$("textarea[name=tags]").val()},function(data){
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

            $(".videoclose .deleteimg").click(function(){          
                var par = $(this).parent();      
                $(par).attr("param");
                $("#confirmbox,#cover").show();
                $("#confirmbox input[name=id]").val($(this).attr("param"));
                $("#confirmbox input[name=par]").val($(par).attr("param"));                
            })

            $(".videoclose .editimg").click(function(){                
                var par = $(this).parent();                      
                var id = $(this).attr("param");                
                $.post("/api/get_video_byid",{"id":id},function(data){
                    var result = JSON.parse(data);                    
                    $("#updatevideoform input[name=par]").val($(par).attr("param"));
                    $("#updatevideoform input[name=id]").val(result.id);
                    $("#updatevideoform input[name=video_name]").val(result.name);
                    $("#updatevideoform input[name=video_link]").val("https://youtu.be/"+result.link);
                    $("#updatevideoform textarea[name=video_description]").val(result.description);
                    $("#updatevideoform textarea[name=tags]").val(result.tags);
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
                $("#modal-edit-video,#cover").hide();
            });


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
            $("#confirmbox .closebtn,.cancelbtn").click(function(){
                $("#confirmbox,#cover").hide();
            })
        });
    </script>
</body>
</html>