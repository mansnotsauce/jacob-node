import permissionsUtils from '../../shared/permissionsUtils'
import Link from '../components/link'
import { view } from '../framework'
import sessionStore from '../stores/sessionStore'
import userStore from '../stores/userStore'

export default view(function AdminDashboard() {

    if (!permissionsUtils.isAdminRole(sessionStore.user.role)) {
        return null
    }

    return (
        <section className="pwrstation-table">
            <div className="section-table-name">PWRStation</div>
            <div className="row">
                {/* <div className="col-xs-6 bulk-buttons">                      
                    <input type="text" name="pwr_search" placeholder="Search...">
                </div> */}
                <div className="col-xs-12 alignRight">
                    <Link to="/createUser/">
                        <button className="addUserBtn">
                            Create New User
                        </button>
                    </Link>
                </div>
            </div>
        </section>
    )
})
