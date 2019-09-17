import { store } from '../framework'
import server from '../utils/server'

export default store({

    users: [],

    eventListeners: {
        async ClickedAddNewUser({
            email,
            role,
            firstName,
            lastName,
            phoneNumber,
            teamId,
        }) {
            const user = await server.post('/createUser', {
                email,
                role,
                firstName,
                lastName,
                phoneNumber,
                teamId,
            })
            if (user) {
                this.users.push(user)
            }
        }
    }
})
