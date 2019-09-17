import constants from '../../shared/constants'
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
                            <a href="/home">
                                <li className={routeStore.pathname === '/home' ? 'active' : ''}>
                                    <img src="/assets/images/dashboard.svg" className="svg icon" />
                                    <div className="clear" />
                                    {
                                        (sessionStore.userData && [ constants.CEO_ROLE, constants.VP_ROLE, constants.SALES_SUPPORT_ROLE, constants.ADMIN_ROLE ].includes(sessionStore.userData.role)) ? 'Admin' : 'Dashboard'
                                    }
                                </li>
                            </a>
                            <a href="/leaderboard">
                                <li className={routeStore.pathname === '/leaderboard' ? 'active' : ''}>
                                    <img src="/assets/images/leader-board.svg" className="svg icon" />
                                    <div className="clear" />
                                    Leader Board
                                </li>
                            </a>
                            <a href="/training">
                                <li className={routeStore.pathname === '/training' ? 'active' : ''}>
                                    <img src="/assets/images/training.svg" className="svg icon" />
                                    <div className="clear" />
                                    Training
                                </li>
                            </a>
                            <a href="/pwrLine">
                                <li className={routeStore.pathname === '/pwrLine' ? 'active' : ''}>
                                    <img src="/assets/images/pwr-ic-black.svg" className="svg icon" />
                                    <div className="clear" />
                                    Pwr Line
                                </li>
                            </a>
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
                                            (sessionStore.userData && [ constants.CEO_ROLE, constants.VP_ROLE, constants.SALES_SUPPORT_ROLE, constants.ADMIN_ROLE, constants.MANAGER_ROLE, constants.REGIONAL_ROLE ].includes(sessionStore.userData.role)) ?
                                                <a href="/onboarding"><li>Onboarding</li></a>
                                            : null
                                        }
                                        <a href="/pwr-goals"><li>PWR Goals</li></a>
                                        <a href="/profile/"><li>Profile</li></a>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>
                {this.props.children}
            </div>
        )
    }
}
