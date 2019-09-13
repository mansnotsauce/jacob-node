import * as React from 'react'
import { withRouter, Switch, Route, Redirect } from 'react-router-dom'
import PropTypes from 'prop-types'
import { emit, view } from './framework'
import routeStore from './stores/routeStore'
import sessionStore from './stores/sessionStore'
import Header from './views/header'
import Footer from './views/footer'
import Login from './views/login'
import Home from './views/home'

const Routes = view(function Routes() {
    
    // leave this -- it causes this component to rerender when route changes
    const { pathname } = routeStore
    
    const { isLoggedIn } = sessionStore
    
    return (
        <div>
            <Header />
            <Switch>
                <Route path="/login" render={() => {
                    return  isLoggedIn ? <Redirect to="/home" /> : <Login />
                }} />
                <Route path="/Home" render={() => {
                    return isLoggedIn ? <Home /> : <Redirect to="/login" />
                }} />
                <Route path="/" render={() => {
                    return isLoggedIn ? <Home /> : <Redirect to="/login" />
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
            emit.RouteChanged({ pathname, search, hash })
        }
    }
    
    render() {
        return <Routes />
    }
}
