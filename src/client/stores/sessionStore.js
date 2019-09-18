import { store, emit } from '../framework'
import requester from '../requester'

const isLoggedInKey = 'isLoggedIn'
const userKey = 'user'

function storeItem(key, item) {
    if (item === undefined) item = null
    window.localStorage.setItem(key, JSON.stringify(item))
}
function getItem(key) {
    const item = window.localStorage.getItem(key)
    return item ? JSON.parse(item) : null
}

export default store({

    isLoggedIn  : getItem(isLoggedInKey) || null,
    user        : getItem(userKey) || null,

    eventListeners: {
        async ClickedLogin({ email, password }) {
            const { isLoggedIn, user } = await requester.post('/login', { email, password })
            emit.ReceivedUserStatus({ isLoggedIn, user })
            if (!isLoggedIn) {
                alert('Login attempt failed')
            }

        },
        async ClickedLogout() {
            await requester.post('/logout')
            emit.ReceivedUserStatus({
                isLoggedIn  : false,
                user        : {},
            })
        },
        async Initialized() {
            const { isLoggedIn, user } = await requester.get('/userStatus')
            emit.ReceivedUserStatus({ isLoggedIn, user })
        },
        async ReceivedUserStatus({ isLoggedIn, user }) {
            this.isLoggedIn = isLoggedIn
            storeItem(isLoggedIn, isLoggedIn)
            if (!isLoggedIn) {
                // TODO: remove cookie
            }
            this.user = user
            storeItem(userKey, user)
        },
    }
})
