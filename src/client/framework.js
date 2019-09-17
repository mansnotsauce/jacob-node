import mafia from '../../mafia/mafia'
import eventTypes from './eventTypes'

export const { emit, store, view, destorify } = mafia(eventTypes)
