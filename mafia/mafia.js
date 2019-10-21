const { configure, action, extendObservable, isObservable, toJS, autorun, reaction } = require('mobx')
const { observer } = require('mobx-react') // todo: consider testing with version 6

configure({
    enforceActions: 'always'
})

function toJsIfObservable(value) {
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
    // hence it is fine to reference stores in other stores,
    // but it is NOT fine to do dynamic store creation (in the present incarnation of this lib)
    let stores = []
    const emit = {}

    eventTypes.forEach((eventType) => {
        emit[eventType] = action((event) => {
            middleware({
                eventType,
                event,
            }, function next () {
                stores.forEach((store) => {
                    const listener = store.eventListeners[eventType]
                    listener && listener(event)
                })
            })
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

    function deafen (store) {
        stores = stores.filter(s => s !== store)
    }

    function getStores () {
        return stores.slice()
    }

    return {
        emit,
        store,
        view: observer,
        toJS: toJsIfObservable,
        deafen,
        getStores,
        autorun,
        reaction,
    }
}
