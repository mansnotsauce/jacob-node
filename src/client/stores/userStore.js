import permissionsUtils from '../../shared/permissionsUtils'
import { store, emit } from '../framework'
import requester from '../requester'

export default store({

    users: [],
    addUserRedirectInitiated: false,
    addUserRedirectEngaged: false,

    eventListeners: {
        async ReceivedUserStatus({ isLoggedIn, user }) {
            if (isLoggedIn && user && permissionsUtils.isAdminRole(user.role)) {
                const { users } = await requester.get('/api/users')
                emit.ReceivedUsers({ users })
            }
        },
        ReceivedUsers({ users }) {
            this.users = users
            if (this.addUserRedirectInitiated) {
                this.addUserRedirectEngaged = true
            }
            this.addUserRedirectInitiated = false
        },
        RouteChanged({ pathname }) {
            if (pathname === '/createUser') {
                this.addUserRedirectEngaged = false
            }
        },
        async ClickedAddNewUser({
            email,
            role,
            firstName,
            lastName,
            phoneNumber,
            teamId,
        }) {
            this.addUserRedirectInitiated = true
            const { users } = await requester.post('/api/createUser', {
                email,
                role,
                firstName,
                lastName,
                phoneNumber,
                teamId,
            })
            emit.ReceivedUsers({ users })
        }
    }
})
