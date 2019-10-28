import { view } from '../framework'
import sessionStore from '../stores/sessionStore'
import ApprovedUsers from './approvedUsers'
import UnapprovedUsers from './unapprovedUsers'

export default view(function AdminDashboard() {

    if (!sessionStore.user.isAdmin) {
        return null
    }

    return (
        <section className="pwrstation-table">
            <ApprovedUsers />
            <div className="clear100" />
            <UnapprovedUsers />
        </section>
    )
})
