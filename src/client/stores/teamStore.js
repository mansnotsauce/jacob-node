import { store } from '../framework'

export default store({

    teams: [],

    eventListeners: {
        LoginConfirmed({ entities }) {
            const { teams } = entities
            this.teams = teams
        }
    }
})
