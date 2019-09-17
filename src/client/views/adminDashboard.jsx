import permissionsUtils from '../../shared/permissionsUtils'
import Link from '../components/link'
import { view } from '../framework'
import sessionStore from '../stores/sessionStore'
import userStore from '../stores/userStore'

export default view(function AdminDashboard() {

    if (!sessionStore.userData || !permissionsUtils.isAdminRole(sessionStore.userData.role)) {
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
                    <Link to="/addUser/">
                        <button className="addUserBtn">
                            Create New User
                        </button>
                    </Link>
                </div>
            </div>
        </section>
    )
})
