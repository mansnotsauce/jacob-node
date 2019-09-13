import { store, emit } from '../framework'
import server from '../utils/server'

const isLoggedInKey = 'isLoggedIn'
const isLoggedInValue = 'true'

export default store({
    isLoggedIn: window.localStorage.getItem(isLoggedInKey) === isLoggedInValue,

    eventListeners: {
        async AttemptedLogin({ email, password }) {
            const { success } = await server.post('/login', { email, password })
            emit.DeterminedLoginStatus({ isLoggedIn: success })
            if (!success) {
                alert('Login attempt failed')
            }
        },
        async LoggedOut() {
            await server.post('/logout')
            emit.DeterminedLoginStatus({ isLoggedIn: false })
        },
        async Initialized() {
            const { isLoggedIn } = await server.get('/loginStatus')
            emit.DeterminedLoginStatus({ isLoggedIn })
        },
        async DeterminedLoginStatus({ isLoggedIn }) {
            this.isLoggedIn = isLoggedIn
            if (isLoggedIn) {
                window.localStorage.setItem(isLoggedInKey, isLoggedInValue)
            }
            else {
                window.localStorage.removeItem(isLoggedInKey)
                // TODO: remove cookie
            }
        },
    }
})
