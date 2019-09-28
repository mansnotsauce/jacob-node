import { view } from '../framework'
import userStore from '../stores/userStore'

export default view(function EditUser({ match }) {

    let { userId } = match.params
    const user = userStore.users.find(user => user.userId == userId)

    return (
        <section className="pwrstation-view-profile">
            <div className="section-table-name">Profile</div>
            <div className="clear50" />
            <div className="shadowbox">
                <div id="resultbox" />
                    <div className="row">
                        <div className="col-xs-6">
                            <form id="edit-profile-form" action="" method="POST">
                                <input type="hidden" name="id" value="<?php echo $data['account']['user_id']; ?>" required />
                                <div className="h5 greyColor">
                                    <b>Email</b>
                                    <br />{user.email}
                                </div>
                                <div className="clear20" />
                                {/* <?php if($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite" || $_SESSION['role']=="Jr Energy Consultant" || $_SESSION['role']=="Sr Energy Consultant"): ?> */}
                                    <div className="h5 greyColor">
                                        <b>Position</b><br />{/*<?php echo $_SESSION['role']; ?>*/}</div>
                                    <div className="clear20" />
                                    <div className="h5 greyColor"><b>Team</b><br />{/*<?php echo $_SESSION['user']['team']; ?>*/}</div>
                                    <div className="clear20" />
                                {/* <?php else: ?> */}
                                <div className="h5 greyColor">
                                    <b>Position</b>
                                    <br />
                                    <select className="form-control" name="position" required>
                                        <option value=""><em>Select User Role</em></option>
                                        {/* TOOD: ROLES */}
                                    </select>
                                </div>
                                <div className="h5 greyColor">
                                    <b>Team</b>
                                    <button type="button" className="addnewteambtn btn btn-primary">Create New Team</button>
                                    <br />
                                    <select className="form-control" name="team">
                                        <option value=""><em>Select Team</em></option>
                                    </select>
                                </div>
                                <div className="clear20" />
                            {/* <?php endif; ?>*/}
                                <div className="h5 greyColor">
                                    <b>Full Name</b>
                                    <br />
                                    <input type="text" name="name" value="<?php echo $data['account']['Name']; ?>" required className="form-control" />
                                </div>
                                <div className="clear20" />
                                <div className="h5 greyColor">
                                    <b>Phone Number</b>
                                    <br />
                                    <input type="text" name="phone" value="<?php echo $data['account']['Phone']; ?>" className="form-control" />
                                </div>
                                <div className="clear50" />
                                <div className="clear30" />
                                <button className="approveBtn" type="submit">Update</button>
                                <a href="/profile/<?php echo $data['account']['user_id']; ?>"><button className="editBtn" type="button">Cancel</button></a>
                            </form>
                        </div>
                        <div className="col-xs-6 pull-right profile-password-section">
                            <img src="<?php echo $data['prof_pic']; ?>" className="profile-img" id="prev-img" />
                            <div className="clear10" />
                            <form id="uploadpic" action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $data['account']['user_id']; ?>" />
                                Select image to upload:
                                <input type="file" name="fileToUpload" onChange="readURL(this);" id="fileToUpload" required />
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
                                        <input type="password" name="pass1" className="form-control" minlength="6" required />
                                    </div>
                                    <div className="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" name="pass2" className="form-control" minlength="6" required />
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
