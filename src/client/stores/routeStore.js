import { store } from '../framework'

export default store({
    
    pathname: '',
    search: '',
    hash: '',
    
    eventListeners: {
        RouteChanged({ pathname, search, hash }) {
            this.pathname = pathname
            this.search = search
            this.hash = hash
        }
    }
})
