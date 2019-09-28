import mafia from '../../mafia/mafia'
import eventTypes from './eventTypes'

export const { emit, store, view, toJS } = mafia(eventTypes)

const _log = console.log
console.log = (...args) => _log(...args.map(toJS))
