import { view, emit } from '../framework'
import sessionStore from '../stores/sessionStore'

export default view(function Header() {
    return (
        <div>
            {
                sessionStore.isLoggedIn ?
                    <div id="signoutbox">
                        <a onClick={() => emit.LoggedOut()}>
                            <span className="signout">Sign out</span>
                        </a>
                        <a onClick={() => {}}>
                            {/*TODO*/}
                            {/* <div class="so-prof-img" style="background-image:url(<?php echo $_SESSION['user']['picture']; ?>);" /> */}
                        </a>
                    </div>
                : null
            }
            {/*TODO*/}
            {/* <div class="mini-container container">
                <h1 class="center">
                    <span class="colorBlue"><?php echo ucfirst($_SESSION['user']['name_arr'][0]); ?></span>, Welcome to the PWR Station
                </h1>
                <h2>powered by <img src="/assets/images/logo-horizon.png"></h2>
            </div> */}
        </div>
    )
})
