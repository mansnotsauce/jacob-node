import { store, emit } from '../framework'
import server from '../utils/server'

const isLoggedInKey = 'isLoggedIn'
const userIdKey = 'userId'
const userDataKey = 'userData'

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
    userId      : getItem(userIdKey) || null,
    userData    : getItem(userDataKey) || null,

    eventListeners: {
        async ClickedLogin({ email, password }) {
            const { isLoggedIn, userId, userData } = await server.post('/login', { email, password })
            emit.ReceivedUserStatus({ isLoggedIn, userId, userData })
            if (!isLoggedIn) {
                alert('Login attempt failed')
            }

        },
        async ClickedLogout() {
            await server.post('/logout')
            emit.ReceivedUserStatus({
                isLoggedIn  : false,
                userId      : null,
                userData    : null,
            })
        },
        async Initialized() {
            const { isLoggedIn, userId, userData } = await server.get('/userStatus')
            emit.ReceivedUserStatus({ isLoggedIn, userId, userData })
        },
        async ReceivedUserStatus({ isLoggedIn, userId, userData }) {
            this.isLoggedIn = isLoggedIn
            storeItem(isLoggedIn, isLoggedIn)
            if (!isLoggedIn) {
                // TODO: remove cookie
            }
            this.userId = userId
            storeItem(userIdKey, userId)
            this.userData = userData
            storeItem(userDataKey, userData)
        },
    }
})
