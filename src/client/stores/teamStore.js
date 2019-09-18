import { store, emit } from '../framework'
import requester from '../requester'

export default store({

    teams: [],

    eventListeners: {
        async ReceivedUserStatus({ isLoggedIn }) {
            if (isLoggedIn) {
                const teams = await requester.get('/ree')
                emit.ReceivedTeams({ teams })
            }
        },
        ReceivedTeams({ teams }) {
            this.teams = teams
        }
    }
})
