import { Link } from 'react-router-dom'
import { view, emit } from '../framework'
import userStore from '../stores/userStore'
import roleStore from '../stores/roleStore'
import teamStore from '../stores/teamStore'

export default view(function EditUser({ match }) {

    let { userId } = match.params
    const user = userStore.users.find(user => user.userId == userId)

    return (
        <section className="pwrstation-view-profile">
            {
                teamStore.addTeamModalVisible ?
                    <div id="modal-addteam" className="modal-box" style={{ display: 'inherit' }}>
                        <form id="addteamform" action="" method="POST" onSubmit={e => {
                            e.preventDefault()
                            emit.ClickedConfirmCreateTeam({
                                newTeamName: document.getElementById('newTeamName').value,
                            })
                        }}>
                            <p className="result-message"></p>
                            <div className="closebtn">
                                <img src="/assets/images/closebtn.png" />
                            </div>
                            Team Name:
                            <input id="newTeamName" type="text" name="name" className="form-control" placeholder="Team Name Here" required />
                            <button type="submit" className="addTeamBtnConfirm">Create Team</button>
                        </form>
                    </div>
                : null
            }
            <div className="section-table-name">Profile</div>
            <div className="shadowbox">
                <div id="resultbox" />
                    <div className="row">
                        <div className="col-xs-6">
                            <form id="edit-profile-form" action="" method="POST" onSubmit={e => {
                                e.preventDefault()
                                emit.SubmittedUserEdit({
                                    userId,
                                    roleId: document.getElementById('roleSelect').value,
                                    teamId: document.getElementById('teamSelect').value,
                                    firstName: document.getElementById('firstNameInput').value,
                                    lastName: document.getElementById('lastNameInput').value,
                                    phoneNumber: document.getElementById('phoneNumberInput').value,
                                })
                            }}>
                                <input type="hidden" name="id" value={user.email} required />
                                <div className="h5 greyColor">
                                    <b>Email</b>
                                    <br />{user.email}
                                </div>
                                <div className="clear20" />
                                <div className="h5 greyColor">
                                    <b>Position</b>
                                    <br />
                                    <select className="form-control" name="position" id="roleSelect" defaultValue={user.roleId} required>
                                        <option value="">Select User Role</option>
                                        {
                                            roleStore.roles.map(role => {
                                                return (
                                                    <option key={role.roleId} value={role.roleId}>{role.roleName}</option>
                                                )
                                            })
                                        }
                                    </select>
                                </div>
                                <div className="h5 greyColor">
                                    <b>Team</b>
                                    <button type="button" className="addnewteambtn btn btn-primary" onClick={() => emit.ClickedCreateNewTeam()}>Create New Team</button>
                                    <br />
                                    <select className="form-control" name="team" id="teamSelect" defaultValue={user.teamId}>
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
                                <div className="clear20" />
                                <div className="h5 greyColor">
                                    <b>First Name</b>
                                    <br />
                                    <input type="text" name="firstName" defaultValue={user.firstName} required className="form-control" id="firstNameInput" />
                                </div>
                                <div className="h5 greyColor">
                                    <b>Last Name</b>
                                    <br />
                                    <input type="text" name="lastName" defaultValue={user.lastName} required className="form-control" id="lastNameInput" />
                                </div>
                                <div className="clear20" />
                                <div className="h5 greyColor">
                                    <b>Phone Number</b>
                                    <br />
                                    <input type="text" name="phone" id="phoneNumberInput" className="form-control" defaultValue={user.phoneNumber} />
                                </div>
                                <div className="clear50" />
                                <div className="clear30" />
                                <button className="approveBtn" type="submit">Update</button>
                                <Link to={`/user/${user.userId}`}>
                                    <button className="editBtn" type="button">Cancel</button>
                                </Link>
                            </form>
                        </div>
                        <div className="col-xs-6 pull-right profile-password-section">
                            <img src={user.profileImageFile ?
                                                `/hosted/users/${user.userId}/${user.profileImageFile}`
                                                : '/assets/images/questionMark.png'} className="profile-img" id="prev-img" />
                            <div className="clear10" />
                            <form id="uploadpic" action="" method="post" encType="multipart/form-data" onSubmit={e => {
                                e.preventDefault()
                                console.log(e)
                            }}>
                                Select image to upload:
                                <input type="file" name="fileToUpload" onChange={console.log} id="fileToUpload" required />
                                <input type="submit" value="Upload Image" name="submit" />
                            </form>
                            <div className="clear50" />
                            <div className="h5 greyColor">
                                <b id="passload">Password</b>
                                <br />
                                <button className="resetBtn" param="<?php echo $data['account']['user_id']; ?>">
                                    Reset Password
                                </button>
                            </div>
                            <div id="resetbox">
                                <form id="updatepassform" action="" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo $data['account']['user_id']; ?>" />
                                    <p className="error text-danger" />
                                    <div className="form-group">
                                        <label>New Password <span>(Minimum of 6 characters)</span></label>
                                        <input type="password" name="pass1" className="form-control" minLength="6" required />
                                    </div>
                                    <div className="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" name="pass2" className="form-control" minLength="6" required />
                                    </div>
                                    <div className="form-group">
                                        <button type="submit" name="updatepass">Update Password</button>
                                    </div>
                                </form>
                            </div>
                            <div className="clear50" />
                        </div>
                    </div>
            </div>
        </section>
    )
})
