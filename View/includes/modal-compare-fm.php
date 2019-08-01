<div class="modal-box modal-compare">
    <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
    <div class="center period">
        <strong>Period</strong> 
        <select name="period">
            <option value="Today">Today</option>
            <option value="Yesterday">Yesterday</option>
            <option value="This Week">This Week</option>
            <option value="Last Week">Last Week</option>
            <option value="This Month">This Month</option>
            <option value="Last Month">Last Month</option>
            <option value="This Quarter">This Quarter</option>
            <option value="Last Quarter">Last Quarter</option>
            <option value="This Year">This Year</option>
            <option value="Last Year">Last Year</option>
            <option value="All Time">All Time</option>
            <option value="Custom">Custom</option>
        </select>
        <div class="custom-time-holder">
            <div class="clear20"></div>
            Start <input type="date" name="prevtime" max="<?php echo date("Y-m-d"); ?>">
            End <input type="date" name="curtime" max="<?php echo date("Y-m-d"); ?>">
        </div>
        <input type="hidden" name="vs-id" value="">
        <div class="vs fordesktop">VS</div>
    </div>
    <div class="clear10"></div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 leftbox-compare">            
            <div class="data-holder">
                <div class="user-image">
                    <img src="/assets/images/sample-img.jpg" class="vs-picture">
                </div>
                <div class="user-name vs-name">
                    
                </div>
                <div class="clear20"></div>
                <div class="role-selector role-fm">
                    <div class="data-box">
                        <div class="data-title">
                            Leads
                        </div>
                        <div class="data-value vs-leads-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            QS
                        </div>
                        <div class="data-value vs-qs-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Leads to QS %
                        </div>
                        <div class="data-value vs-leadstoqs-value">
                            0%
                        </div>
                    </div>
                    <div class="records-title">
                        Records
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Leads
                        </div>
                        <div class="clear10"></div>
                        <div class="row">
                            <div class="col-xs-6 week pd0">
                                <div class="data-value">
                                    Week
                                </div>
                                <div class="data-title vs-leads-week-value">
                                    0
                                </div>
                                <div class="clear10"></div>
                            </div>
                            <div class="col-xs-6 month pd0">
                                <div class="data-value">
                                    Month
                                </div>
                                <div class="data-title vs-leads-month-value">
                                    0
                                </div>
                                <div class="clear10"></div>
                            </div>
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            QS
                        </div>
                        <div class="clear10"></div>
                        <div class="row">
                            <div class="col-xs-6 week pd0">
                                <div class="data-value">
                                    Week
                                </div>
                                <div class="data-title vs-qs-week-value">
                                    0
                                </div>
                                <div class="clear10"></div>
                            </div>
                            <div class="col-xs-6 month pd0">
                                <div class="data-value">
                                    Month
                                </div>
                                <div class="data-title vs-qs-month-value">
                                    0
                                </div>
                                <div class="clear10"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="role-selector role-ec">
                    <div class="data-box">
                        <div class="data-title">
                            QS
                        </div>
                        <div class="data-value vs-qs-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Closes
                        </div>
                        <div class="data-value vs-closes-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Installs
                        </div>
                        <div class="data-value vs-installs-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Sit to Close %
                        </div>
                        <div class="data-value vs-sitstoclose-value">
                            0%
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Close to Install Ratio
                        </div>
                        <div class="data-value vs-closestoinstall-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            KWs Installed Year to Date
                        </div>
                        <div class="data-value vs-kw-value">
                            2350 KWs
                        </div>
                    </div>
                </div>
            </div>
            <div class="vs-line formobile"></div>
            <div class="vs formobile">VS</div>
        </div>
        <div class="col-xs-12 col-sm-6 rightbox-compare">
            <div class="data-holder">
                <div class="user-image">
                    <img src="<?php echo $_SESSION['user']['picture']; ?>" class="picture-value">
                </div>
                <div class="user-name name-value">
                    <?php echo $_SESSION['user']['name']; ?>
                </div>
                <div class="clear20"></div>
                <div class="role-selector role-fm">
                    <div class="data-box">
                        <div class="data-title">
                            Leads
                        </div>
                        <div class="data-value leads-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            QS
                        </div>
                        <div class="data-value qs-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Leads to QS %
                        </div>
                        <div class="data-value leadstoqs-value">
                            0%
                        </div>
                    </div>
                    <div class="records-title">
                        Records
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Leads
                        </div>
                        <div class="clear10"></div>
                        <div class="row">
                            <div class="col-xs-6 week pd0">
                                <div class="data-value">
                                    Week
                                </div>
                                <div class="data-title leads-week-value">
                                    0
                                </div>
                                <div class="clear10"></div>
                            </div>
                            <div class="col-xs-6 month pd0">
                                <div class="data-value">
                                    Month
                                </div>
                                <div class="data-title leads-month-value">
                                    0
                                </div>
                                <div class="clear10"></div>
                            </div>
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            QS
                        </div>
                        <div class="clear10"></div>
                        <div class="row">
                            <div class="col-xs-6 week pd0">
                                <div class="data-value">
                                    Week
                                </div>
                                <div class="data-title qs-week-value">
                                    0
                                </div>
                                <div class="clear10"></div>
                            </div>
                            <div class="col-xs-6 month pd0">
                                <div class="data-value">
                                    Month
                                </div>
                                <div class="data-title qs-month-value">
                                    0
                                </div>
                                <div class="clear10"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="role-selector role-ec">
                    <div class="data-box">
                        <div class="data-title">
                            QS
                        </div>
                        <div class="data-value qs-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Closes
                        </div>
                        <div class="data-value close-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Installs
                        </div>
                        <div class="data-value installs-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Sit to Close %
                        </div>
                        <div class="data-value sittoclose-value">
                            0%
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            Close to Install Ratio
                        </div>
                        <div class="data-value closetoinstall-value">
                            0
                        </div>
                    </div>
                    <div class="data-box">
                        <div class="data-title">
                            KWs Installed Year to Date
                        </div>
                        <div class="data-value kw-value">
                            2000 KWs
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-box modal-solo">
    <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
    <div class="user-image">
        <img src="/assets/images/sample-img.jpg" class="vs-picture">
    </div>
    <div class="user-name vs-name">
       
    </div>
    <div class="clear20"></div>
    <div class="period">
        <strong>Period</strong> 
        <select name="period">
            <option value="Today">Today</option>
            <option value="Yesterday">Yesterday</option>
            <option value="This Week">This Week</option>
            <option value="Last Week">Last Week</option>
            <option value="This Month">This Month</option>
            <option value="Last Month">Last Month</option>
            <option value="This Quarter">This Quarter</option>
            <option value="Last Quarter">Last Quarter</option>
            <option value="This Year">This Year</option>
            <option value="Last Year">Last Year</option>
            <option value="All Time">All Time</option>
            <option value="Custom">Custom</option>
        </select>
        <div class="custom-time-holder">
            <div class="clear20"></div>
            Start <input type="date" name="prevtime" max="<?php echo date("Y-m-d"); ?>">
            <br>
            End <input type="date" name="curtime" max="<?php echo date("Y-m-d"); ?>">
        </div>
        <input type="hidden" name="vs-id" value="">        
    </div>
    <div class="clear"></div>
    <div class="row role-selector role-fm">        
        <div class="col-xs-12 rightbox-compare">
            <div class="data-holder">                
                <div class="clear20"></div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                Leads
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-leads-value">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                QS
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-qs-value">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                Leads to QS %
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-leadstoqs-value">
                                0%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="records-title">
                    Records
                </div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo2">
                                Leads
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-6 week pd0">
                                    <div class="data-value">
                                        Week
                                    </div>
                                    <div class="data-title vs-leads-week-value">
                                        0
                                    </div>
                                    <div class="clear10"></div>
                                </div>
                                <div class="col-xs-6 month pd0">
                                    <div class="data-value">
                                        Month
                                    </div>
                                    <div class="data-title vs-leads-month-value">
                                        0
                                    </div>
                                    <div class="clear10"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo2">
                                QS
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-xs-6 week pd0">
                                    <div class="data-value">
                                        Week
                                    </div>
                                    <div class="data-title vs-qs-week-value">
                                        0
                                    </div>
                                    <div class="clear10"></div>
                                </div>
                                <div class="col-xs-6 month pd0">
                                    <div class="data-value">
                                        Month
                                    </div>
                                    <div class="data-title vs-qs-month-value">
                                        0
                                    </div>
                                    <div class="clear10"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row role-selector role-ec">        
        <div class="col-xs-12 rightbox-compare">
            <div class="data-holder">                
                <div class="clear20"></div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                QS
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-qs-value">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                Closes
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-closes-value">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                Installs
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-installs-value">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                Sit to Close %
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-sittoclose-value">
                                0%
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                Close to Install Ratio
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-closestoinstall-value">
                                0
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="data-box">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="data-title title-solo">
                                KWs Installed Year to Date
                            </div>                            
                        </div>
                        <div class="col-xs-6">
                            <div class="data-value vs-kw-value">
                                2000 KWs
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){                    
        $("#topreps table tbody td").click(function(){            
            var par = $(this).parent().parent().parent();
            if($(par).hasClass("table-ec")) {
                if("<?php echo $_SESSION['role']; ?>" == "Jr Energy Consultant" || "<?php echo $_SESSION['role']; ?>" == "Sr Energy Consultant") {
                    $(".modal-compare,.role-ec,#cover").show();
                } else {
                    $(".modal-solo,.role-ec,#cover").show();
                }                
            } else if($(par).hasClass("table-fm")) {                     
                if("<?php echo $_SESSION['role']; ?>" == "Field Marketer" || "<?php echo $_SESSION['role']; ?>" == "Field Marketer Elite") {
                    $(".modal-compare,.role-fm,#cover").show();
                } else {
                    $(".modal-solo,.role-fm,#cover").show();                        
                }
            }
        })

        $(".modal-box .closebtn").click(function(){
            $(".modal-box,.role-selector,#cover").hide();
        })
    })
</script>