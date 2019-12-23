const constants = require('../shared/constants')
const sessionService = require('./services/sessionService')
const userService = require('./services/userService')
const dbService = require('./services/dbService')
const salesforceService = require('./services/salesforceService')

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

    ClickedApproveUser: {
        auth: user => user.isAdmin,
        async cb ({ event, emitBack }) {
            const { userId } = event
            await dbService.query(`UPDATE user SET isApproved = 1 WHERE userId = ?`, [userId])
            const users = await userService.getUsers()
            emitBack.ReceivedUsers({ users })
        }
    },

    GetStats: {
        auth: user => user.isApproved,
        async cb ({ event, emitBack }) {
            const stats = await salesforceService.getStats(event)
            console.log(stats)
            emitBack.ReceivedStats({ stats })
        }
    },

}

module.exports = {
    getEventTypesSubscribedTo: () => Object.keys(listeners),
    dispatch: async ({ eventType, ctx, event, emitBack, emitToId }) => {
        const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
        const { auth, cb } = listeners[eventType]
        if (!auth(user)) {
            return ctx.throw(401, 'Unauthorized')
        }
        await cb({ user, event, emitBack, emitToId })
    },
}
