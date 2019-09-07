export default function Footer() {
    return (
        <div>
            <footer className="footer">
                <div className="container">
                    <p>
                        <img src="/assets/images/phone-receiver.png" width="18" height="18" alt="" className="wp-image-25 alignnone size-full" />&nbsp;Phone: 888-468-7180
                    </p>
                    <p>
                        <img src="/assets/images/placeholder-filled-point.png" width="15" height="20" alt="" className="wp-image-26 alignnone size-full" />&nbsp;Headquarters 237 N 2nd E st, Rexburg, ID 83440
                    </p>
                    <p>
                        <a href="/privacy-policy/" target="_blank" rel="noopener noreferrer" style={{ color: '#3b4958' }}>Privacy Policy</a> | <a href="/terms-of-use/" target="_blank" rel="noopener noreferrer" style={{ color: '#3b4958' }}>Terms of Use</a>
                    </p>
                    <div className="clear10"></div>
                    <a href="/">
                        <img src="/assets/images/logo-horizon.png" className="bottom-logo" alt="horizonPWR" />
                    </a>
                    <div className="clear30"></div>
                </div>
            </footer>
            <footer className="copyright">
                <div className="container">
                    &copy; Copyright 2019 Horizon PWR
                </div>
            </footer>
        </div>
    )
}

// TODO
/*
<?php if($data['menu-link'] != "Log In" && $data['menu-link'] != "Privacy Policy" && $data['menu-link'] != "Terms of Use"): ?>
    <div id="menu-mobile-spacing"></div>
    <div id="menu-mobile">
        <ul className="menu-holder">
            <a href="/"><li <?php if($data['menu-link']=="Dashboard"): ?>className="active"<?php endif; ?>><img src="/assets/images/icons/dashboard.svg" className="svg icon"><div className="clear"></div><?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>Admin<?php else: ?>Dashboard<?php endif; ?></li></a>
            <a href="/leaderboard/"><li <?php if($data['menu-link']=="Leaderboard"): ?>className="active"<?php endif; ?>><img src="/assets/images/icons/leader-board.svg" className="svg icon"><div className="clear"></div>Leader Board</li></a>
            <a href="/training/"><li <?php if($data['menu-link']=="Training"): ?>className="active"<?php endif; ?>><img src="/assets/images/icons/training.svg" className="svg icon"><div className="clear"></div>Training</li></a>
            <a href="/pwr-line"><li <?php if($data['menu-link']=="PWR Line"): ?>className="active"<?php endif; ?>><img src="/assets/images/icons/pwr-ic-black.svg" className="svg icon"><div className="clear"></div>PWR Line</li></a>
            <li className="more-desktop  <?php if($data['menu-link']=="PWR Goals"||$data['menu-link']=="Onboarding"||$data['menu-link']=="Profile"): ?>active<?php endif; ?>"><img src="/assets/images/icons/more.svg" className="svg icon"><div className="clear"></div>More
                <div className="dropdown-menu">
                    <ul className="dropdown-menu-nav">
                        <?php if($_SESSION['role']=="Manager" || $_SESSION['role']=="Regional" || $_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>
                            <a href="/onboarding/"><li>Onboarding</li></a>
                        <?php endif; ?>
                        <a href="/pwr-goals"><li>PWR Goals</li></a>
                        <a href="/profile/"><li>Profile</li></a>                        
                    </ul>
                </div>
            </li>
        </ul>
        <div className="clear"></div>
    </div>
    <script src="/assets/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            updatesvgicon("desktop");
            updatesvgicon("mobile");
            $("#cover").hide();

            $(".menu-holder li.more-desktop,.menu-holder li.more-mobile").click(function(){
                if($(this).hasClass("open")) {
                    $(this).removeClass("open");
                } else {
                    $(this).addClass("open");
                }
            })

        });        

        $(".menu-holder li").hover(function(){
            $(this).addClass("hover");
            var $img = $("img",this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function(data) {
                var $svg = $(data).find('svg');
                if(typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                if(typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass+' replaced-svg');
                }
                $svg = $svg.removeAttr('xmlns:a');
                if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                    $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                }
                $img.replaceWith($svg);

            }, 'xml');
        }, function(){
            $(this).removeClass("hover");
        });                

        function updatesvgicon($view) {            
            if($view=="desktop") {
                var $img = $(".menu-holder li.active img");
                var imgID = $img.attr('id');
                var imgClass = $img.attr('class');
                var imgURL = $img.attr('src');
                jQuery.get(imgURL, function(data) {                
                    var $svg = $(data).find('svg');                
                    if(typeof imgID !== 'undefined') {
                        $svg = $svg.attr('id', imgID);
                    }                
                    if(typeof imgClass !== 'undefined') {
                        $svg = $svg.attr('class', imgClass+' replaced-svg');
                    }                
                    $svg = $svg.removeAttr('xmlns:a');                
                    if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                        $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                    }                
                    $img.replaceWith($svg);
                }, 'xml'); 
            } else if($view=="mobile") {
                $("#menu-mobile .menu-holder li img").each(function(){
                    var $img = $(this);
                    var imgID = $img.attr('id');
                    var imgClass = $img.attr('class');
                    var imgURL = $img.attr('src');                
                    jQuery.get(imgURL, function(data) {                
                        var $svg = $(data).find('svg');                
                        if(typeof imgID !== 'undefined') {
                            $svg = $svg.attr('id', imgID);
                        }                
                        if(typeof imgClass !== 'undefined') {
                            $svg = $svg.attr('class', imgClass+' replaced-svg');
                        }                
                        $svg = $svg.removeAttr('xmlns:a');                
                        if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                            $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                        }                
                        $img.replaceWith($svg);
                    }, 'xml');
                })
            }                
        }
    </script>
<?php endif; ?></p>
*/
