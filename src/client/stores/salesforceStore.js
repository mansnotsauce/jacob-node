import { store, emit } from '../framework'
import requester from '../requester'

export default store({

    stats: null,

    eventListeners: {
        async RouteChanged({ pathname }) {
            if (pathname === '/leaderboard') {
                if (this.stats === null) {
                    //const { stats } = await requester.get('/api/stats')
                    //emit.ReceivedStats({ stats })
                    emit.GetStats({ start: '1000-01-01', end: '9999-12-31' })
                }
            }
        },
        ReceivedStats({ stats }) {
            this.stats = stats
            console.log(stats)
        }
    }
})