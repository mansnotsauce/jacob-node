import { view, emit } from "../framework"
import ClunkyTable from '../components/clunkyTable'
import Link from '../components/link'
import unapprovedUserStore from '../stores/unapprovedUserStore'

export default view(function UnapprovedUsers() {

    const { unapprovedUsers, unapprovedUserSearchString, selectedUnapprovedUsers, sortBy, reverseSort } = unapprovedUserStore
    let sortedUsers = unapprovedUsers.sort((u1, u2) => Number(u1.userId) < Number(u2.userId) ? 1 : -1) // higher IDs are more recent so we want them first as per the spec
    if (sortBy) {
        sortedUsers = sortedUsers.sort((u1, u2) => sortBy ? (u1[sortBy] > u2[sortBy] ? 1 : -1) : 0)
        if (reverseSort) {
            sortedUsers = sortedUsers.reverse()
        }
    }
    if (unapprovedUserSearchString) {
        sortedUsers = sortedUsers.filter(user => {
            return ['firstName', 'lastName', 'email', 'phoneNumber', 'roleName', 'teamName'].some(field => {
                return user[field] && user[field].toLowerCase().includes(unapprovedUserSearchString.toLowerCase())
            })
        })
    }

    const headOptions = [
        { sortBy: 'lastName', label: 'Name' },
        { sortBy: 'email', label: 'Email' },
        { sortBy: 'percentComplete', label: '% Complete' },
    ]

    return (
        <ClunkyTable
            title="Onboarding"
            searchString={unapprovedUserSearchString}
            onSearchStringChange={unapprovedUserSearchString => emit.ChangedUnapprovedUserSearchString({ unapprovedUserSearchString })}
            onDelete={() => emit.ClickedDeleteUsers({
                userIds: unapprovedUsers.filter(u => selectedUnapprovedUsers[u.userId]).map(u => u.userId)
            })}
            tableHead={
                <thead>
                    <tr>
                        <th>
                            <div className="checkbox">
                                <span
                                    param="all"
                                    className={unapprovedUsers.length && unapprovedUsers.every(user => selectedUnapprovedUsers[user.userId]) ? 'checked' : ''} 
                                    onClick={() => emit.ClickedSelectAllUnapprovedUsersCheckbox()}
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
                                        onClick={() => emit.SelectedUnapprovedUserSortBy({ sortBy })}
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
                        sortedUsers.map((user, idx) => {
                            console.log({ user })

                            return (
                                <tr key={user.userId} className={idx % 2 ? '' : 'even-column'}>
                                    <td>
                                        <div className="checkbox">
                                            <span
                                                param="all"
                                                className={selectedUnapprovedUsers[user.userId] ? 'checked' : ''} 
                                                onClick={() => emit.ClickedSelectUnapprovedUserCheckbox({ userId: user.userId })}
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <div className="profile-holder">
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
                                    <td className="greyColor">
                                        <div className="progress">
                                            <div className="progress-bar" style={{ width: `${user.percentComplete || 0}%`}} />
                                        </div>
                                        {user.percentComplete || '0'}%
                                    </td>
                                    <td className="greyColor">
                                        <button className="approve-onb" onClick={() => emit.ClickedApproveUser({ userId: user.userId })}>
                                            Approve
                                        </button>
                                    </td>
                                </tr>
                            )
                        })
                    }
                    {
                        !unapprovedUsers.length ?
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
