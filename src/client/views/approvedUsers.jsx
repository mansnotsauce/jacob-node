import Link from '../components/link'
import ClunkyTable from '../components/clunkyTable'
import { view, emit } from '../framework'
import userStore from '../stores/userStore'

export default view(function ApprovedUsers() {

    const { userSearchString, users, selectedUsers, sortBy, reverseSort } = userStore

    const approvedUsers = users.slice().filter(u => u.isApproved)
    let sortedApprovedUsers = approvedUsers.sort((u1, u2) => Number(u1.userId) < Number(u2.userId) ? 1 : -1) // higher IDs are more recent so we want them first as per the spec
    if (sortBy) {
        sortedApprovedUsers = sortedApprovedUsers.sort((u1, u2) => sortBy ? (u1[sortBy] > u2[sortBy] ? 1 : -1) : 0)
        if (reverseSort) {
            sortedApprovedUsers = sortedApprovedUsers.reverse()
        }
    }
    if (userSearchString) {
        sortedApprovedUsers = sortedApprovedUsers.filter(user => {
            return ['firstName', 'lastName', 'email', 'phoneNumber', 'roleName', 'teamName'].some(field => {
                return user[field] && user[field].toLowerCase().includes(userSearchString.toLowerCase())
            })
        })
    }

    const headOptions = [
        { sortBy: 'lastName', label: 'Name' },
        { sortBy: 'email', label: 'Email' },
        { sortBy: 'teamName', label: 'Team' },
        { sortBy: 'roleName', label: 'Role' },
    ]

    return (
        <ClunkyTable
            title="PWRStation"
            searchString={userSearchString}
            onSearchStringChange={userSearchString => emit.ChangedUserSearchString({ userSearchString })}
            onDelete={() => emit.ClickedDeleteUsers({
                userIds: approvedUsers.filter(u => selectedUsers[u.userId]).map(u => u.userId)
            })}
            tableHead={
                <thead>
                    <tr>
                        <th>
                            <div className="checkbox">
                                <span
                                    param="all"
                                    className={approvedUsers.length && approvedUsers.every(user => selectedUsers[user.userId]) ? 'checked' : ''} 
                                    onClick={() => emit.ClickedSelectAllUsersCheckbox()}
                                />
                            </div>
                        </th>
                        {
                            headOptions.map(({ sortBy, label }) => {
                                let className = 'greyColor'
                                if (sortBy === sortBy) {
                                    className = 'sort greyColor active'
                                }
                                return (
                                    <th
                                        key={sortBy}
                                        className={className}
                                        onClick={() => emit.SelectedUserSortBy({ sortBy })}
                                    >
                                        {label}
                                    </th>
                                )
                            })
                        }
                        <th className="greyColor">Status</th>{/*TODO*/}
                    </tr>
                </thead>
            }
            tableBody={
                <tbody>
                    {
                        sortedApprovedUsers.map((user, idx) => {

                            return (
                                <tr key={user.userId} className={idx % 2 ? '' : 'even-column'}>
                                    <td>
                                        <div className="checkbox">
                                            <span
                                                param="all"
                                                className={selectedUsers[user.userId] ? 'checked' : ''} 
                                                onClick={() => emit.ClickedSelectUserCheckbox({ userId: user.userId })}
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <div className="profile-holder">
                                            <img
                                                src={
                                                    user.profileImageFile ?
                                                        `/hosted/users/${user.userId}/${user.profileImageFile}`
                                                        : '/assets/images/questionMark.png'
                                                }
                                                className="prof-table-img"
                                            />
                                            <span className="username">
                                                {user.firstName} {user.lastName}
                                            </span>
                                            <br />
                                            <span className="action">
                                                <Link to={`/editUser/${user.userId}`}>Edit</Link> | <Link to={`/user/${user.userId}`}>View</Link>
                                            </span>
                                        </div>                              
                                    </td>                                                                
                                    <td className="greyColor">{user.email}</td>
                                    <td className="greyColor">{user.teamName}</td>
                                    <td className="greyColor">{user.roleName || '--'}</td>
                                    <td className="greyColor">Active</td>
                                </tr>
                            )
                        })
                    }
                    {
                        !approvedUsers.length ?
                            <tr>
                                <td colSpan="6">
                                    No records found.
                                </td>
                            </tr>
                        : null
                    }
                </tbody>
            }
        />
    )
})
