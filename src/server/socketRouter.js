const constants = require('../shared/constants')
const sessionService = require('./services/sessionService')
const userService = require('./services/userService')

const listeners = {

    ClickedDeleteUsers: {
        auth: user => user.isAdmin,
        async cb ({ user, event, emitBack }) {
            const { userIds } = event
            for (const userId of userIds) {
                if (userId === user.userId) {
                    continue // do not delete the user making the request XD
                }
                await userService.deleteUser({ userId })
            }
            const users = await userService.getUsers()
            emitBack.ReceivedUsers({ users })
        }
    },

}

module.exports = {
    getServerSubscriptionEventTypes: () => Object.keys(listeners),
    dispatch: async ({ eventType, ctx, event, emitBack, emitToId }) => {
        const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
        const { auth, cb } = listeners[eventType]
        if (!auth(user)) {
            return ctx.throw(401, 'Unauthorized')
        }
        await cb({ user, event, emitBack, emitToId })
    },
}
