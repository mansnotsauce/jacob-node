import { store, emit } from '../framework'
import requester from '../requester'

export default store({

    teams: [],

    eventListeners: {
        async ReceivedUserStatus({ isLoggedIn, user }) {
            if (isLoggedIn && user.isAdmin || user.isOnboarder) {
                const { teams } = await requester.get('/api/teams')
                emit.ReceivedTeams({ teams })
            }
        },
        ReceivedTeams({ teams }) {
            this.teams = teams
        }
    }
})
