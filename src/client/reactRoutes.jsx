import * as React from 'react'
import { withRouter, Switch, Route, Redirect } from 'react-router-dom'
import PropTypes from 'prop-types'
import { emit, view } from './framework'
import routeStore from './stores/routeStore' // leave this
import sessionStore from './stores/sessionStore'
import Header from './views/header'
import Footer from './views/footer'
import Login from './views/login'
import Home from './views/home'
import AdminDashboard from './views/adminDashboard'
import CreateUser from './views/createUser'
import EditUser from './views/editUser'

const HomeWithChildRoutes = view(function HomeWithChildRoutes() {
    return (
        <Home>
            <Switch>
                <Route path="/home" render={() => {
                    return (
                        <div>
                            <AdminDashboard />

                        </div>
                    )
                }} />
                <Route path="/createUser" component={CreateUser} />
                <Route path="/editUser/:userId" component={EditUser} />
            </Switch>
        </Home>
    )
})

// TODO: route is lost on rerender unless it's one of these routes
const Routes = view(function Routes() {
    
    // leave this -- it causes this component to rerender when route changes
    const { pathname } = routeStore
    
    const { isActive } = sessionStore

    if (isActive === null) {
        return <div>Loading...</div>
    }
    
    return (
        <div>
            <Header />
            <Switch>
                <Route path="/login" render={() => {
                    return  isActive ? <Redirect to="/home" /> : <Login />
                }} />
                <Route path="/home" render={() => {
                    return isActive ? <HomeWithChildRoutes /> : <Redirect to="/login" />
                }} />
                <Route path="/" render={() => {
                    return isActive ? <HomeWithChildRoutes /> : <Redirect to="/login" />
                }} />
            </Switch>
            <Footer />
        </div>
    )
})


@withRouter
export default class RoutesWrapper extends React.Component {

    static propTypes = {
        location: PropTypes.object.isRequired
    }

    componentDidMount() {
        const { pathname, search, hash } = this.props.location
        emit.RouteChanged({ pathname, search, hash })
    }
    
    componentDidUpdate(prevProps) {
        if (this.props.location !== prevProps.location) {
            const { pathname, search, hash } = this.props.location
            emit.RouteChanged({
                pathname,
                // search,
                // hash,
                previousPathname: prevProps.location.pathname,
            })
        }
    }
    
    render() {
        return <Routes />
    }
}
