import permissionsUtils from '../../shared/permissionsUtils'
import { store, emit } from '../framework'
import requester from '../requester'

export default store({

    users: [],

    eventListeners: {
        async ReceivedUserStatus({ isLoggedIn, userData }) {
            if (isLoggedIn && userData && permissionsUtils.isAdminRole(userData.role)) {
                const users = await requester.get('/users')
                emit.ReceivedUsers({ users })
            }
        },
        ReceivedUsers({ users }) {
            this.users = users
        },
        async ClickedAddNewUser({
            email,
            role,
            firstName,
            lastName,
            phoneNumber,
            teamId,
        }) {
            const { newUser } = await requester.post('/createUser', {
                email,
                role,
                firstName,
                lastName,
                phoneNumber,
                teamId,
            })
            this.users.push(newUser)
        }
    }
})
