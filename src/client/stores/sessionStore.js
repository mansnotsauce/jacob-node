import { store, emit } from '../framework'
import requester from '../requester'

export default store({

    isActive  : null,
    user      : null,

    eventListeners: {
        async ClickedLogin({ email, password }) {
            const { isActive, entities } = await requester.post('/api/login', { email, password })
            if (isActive) {
                emit.LoginConfirmed({ entities })
            }
            else {
                emit.LoginDenied()
            }
        },
        LoginDenied() {
            this.isActive = false
        },
        LoginConfirmed({ entities }) {
            this.isActive = true
            const { user } = entities
            this.user = user
        },
        async ClickedLogout() {
            await requester.post('/api/logout')
            window.location.reload(true) // remove cookies
        },
        async Initialized() {
            const { isActive, entities } = await requester.get('/api/sessionStatus')
            if (isActive) {
                emit.LoginConfirmed({ entities })
            }
            else {
                emit.LoginDenied()
            }
        },
        DetectedServerError({ errorName, errorMessage, errorStack }) {
            console.log({ errorName, errorMessage, errorStack })
            alert(`Error detected: ${errorName}: ${errorMessage}`)
        },
    }
})
