import { store, emit } from '../framework'
import server from '../utils/server'

const isLoggedInKey = 'isLoggedIn'
const isLoggedInValue = 'true'
const usernameKey = 'username'

export default store({
    isLoggedIn: window.localStorage.getItem(isLoggedInKey) === isLoggedInValue,
    attemptFailed: false,
    username:  window.localStorage.getItem(usernameKey) || '',
    password: '',
    eventListeners: {
        async AttemptedLogin({ username, password }) {
            const { success } = await server.post('/login', { username, password })
            emit.CompletedLoginAttempt({ success, username, password })
        },
        CompletedLoginAttempt({ success, username, password }) {
            this.isLoggedIn = success
            if (success) {
                this.username = username
                window.localStorage.setItem(usernameKey, username)
                this.password = password
                this.attemptFailed = false
                window.localStorage.setItem(isLoggedInKey, isLoggedInValue)
            }
            else {
                window.localStorage.removeItem(isLoggedInKey)
                alert('Invalid username/password')
                this.attemptFailed = true
            }
        },
        async LoggedOut() {
            window.localStorage.removeItem(isLoggedInKey)
            window.localStorage.removeItem(usernameKey)
            this.isLoggedIn = false
            this.username = ''
            this.password = ''
            const { success } = await server.post('/logout')
            console.log({ logoutSuccess: success })
        },
    }
})
