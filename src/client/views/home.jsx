import permissionsUtils from '../../shared/permissionsUtils'
import Link from '../components/link'
import { view } from '../framework'
import routeStore from '../stores/routeStore'
import sessionStore from '../stores/sessionStore'

@view
export default class Home extends React.Component {

    state = {
        moreMenuExpanded: false,
    }

    render() {
        return (
            <div className="menu-desktop">
                <section id="menu-desktop">
                    <div className="mini-container container">
                        <ul className="menu-holder">
                            <Link to="/home">
                                <li className={routeStore.pathname === '/home' ? 'active' : ''}>
                                    <img src="/assets/images/dashboard.svg" className="svg icon" />
                                    <div className="clear" />
                                    {
                                        (permissionsUtils.isAdminRole(sessionStore.user.role)) ? 'Admin' : 'Dashboard'
                                    }
                                </li>
                            </Link>
                            <Link to="/leaderboard">
                                <li className={routeStore.pathname === '/leaderboard' ? 'active' : ''}>
                                    <img src="/assets/images/leader-board.svg" className="svg icon" />
                                    <div className="clear" />
                                    Leader Board
                                </li>
                            </Link>
                            <Link to="/training">
                                <li className={routeStore.pathname === '/training' ? 'active' : ''}>
                                    <img src="/assets/images/training.svg" className="svg icon" />
                                    <div className="clear" />
                                    Training
                                </li>
                            </Link>
                            <Link to="/pwrLine">
                                <li className={routeStore.pathname === '/pwrLine' ? 'active' : ''}>
                                    <img src="/assets/images/pwr-ic-black.svg" className="svg icon" />
                                    <div className="clear" />
                                    Pwr Line
                                </li>
                            </Link>
                            <li
                                className={'more-desktop' + (this.state.moreMenuExpanded ? ' open' : '')} 
                                onClick={() => this.setState({ moreMenuExpanded: !this.state.moreMenuExpanded })}
                            >
                                <img src="/assets/images/more.svg" className="svg icon" />
                                <div className="clear" />
                                More
                                <div className="dropdown-menu">
                                    <ul className="dropdown-menu-nav">
                                        {

                                            (permissionsUtils.isOnboarderRole(sessionStore.user.role)) ?
                                                <Link to="/onboarding"><li>Onboarding</li></Link>
                                            : null
                                        }
                                        <Link to="/pwr-goals"><li>PWR Goals</li></Link>
                                        <Link to="/profile/"><li>Profile</li></Link>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>
                <div className="content">
                    <div className="container">
                        <div className="clear50" />
                        {this.props.children}
                    </div>
                </div>
            </div>
        )
    }
}
