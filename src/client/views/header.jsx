import { view, emit } from '../framework'
import sessionStore from '../stores/sessionStore'

export default view(function Header() {
    return (
        <div id="header">
            {
                sessionStore.isLoggedIn ?
                    <div id="signoutbox">
                        <a onClick={() => emit.ClickedLogout()}>
                            <span className="signout">Sign out</span>
                        </a>
                        <a onClick={() => {}}>
                            {/*TODO*/}
                            {/* <div className="so-prof-img" style="background-image:url(<?php echo $_SESSION['user']['picture']; ?>);" /> */}
                        </a>
                        {
                            sessionStore.user.userId !== null ?
                                <div className="so-prof-img" style={{ backgroundImage: `url(/hosted/users/${sessionStore.user.userId}/profilePicture.png)` }} />
                            : null
                        }
                    </div>
                : null
            }
            {
                sessionStore.user && sessionStore.user.firstName ?
                    <div className="mini-container container">
                        <h1 className="center">
                            <span className="colorBlue">{sessionStore.user.firstName}</span>, Welcome to the PWR Station
                        </h1>
                        <h2>powered by <img src="/assets/images/logo-horizon.png" /></h2>
                    </div>
                : null
            }
        </div>
    )
})
