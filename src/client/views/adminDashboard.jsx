import Link from '../components/link'
import { view, emit } from '../framework'
import sessionStore from '../stores/sessionStore'
import Users from './users'
import userStore from '../stores/userStore'

export default view(function AdminDashboard() {

    if (!sessionStore.user.isAdmin) {
        return null
    }

    return (
        <section className="pwrstation-table">
            <div className="section-table-name">PWRStation</div>
            <div className="row" style={{ display: 'flex', alignItems: 'center' }}>
                <div className="col-xs-6 bulk-buttons">                      
                    <input
                        type="text"
                        name="pwr_search"
                        placeholder="Search..."
                        onChange={e => emit.ChangedUserSearchString({ userSearchString: e.target.value })}
                        value={userStore.userSearchString}
                    />
                </div>
                <div className="col-xs-12 alignRight">
                    <Link to="/createUser/">
                        <button className="addUserBtn">
                            Create New User
                        </button>
                    </Link>
                </div>
            </div>
            <div className="clear30" />
            <Users />
        </section>
    )
})
