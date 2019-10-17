const listeners = {
    async RouteChanged({ ctx, event, emitBack, emitToId }) {
        console.log("REEE", event)
        
        emitBack.Test('ayyyy lmao')
    }
}

const getServerSubscriptionEventTypes = () => {
    return Object.keys(listeners)
}

const dispatch = ({ eventType, ctx, event, emitBack, emitToId }) => {
    const listener = listeners[eventType]
    listener && listener({ ctx, event, emitBack, emitToId })
}

module.exports = {
    getServerSubscriptionEventTypes,
    dispatch,
}
