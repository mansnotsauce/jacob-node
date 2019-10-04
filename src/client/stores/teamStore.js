import { store, emit } from '../framework'
import requester from '../requester'

export default store({

    teams: [],
    addTeamModalVisible: false,

    eventListeners: {
        LoginConfirmed({ entities }) {
            const { teams } = entities
            this.teams = teams
        },
        ReceivedTeams({ teams }) {
            this.teams = teams
        },
        ClickedCreateNewTeam() {
            this.addTeamModalVisible = true
        },
        async ClickedConfirmCreateTeam({ newTeamName }) {
            this.addTeamModalVisible = false
            const { teams } = await requester.post('/api/createTeam', { newTeamName })
            emit.ReceivedTeams({ teams })
        },
    }
})
