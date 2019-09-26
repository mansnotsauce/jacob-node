import { store, emit } from '../framework'
import requester from '../requester'

export default store({
    roles: [],
    eventListeners: {
        async Initialized() {
            const { roles } = await requester.get('/api/roles')
            emit.ReceivedRoles({ roles })
        },
        ReceivedRoles({ roles }) {
            this.roles = roles
        }
    }
})
