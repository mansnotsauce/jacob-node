import Link from '../components/link'
import ClunkyTable from '../components/clunkyTable'
import { view, emit } from '../framework'
import userStore from '../stores/userStore'
import salesforceStore from '../stores/salesforceStore'

export default view(function ApprovedUsers() {
    const { stats } = salesforceStore
    return (
        <div>{ stats }</div>
    )
})