<div id="cover"></div>
<!-- Main header -->
<section id="header">
    <div id="signoutbox"><a href="/logout/"><span class="signout">Sign out</span></a> <a href="/profile/"><div class="so-prof-img" style="background-image:url(<?php echo $_SESSION['user']['picture']; ?>);"></div></a></div>
    <div class="mini-container container">
        <h1 class="center"><span class="colorBlue"><?php echo ucfirst($_SESSION['user']['name_arr'][0]); ?></span>, Welcome to the PWR Station</h1>
        <h2>powered by <img src="/assets/images/logo-horizon.png"></h2>
    </div>
</section>
<section id="menu-desktop">
    <div class="mini-container container">
        <ul class="menu-holder">
            <a href="/"><li <?php if($data['menu-link']=="Dashboard"): ?>class="active"<?php endif; ?>><img src="/assets/images/icons/dashboard.svg" class="svg icon"><div class="clear"></div><?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>Admin<?php else: ?>Dashboard<?php endif; ?></li></a>
            <a href="/leaderboard/"><li <?php if($data['menu-link']=="Leaderboard"): ?>class="active"<?php endif; ?>><img src="/assets/images/icons/leader-board.svg" class="svg icon"><div class="clear"></div>Leader Board</li></a>
            <a href="/training/"><li <?php if($data['menu-link']=="Training"): ?>class="active"<?php endif; ?>><img src="/assets/images/icons/training.svg" class="svg icon"><div class="clear"></div>Training</li></a>
            <a href="/pwr-line"><li <?php if($data['menu-link']=="PWR Line"): ?>class="active"<?php endif; ?>><img src="/assets/images/icons/pwr-ic-black.svg" class="svg icon"><div class="clear"></div>PWR Line</li></a>
            <li class="more-desktop  <?php if($data['menu-link']=="PWR Goals"||$data['menu-link']=="Onboarding"||$data['menu-link']=="Profile"): ?>active<?php endif; ?>"><img src="/assets/images/icons/more.svg" class="svg icon"><div class="clear"></div>More
                <div class="dropdown-menu">
                    <ul class="dropdown-menu-nav">
                        <?php if($_SESSION['role']=="Manager" || $_SESSION['role']=="Regional" || $_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>
                            <a href="/onboarding/"><li>Onboarding</li></a>
                        <?php endif; ?>
                        <a href="/pwr-goals"><li>PWR Goals</li></a>
                        <a href="/profile/"><li>Profile</li></a></a>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</section>
<!-- /main header -->