const url = require('url')
const WebSocket = require('ws')
const koaServer = require('./koaServer')
const socketRouter = require('./socketRouter')

const eventTypesSubscribedTo = socketRouter.getEventTypesSubscribedTo()

function noop() {}

function heartbeat() {
    this.isAlive = true;
}

function initialize(server) {

    const wss = new WebSocket.Server({ noServer: true })
    server.on('upgrade', (request, socket, head) => {
        const pathname = url.parse(request.url).pathname
        if (pathname === '/socket') {
            wss.handleUpgrade(request, socket, head, (ws) => {
                wss.emit('connection', ws, request, head)
            })
        }
        else {
            socket.destroy()
        }
    })
    wss.on('connection', (ws, request, head) => {
        ws.isAlive = true
        ws.on('pong', heartbeat)
        const koaContext = koaServer.createContext.call(koaServer, request, head)
        // ws.send('hello')
        const _emitBack = {}
        const emitBack = new Proxy(_emitBack, {
            get(_emitBack, eventType) {
                if (eventTypesSubscribedTo.includes(eventType)) {
                    console.log(`The server is subscribed to events with type "${eventType}", and so is not allowed to emit them (to prevent an infinite loop)`)
                    return
                }
                if (!_emitBack[eventType]) {
                    _emitBack[eventType] = (event) => {
                        ws.send(JSON.stringify({
                            topic: 'ServerEventEmitted',
                            content: {
                                eventType,
                                event,
                            }
                        }))
                    }
                }
                return _emitBack[eventType]
            }
        })

        ws.on('message', async (message) => {
            const { topic, content } = JSON.parse(message)
            if (topic === 'ClientEventEmitted') {
                const { eventType, event } = content
                try {
                    await socketRouter.dispatch({
                        eventType,
                        ctx: koaContext,
                        event,
                        emitBack,
                        emitToId: {}, // TODO
                    })
                }
                catch (error) {
                    emitBack.DetectedServerError({
                        errorName   : error.name,
                        errorMessage: error.message,
                        errorStack  : error.stack.split('\n'),
                    })
                }
            }
            else {
                console.log(`Unrecognized topic: "${topic}"`)
            }
        })

        // wss.clients.forEach(function each(client) {
        // // client === ws if it's self
        //     if (client.readyState === WebSocket.OPEN) {
        //     client.send(data);
        //     }
        // });
    })

    setInterval(function ping() {
        wss.clients.forEach(function each(ws) {
            if (ws.isAlive === false) {
                return ws.terminate()
            }
            ws.isAlive = false
            ws.ping(noop)
        })
    }, 30000)
}

module.exports = {
    initialize,
}
