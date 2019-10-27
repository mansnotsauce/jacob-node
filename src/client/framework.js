import ReconnectingWebSocket from 'reconnecting-websocket'
import mafia from '../../mafia/mafia'
import { eventTypesSubscribedToByServer } from './serverGenerated'

const socketUrl = `${window.location.protocol === 'https:' ? 'wss' : 'ws'}://${window.location.host}/socket`
const socket = new ReconnectingWebSocket(socketUrl)

export const { emit, store, view, toJS } = mafia(({ eventType, event }, next) => {

    if (eventTypesSubscribedToByServer.includes(eventType)) {
        socket.send(JSON.stringify({
            topic: 'ClientEventEmitted',
            content: {
                eventType,
                event,
            },
        }, null, 4))
    }

    next()
})

socket.addEventListener('message', (message) => {
    const { topic, content } = JSON.parse(message.data)
    if (topic === 'ServerEventEmitted') {
        const { eventType, event } = content
        emit[eventType](event)
    }
    else {
        console.log('Bad message:', message)
        throw new Error(`Unrecognized message topic: "${topic}"`)
    }
})
