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
                    emit.GetStats()
                }
            }
        },
        ReceivedStats({ stats }) {
            this.stats = stats
            console.log(stats)
        }
    }
})