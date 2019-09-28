import { store, emit } from '../framework'
import requester from '../requester'

export default store({

    isLoggedIn  : null,
    user        : null,

    eventListeners: {
        async ClickedLogin({ email, password }) {
            const { isLoggedIn, entities } = await requester.post('/api/login', { email, password })
            if (isLoggedIn) {
                emit.LoginConfirmed({ entities })
            }
            else {
                emit.LoginDenied()
            }
        },
        LoginDenied() {
            this.isLoggedIn = false
        },
        LoginConfirmed({ entities }) {
            this.isLoggedIn = true
            const { user } = entities
            this.user = user
        },
        async ClickedLogout() {
            await requester.post('/api/logout')
            window.location.reload(true) // remove cookies
        },
        async Initialized() {
            const { isLoggedIn, entities } = await requester.get('/api/userStatus')
            if (isLoggedIn) {
                emit.LoginConfirmed({ entities })
            }
            else {
                emit.LoginDenied()
            }
        },
    }
})
