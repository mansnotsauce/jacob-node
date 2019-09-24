import { Redirect } from 'react-router-dom'
import { view, emit } from '../framework'
import constants from '../../shared/constants'
import teamStore from '../stores/teamStore'
import userStore from '../stores/userStore'

export default view(function CreateUser() {

    if (userStore.addUserRedirectEngaged) {
        return <Redirect to="/home" />
    }

    return (
        <section className="pwrstation-table">
            <div className="section-table-name">Add New User</div>
            <div className="clear50"></div>   
            <div className="shadowbox">
                <div id="resultbox"></div>
                <form id="add-user-form" action="" onSubmit={e => {
                    e.preventDefault()
                    emit.ClickedAddNewUser({
                        email       : document.getElementById('newUserEmail').value,
                        role        : document.getElementById('newUserRole').value,
                        firstName   : document.getElementById('newUserFirstName').value,
                        lastName    : document.getElementById('newUserLastName').value,
                        phoneNumber : document.getElementById('newUserPhoneNumber').value,
                        teamId      : document.getElementById('newUserTeamId').value,
                    })
                }}>
                    <div className="row">  
                        <div className="col-md-6">
                            <div className="form-group">
                                <label className="p greyColor">
                                    <strong>Email<span className="text-danger">*</span></strong>
                                </label>
                                <input
                                    id="newUserEmail"
                                    type="email"
                                    name="email"
                                    className="form-control"
                                    placeholder="Enter your Email Address" required
                                />
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div className="form-group">
                                <label className="p greyColor"><strong>Role<span className="text-danger">*</span></strong></label>
                                <select id="newUserRole" className="form-control" name="position" required>
                                    <option value="">Select User Role</option>
                                    <option value={constants.FIELD_MARKETER_ROLE}>Field Marketer</option>
                                    <option value={constants.FIELD_MARKETER_ELITE_ROLE}>Field Marketer Elite</option>
                                    <option value={constants.JUNIOR_ENERGY_CONSULTANT_ROLE}>Junior Energy Consultant</option>
                                    <option value={constants.SENIOR_ENERGY_CONSULTANT_ROLE}>Senior Energy Consultant</option>
                                    <option value={constants.SALES_SUPPORT_ROLE}>Sales Support</option>
                                    <option value={constants.MANAGER_ROLE}>Manager</option>
                                    <option value={constants.REGIONAL_MANAGER_ROLE}>Regional Manager</option>
                                    <option value={constants.VP_ROLE}>VP of Sales</option>
                                    <option value={constants.CEO_ROLE}>CEO</option>
                                </select>
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div className="form-group">
                                <label className="p greyColor">
                                    <strong>First Name<span className="text-danger">*</span></strong>
                                </label>
                                <input id="newUserFirstName" type="text" name="first_name" className="form-control" placeholder="Enter your First Name" required />
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div className="form-group">
                                <label className="p greyColor">
                                    <strong>Last Name<span className="text-danger">*</span></strong>
                                </label>
                                <input  id="newUserLastName" type="text" name="last_name" className="form-control" placeholder="Enter your Last Name" required />
                            </div>
                        </div>                                        
                        <div className="col-md-6">
                            <div className="form-group">
                                <label className="p greyColor">
                                    <strong>Phone Number<span className="text-danger">*</span></strong>
                                </label>
                                <input id="newUserPhoneNumber" type="text" name="phone" className="form-control" placeholder="Enter your Phone Number" required/>
                            </div>
                        </div>
                        <div className="col-md-6">
                            <div className="form-group">
                                <label className="p greyColor"><strong>Team</strong></label>
                                <select id="newUserTeamId" className="form-control" name="team">
                                    <option value="">Select Team</option>
                                    {
                                        teamStore.teams.map(team => {
                                            return (
                                                <option key={team.teamId} value={team.teamId}>
                                                    {team.teamName}
                                                </option>
                                            )
                                        })
                                    }
                                </select>
                            </div>
                        </div>
                        <div className="col-xs-12">
                            <div className="clear30"></div>
                            <button type="submit" name="submit" className="submitBtn">Add New User</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>  
    )
})
