const { configure, action, extendObservable, isObservable, toJS } = require('mobx')
const { observer } = require('mobx-react')

configure({
    enforceActions: 'always'
})

const view = observer

function destorify(value) {
    try {
        if (isObservable(value)) {
            return toJS(value)
        }
    }
    catch (error) {
        // do nothing
    }
    return value
}

function isObject (value) {
    return !!value && typeof value === 'object' && !Array.isArray(value)
}

module.exports = function Mafia (eventTypes, middleware = function (_, next) { next() }) {

    if (!Array.isArray(eventTypes) || eventTypes.some(eventType => typeof eventType !== 'string')) {
        throw new Error('first argument to Mafia ("eventTypes") must be an array of strings')
    }
    if (typeof middleware !== 'function') {
        throw new Error('second optional argument to Mafia ("middleware") must be a function')
    }

    // events are emitted to stores in the order that stores are appended to this array.
    // since the leafs of the store dependency tree are required/imported first,
    // we assume that stores' dependencies will always be emitted to first.
    // hence it is fine to reference stores in other stores.
    // (just don't do any dynamic `require`ing.)
    const stores = []
    const emit = {}

    let emitCount = 1
    eventTypes.forEach((eventType) => {
        emit[eventType] = action((event) => {
            if (emitCount === 1) {
                eventTypes
                    .filter(eventType => !stores.some(store => store.eventListeners[eventType]))
                    .forEach(eventType => console.warn(`Event type "${eventType}" is not used on any store.`))
            }
            stores.forEach((store) => {
                const listener = store.eventListeners[eventType]
                if (!listener) {
                    return
                }
                middleware({
                    emitCount,
                    eventType,
                    event,
                    store,
                    listener
                }, function next () {
                    store.eventListeners[eventType](event)
                })
            })
            emitCount++
        })
    })

    function store (storeBase) {

        if (!isObject(storeBase)) {
            throw new Error('first argument to store ("storeBase") must be an object')
        }
        if (!isObject(storeBase.eventListeners)) {
            throw new Error('first argument to store ("storeBase") must contain an "eventListeners" property which is an object')
        }

        const store = {}
        Object.entries(storeBase.eventListeners).forEach(([eventType, listener]) => {
            if (!emit[eventType]) {
                throw new Error(`eventListener key { ${eventType} } is not a valid event type!!`)
            }
            if (typeof listener !== 'function') {
                throw new Error(`eventListener with key { ${eventType} } is not a function!!`)
            }
            storeBase.eventListeners[eventType] = listener.bind(store) // autobind
        })
        extendObservable(store, storeBase)

        stores.push(store)
        return store
    }

    return {
        emit,
        store,
        view,
        destorify,
    }
}
