<?php include("View/includes/head.php"); ?>
<body>
    <?php include("View/includes/home-nav.php"); ?>
    <div id="approvebox" class="modal-box">
        <form class="approveform" action="" method="POST">
            <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
            <div class="h4 center">Approve User</div>
            <input type="hidden" name="approve_id">   
            <p>Test</p>   
            <input type="email" name="email" placeholder="Enter HorizonPWR Email" value="" required>
            <div class="clear30"></div>
            <div class="center">
                <button type="button" class="btn btn-secondary cancelbtn">Cancel</button>
                <button type="submit" class="btn btn-success approvebtnconfirm">Approve</button>
            </div>
        </form>
    </div>
    <div id="confirmbox" class="modal-box">
        <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
        <div class="h4 center">Are you sure you want to delete?</div>
        <input type="hidden" name="bulk_id">
        <input type="hidden" name="bulk_type">
        <input type="hidden" name="table_name">        
        <div class="clear30"></div>
        <div class="center">
            <button type="button" class="btn btn-secondary cancelbtn">Cancel</button>
            <button type="button" class="btn btn-danger deletebtnconfirm">Delete</button>
        </div>
    </div>
    <div id="content" class="content admin-dashboard">
        <div class="container">
            <div class="clear50"></div>  
            <section class="pwrstation-table">
                <div class="section-table-name">PWRStation</div>
                <div class="row">
                    <div class="col-xs-6 bulk-buttons">                      
                        <div class="dropdown-bulk">
                            <div class="dropdown-bulk-option selected">Bulk Action</div>
                            <div class="dropdown-bulk-option">Delete</div>
                        </div>
                        <button class="bulkBtn" param="pwrstation">Bulk Action <img src="/assets/images/icons/caret-down.png" class="caret-icon"></button>
                        <button class="applyBtn" param="pwrstation">Apply</button>
                        <input type="text" name="pwr_search" placeholder="Search...">
                    </div>
                    <div class="col-xs-6 alignRight">
                        <a href="/admin/add-user/"><button class="addUserBtn">Create New User</button></a>
                    </div>
                </div>
                <div class="clear30"></div>
                <div class="table-container">
                    <div class="table-header">
                        <table class="table" param="pwrstation">
                            <thead>
                                <tr>
                                    <th><div class="checkbox"><span param="all"></span></div></th>
                                    <th class="sort greyColor" param="ASC">Name</th>                                
                                    <th class="sort greyColor" param="ASC">Email</th>
                                    <th class="sort greyColor" param="ASC">Team</th>
                                    <th class="sort greyColor" param="ASC">Role</th>
                                    <th class="greyColor">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($data['pwrstation_acc'])>0): ?>
                                <?php $column = 1; foreach ($data['pwrstation_acc'] as $key => $value): ?>
                                    <?php if( $column == 1) {
                                        echo "<tr>";
                                        $column=0;
                                    } else {
                                        echo '<tr class="even-column">';
                                        $column=1;
                                    }
                                    ?>
                                    
                                        <td><div class="checkbox"><span param="<?php echo $value['Id']; ?>"></span></div></td>
                                        <td><div class="profile-holder"><img src="<?php echo $value['picture']; ?>" class="prof-table-img"><span class="username"><?php echo $value['Name']; ?></span><br><span class="action"><a href="/profile/<?php echo $value['Id']; ?>?edit=1">Edit</a> | <a href="/profile/<?php echo $value['Id']; ?>">View</a></span></div>                              
                                    </td>                                                                
                                    <td class="greyColor"><?php echo $value['Company_Email__c']; ?></td>
                                    <td class="greyColor"><?php echo $value['Team__c']; ?></td>
                                    <td class="greyColor"><?php echo $value['Position__c']; ?></td>
                                    <td class="greyColor"><?php echo $value['Status__c']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                                <?php else: ?>
                                    <tr><td colspan=6>No records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><div class="checkbox"><span param="all"></span></div></th>
                                    <th class="greyColor">Name</th>                                
                                    <th class="colorBlue">Email</th>
                                    <th class="greyColor">Team</th>
                                    <th class="greyColor">Role</th>
                                    <th class="greyColor">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($data['pwrstation_acc'])>0): ?>
                                <?php $column = 1; foreach ($data['pwrstation_acc'] as $key => $value): ?>
                                    <?php if( $column == 1) {
                                        echo "<tr>";
                                        $column=0;
                                    } else {
                                        echo '<tr class="even-column">';
                                        $column=1;
                                    }
                                    ?>
                                    
                                        <td><div class="checkbox"><span param="<?php echo $value['Id']; ?>"></span></div></td>
                                        <td><div class="profile-holder"><img src="<?php echo $value['picture']; ?>" class="prof-table-img"><span class="username"><?php echo $value['Name']; ?></span><br><span class="action"><a href="/profile/<?php echo $value['Id']; ?>?edit=1">Edit</a> | <a href="/profile/<?php echo $value['Id']; ?>">View</a></span></div>                              
                                    </td>                                                                
                                    <td class="colorBlue"><?php echo $value['Company_Email__c']; ?></td>
                                    <td class="greyColor"><?php echo $value['Team__c']; ?></td>
                                    <td class="greyColor"><?php echo $value['Position__c']; ?></td>
                                    <td class="greyColor"><?php echo $value['Status__c']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                                <?php else: ?>
                                    <tr><td colspan=6>No records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>  
            <div class="clear100"></div>
            <section class="onboarding-table">
                <div class="section-table-name">Onboarding</div>
                <div class="row">
                    <div class="col-xs-6 bulk-buttons">                      
                        <div class="dropdown-bulk">
                            <div class="dropdown-bulk-option selected">Bulk Action</div>                            
                            <div class="dropdown-bulk-option">Delete</div>
                        </div>
                        <button class="bulkBtn" param="onboarding">Bulk Action <img src="/assets/images/icons/caret-down.png" class="caret-icon"></button>
                        <button class="applyBtn" param="onboarding">Apply</button>
                        <input type="text" name="onb_search" placeholder="Search...">
                    </div>
                    <div class="col-xs-6 alignRight">
                        <a href="/admin/add-user/"><button class="addUserBtn">Create New User</button></a>
                    </div>
                </div>
                <div class="clear30"></div>
                <div class="table-container">
                    <div class="table-header">
                        <table class="table" param="onboarding">
                            <thead>
                                <tr>
                                    <th><div class="checkbox"><span param="all"></span></div></th>
                                    <th class="greyColor sort" param="ASC">Name</th>
                                    <th class="colorBlue sort" param="ASC">Email</th>                            
                                    <th class="greyColor percentage-count-column sort" param="ASC">% Complete</th>
                                    <th class="greyColor">Status</th>
                                </tr>                                
                            </thead>
                            <tbody>                  
                                <?php if(count($data['onboarding_acc'])>0): ?>
                                <?php $column = 1; foreach ($data['onboarding_acc'] as $key => $value): ?>
                                    <?php if( $column == 1) {
                                        echo "<tr>";
                                        $column=0;
                                    } else {
                                        echo '<tr class="even-column">';
                                        $column=1;
                                    }
                                    ?>
                                    
                                        <td><div class="checkbox"><span param="<?php echo $value['Id']; ?>"></span></div></td>
                                        <td><div class="profile-holder"><span class="username"><?php echo $value['Name']; ?></span><br><span class="action"><a href="/profile/<?php echo $value['Id']; ?>?edit=1">Edit</a> | <a href="/profile/<?php echo $value['Id']; ?>">View</a></span></div>                              
                                        </td>                           
                                        <td class="colorBlue"><?php echo $value['Company_Email__c']; ?></td>
                                        <td>
                                            <div class="progress">
                                              <div class="progress-bar" style="width:<?php if($value['Onboarding_Complete_Percent__c']==NULL){ echo "0%";} else { echo $value['Onboarding_Complete_Percent__c']."%"; } ?>"></div>
                                            </div><?php if($value['Onboarding_Complete_Percent__c']==NULL){ echo "<span class='text-danger'>0%</span>";} else { echo $value['Onboarding_Complete_Percent__c']."%"; } ?>
                                        </td>
                                        <td><?php if($value['Onboarding_Complete_Percent__c']==100): ?>
                                            <button param="<?php echo $value['Id']; ?>" class="approve-onb">Approve</button>
                                            <?php else: ?>
                                                Pending
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php else: ?>
                                    <tr><td colspan=5>No records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><div class="checkbox"><span param="all"></span></div></th>
                                    <th class="greyColor">Name</th>
                                    <th class="greyColor">Email</th>                            
                                    <th class="greyColor percentage-count-column">% Complete</th>
                                    <th class="greyColor">Status</th>
                                </tr>                                
                            </thead>
                            <tbody>                  
                                <?php if(count($data['onboarding_acc'])>0): ?>
                                <?php $column = 1; foreach ($data['onboarding_acc'] as $key => $value): ?>
                                    <?php if( $column == 1) {
                                        echo "<tr>";
                                        $column=0;
                                    } else {
                                        echo '<tr class="even-column">';
                                        $column=1;
                                    }
                                    ?>
                                    
                                        <td><div class="checkbox"><span param="<?php echo $value['Id']; ?>"></span></div></td>
                                        <td><div class="profile-holder"><span class="username"><?php echo $value['Name']; ?></span><br><span class="action"><a href="/profile/<?php echo $value['Id']; ?>?edit=1">Edit</a> | <a href="/profile/<?php echo $value['Id']; ?>">View</a></span></div>                              
                                        </td>                           
                                        <td class="colorBlue"><?php echo $value['Company_Email__c']; ?></td>
                                        <td>
                                            <div class="progress">
                                              <div class="progress-bar" style="width:<?php if($value['Onboarding_Complete_Percent__c']==NULL){ echo "0%";} else { echo $value['Onboarding_Complete_Percent__c']."%"; } ?>"></div>
                                            </div><?php if($value['Onboarding_Complete_Percent__c']==NULL){ echo "<span class='text-danger'>0%</span>";} else { echo $value['Onboarding_Complete_Percent__c']."%"; } ?>
                                        </td>
                                        <td><button param="<?php echo $value['Id']; ?>" class="approve-onb">Approve</button></td>
                                    </tr>
                                <?php endforeach ?>
                                <?php else: ?>
                                    <tr><td colspan=5>No records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>          
        </div>
    </div>

    <?php include("View/includes/foot.php"); ?>    
    <script type="text/javascript">
        $(document).ready(function(){
            $(".approve-onb").click(function(){
                $("#approvebox,#cover").show();
                var par = $(this).parent().parent();
                var id = $(this).attr("param");
                $("#approvebox p").text($(".username",par).text());
                $("#approvebox input[name=approve_id]").val(id);
            });
            $(".cancelbtn,.closebtn").click(function(){
                $("#approvebox,#cover").hide();
            })

            $("#approvebox .approveform").submit(function(e){
                e.preventDefault();
                var email = $("input[name=email]",this).val();
                var id = $("input[name=approve_id]",this).val();                
                $("#approvebox p").append('<img src="/assets/images/spinner.gif" style="height:20px;margin-left:10px;" class="spinner-gif">');

                $.post("/api/approve-onboarding",{"user_id":id,"email":email},function(data){
                    $(".spinner-gif").remove();
                    if(data=="success") {
                        $("#approvebox p").append('<img src="/assets/images/success.png" style="height:20px;margin-left:10px;" class="spinner-gif">');    
                        setTimeout(function(){ location.reload() }, 1500);
                    }                                    
                })
            })

            $("input[name=pwr_search]").keyup(function(){
                var table="pwrstation";
                var name = "Name";
                var sort = "ASC";
                var search = $(this).val();
                $.post("/api/get-admin-userlist",{"column":name,"sort":sort,"table":table,"search":search},function(data){                    
                    $("."+table+"-table .table-content table tbody").html(data);
                    $("tbody .checkbox span").click(function(){                     
                        if($(this).hasClass("checked")) {
                            $(this).removeClass("checked");                             
                        } else {
                            $(this).addClass("checked");                                
                        }           
                        var par2 = $(this).parent().parent().parent().parent().parent().parent().parent();
                        if($(this).attr("param")=="all" && $(this).hasClass("checked")) {               
                            $(".table-content tbody .checkbox span",par2).each(function(){                 
                                if(!$(this).hasClass("checked")) {
                                    $(this).addClass("checked");
                                }
                            });             
                        } else if($(this).attr("param")=="all" && !$(this).hasClass("checked")) {               
                            $(".table-content tbody .checkbox span",par2).each(function(){                 
                                if($(this).hasClass("checked")) {
                                    $(this).removeClass("checked");
                                }
                            });             
                        } else {                
                            $("th .checkbox span",par2).removeClass("checked");             
                        }
                    })
                })
            })

            $("input[name=onb_search]").keyup(function(){
                var table="onboarding";
                var name = "Name";
                var sort = "ASC";
                var search = $(this).val();
                $.post("/api/get-admin-userlist",{"column":name,"sort":sort,"table":table,"search":search},function(data){                    
                    $("."+table+"-table .table-content table tbody").html(data);
                    $("tbody .checkbox span").click(function(){                     
                        if($(this).hasClass("checked")) {
                            $(this).removeClass("checked");                             
                        } else {
                            $(this).addClass("checked");                                
                        }           
                        var par2 = $(this).parent().parent().parent().parent().parent().parent().parent();
                        if($(this).attr("param")=="all" && $(this).hasClass("checked")) {               
                            $(".table-content tbody .checkbox span",par2).each(function(){                 
                                if(!$(this).hasClass("checked")) {
                                    $(this).addClass("checked");
                                }
                            });             
                        } else if($(this).attr("param")=="all" && !$(this).hasClass("checked")) {               
                            $(".table-content tbody .checkbox span",par2).each(function(){                 
                                if($(this).hasClass("checked")) {
                                    $(this).removeClass("checked");
                                }
                            });             
                        } else {                
                            $("th .checkbox span",par2).removeClass("checked");             
                        }
                    })
                    $(".approve-onb").click(function(){
                        $("#approvebox,#cover").show();
                        var par = $(this).parent().parent();
                        var id = $(this).attr("param");
                        $("#approvebox p").text($(".username",par).text());
                        $("#approvebox input[name=approve_id]").val(id);
                    });
                })
            })

            //Sorting
            $(".sort").click(function(){
                $(".sort").each(function(){
                    $(this).removeClass("active");
                })
                $(this).addClass("active");
                var name=$(this).text();
                var sort=$(this).attr("param");
                if(sort=="ASC") {
                    $(this).attr("param","DESC");
                } else {
                    $(this).attr("param","ASC");
                }
                var table=$(this).parent().parent().parent().attr("param");
                var search="";
                if(table=="pwrstation") {
                    search = $("input[name=pwr_search]").val();
                } else {
                    search = $("input[name=onb_search]").val();
                }                
                if(search!="") {
                    $.post("/api/get-admin-userlist",{"column":name,"sort":sort,"table":table,"search":search},function(data){
                        $("."+table+"-table .table-content table tbody").html(data);
                        $("tbody .checkbox span").click(function(){                     
                            if($(this).hasClass("checked")) {
                                $(this).removeClass("checked");                             
                            } else {
                                $(this).addClass("checked");                                
                            }           
                            var par2 = $(this).parent().parent().parent().parent().parent().parent().parent();
                            if($(this).attr("param")=="all" && $(this).hasClass("checked")) {               
                                $(".table-content tbody .checkbox span",par2).each(function(){                 
                                    if(!$(this).hasClass("checked")) {
                                        $(this).addClass("checked");
                                    }
                                });             
                            } else if($(this).attr("param")=="all" && !$(this).hasClass("checked")) {               
                                $(".table-content tbody .checkbox span",par2).each(function(){                 
                                    if($(this).hasClass("checked")) {
                                        $(this).removeClass("checked");
                                    }
                                });             
                            } else {                
                                $("th .checkbox span",par2).removeClass("checked");             
                            }
                        })

                        if(table == "onboarding") {
                            $(".approve-onb").click(function(){
                                $("#approvebox,#cover").show();
                                var par = $(this).parent().parent();
                                var id = $(this).attr("param");
                                $("#approvebox p").text($(".username",par).text());
                                $("#approvebox input[name=approve_id]").val(id);
                            });
                        }
                    })
                } else {
                    $.post("/api/get-admin-userlist",{"column":name,"sort":sort,"table":table},function(data){
                        $("."+table+"-table .table-content table tbody").html(data);
                        $("tbody .checkbox span").click(function(){                     
                            if($(this).hasClass("checked")) {
                                $(this).removeClass("checked");                             
                            } else {
                                $(this).addClass("checked");                                
                            }           
                            var par2 = $(this).parent().parent().parent().parent().parent().parent().parent();
                            if($(this).attr("param")=="all" && $(this).hasClass("checked")) {               
                                $(".table-content tbody .checkbox span",par2).each(function(){                 
                                    if(!$(this).hasClass("checked")) {
                                        $(this).addClass("checked");
                                    }
                                });             
                            } else if($(this).attr("param")=="all" && !$(this).hasClass("checked")) {               
                                $(".table-content tbody .checkbox span",par2).each(function(){                 
                                    if($(this).hasClass("checked")) {
                                        $(this).removeClass("checked");
                                    }
                                });             
                            } else {                
                                $("th .checkbox span",par2).removeClass("checked");             
                            }
                        })

                        if(table == "onboarding") {
                            $(".approve-onb").click(function(){
                                $("#approvebox,#cover").show();
                                var par = $(this).parent().parent();
                                var id = $(this).attr("param");
                                $("#approvebox p").text($(".username",par).text());
                                $("#approvebox input[name=approve_id]").val(id);
                            });
                        }
                    })
                }


                
            })
    
            //admin dashboard thead
            $("thead .checkbox span").click(function(){                                
                if($(this).hasClass("checked")) {
                    $(this).removeClass("checked");                             
                } else {
                    $(this).addClass("checked");                                
                }           
                var par2 = $(this).parent().parent().parent().parent().parent().parent().parent();
                if($(this).attr("param")=="all" && $(this).hasClass("checked")) {               
                    $(".table-content tbody .checkbox span",par2).each(function(){                 
                        if(!$(this).hasClass("checked")) {
                            $(this).addClass("checked");
                        }
                    });             
                } else if($(this).attr("param")=="all" && !$(this).hasClass("checked")) {               
                    $(".table-content tbody .checkbox span",par2).each(function(){                 
                        if($(this).hasClass("checked")) {
                            $(this).removeClass("checked");
                        }
                    });             
                } else {                
                    $("th .checkbox span",par2).removeClass("checked");             
                }
            })
            //tbody
            $("tbody .checkbox span").click(function(){                     
                if($(this).hasClass("checked")) {
                    $(this).removeClass("checked");                             
                } else {
                    $(this).addClass("checked");                                
                }           
                var par2 = $(this).parent().parent().parent().parent().parent().parent().parent();
                if($(this).attr("param")=="all" && $(this).hasClass("checked")) {               
                    $(".table-content tbody .checkbox span",par2).each(function(){                 
                        if(!$(this).hasClass("checked")) {
                            $(this).addClass("checked");
                        }
                    });             
                } else if($(this).attr("param")=="all" && !$(this).hasClass("checked")) {               
                    $(".table-content tbody .checkbox span",par2).each(function(){                 
                        if($(this).hasClass("checked")) {
                            $(this).removeClass("checked");
                        }
                    });             
                } else {                
                    $("th .checkbox span",par2).removeClass("checked");             
                }
            })

            $(".dropdown-bulk-option").click(function(){
                var par = $(this).parent();
                var par2 = $(this).parent().parent();
                $(".dropdown-bulk-option",par).each(function(){
                    $(this).removeClass("selected");
                });
                $(this).addClass("selected");
                $(".bulkBtn",par2).html($(this).text()+' <img src="/assets/images/icons/caret-down.png" class="caret-icon">').removeClass("open");
                $(par).hide();                
            })

            $(".bulkBtn").click(function(){
                var par = $(this).parent();
                if($(this).hasClass("open")) {
                    $(this).removeClass("open");
                } else {
                    $(this).addClass("open");
                }
                if($(".dropdown-bulk",par).is(":visible")) {
                    $(".dropdown-bulk",par).hide();
                } else {
                    $(".dropdown-bulk",par).show();
                }           
            })

            $(".applyBtn").click(function(){
                var par = $(this).parent();
                var param = $(this).attr("param");                
                if($(".dropdown-bulk-option.selected",par).text()=="Bulk Action") {
                    $(".bulkBtn",par).addClass("error");                    
                    setTimeout(function(){ $(".bulkBtn",par).removeClass("error"); }, 3000);
                } else {
                    var type = $(".dropdown-bulk-option.selected",par).text();
                    var id = [];
                    $("."+param+"-table .table .checkbox span").each(function(){
                        if($(this).hasClass("checked")) {
                            if($(this).attr("param")!="all")
                            {
                                id.push($(this).attr("param"));
                            }
                        }
                    });

                    if(id.length>0) {
                        if(type == "Delete") {
                            $("input[name=bulk_id]").val(id);
                            $("input[name=bulk_type]").val(type);
                            $("input[name=table_name]").val(param);      
                            $("#cover,#confirmbox").show();
                        } else {
                            $(par).append('<img src="/assets/images/spinner.gif" style="height:30px;margin-left:10px;" class="spinner-gif">');
                            $.post("/api/admin-bulk/",{"type":type,"id":id},function(data){ 
                                $(".spinner-gif").remove();                                     
                                if(data == "success") {
                                    location.reload(true);
                                }
                            });
                        }                                                
                    }                    
                }
            })

            $(".deletebtnconfirm").click(function(){                
                var bulk_id = $("input[name=bulk_id]").val().split(",");
                var bulk_type = $("input[name=bulk_type]").val();
                var table_name = $("input[name=table_name]").val();
                $("."+table_name+"-table .bulk-buttons").append('<img src="/assets/images/spinner.gif" style="height:30px;margin-left:10px;" class="spinner-gif">');
                $("#confirmbox,#cover").hide();
                $.post("/api/admin-bulk/",{"type":bulk_type,"id":bulk_id},function(data){                        
                    $(".spinner-gif").remove();     
                    console.log(data);                                       
                    if(data == "success") {
                        location.reload(true);
                    }
                });
            })

            $(".closebtn,.cancelbtn").click(function(){
                $("#confirmbox,#cover").hide();                
            })
            //admin dashboard end
        })
    </script>
</body>
</html>