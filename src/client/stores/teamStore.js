import { store } from '../framework'

export default store({

    teams: [],

    eventListeners: {
        async ReceivedUserStatus({ isLoggedIn }) {
            if (isLoggedIn) {
                const teams = await server.get('/teams')
                emit.ReceivedTeams({ teams })
            }
        },
        ReceivedTeams({ teams }) {
            this.teams = teams
        }
    }
})
