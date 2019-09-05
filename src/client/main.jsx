import 'babel-polyfill'
import { render } from 'react-dom'
import { HashRouter } from 'react-router-dom'
import Routes from './reactRoutes'

render(
    <HashRouter>
        <Routes />
    </HashRouter>
    , document.getElementById('root')
)
