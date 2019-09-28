import { store } from '../framework'

export default store({
    roles: [],
    eventListeners: {
        LoginConfirmed({ entities }) {
            const { roles } = entities
            this.roles = roles
        }
    }
})
