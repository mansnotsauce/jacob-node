import { Link } from 'react-router-dom'
import { view, emit } from '../framework'
import userStore from '../stores/userStore'
import teamStore from '../stores/teamStore'

const UserTableBody = view(() => {

    const { users, sortBy, reverseSort, userSearchString } = userStore

    let sortedUsers = users.slice().sort((u1, u2) => Number(u1.userId) < Number(u2.userId) ? 1 : -1) // higher IDs are more recent so we want them first as per the spec
    if (sortBy) {
        sortedUsers = sortedUsers.sort((u1, u2) => sortBy ? (u1[sortBy] > u2[sortBy] ? 1 : -1) : 0)
        if (reverseSort) {
            sortedUsers = sortedUsers.reverse()
        }
    }
    if (userSearchString) {
        sortedUsers = sortedUsers.filter(user => {
            return ['firstName', 'lastName', 'email', 'phoneNumber', 'roleName', 'teamName'].some(field => {
                return user[field] && user[field].toLowerCase().includes(userSearchString.toLowerCase())
            })
        })
    }

    return (
        <tbody>
            {
                sortedUsers.map((user, idx) => {

                    const team = teamStore.teams.find(team => team.teamId === user.teamId)
                    const teamName = team ? team.teamName : '--'

                    return (
                        <tr key={user.userId} className={idx % 2 ? '' : 'even-column'}>
                            <td>
                                <div className="checkbox">
                                    <span
                                        param="all"
                                        className={userStore.selectedUsers[user.userId] ? 'checked' : ''} 
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
                            <td className="greyColor">{teamName}</td>
                            <td className="greyColor">{user.roleName || '--'}</td>
                            <td className="greyColor">--{/*TODO*/}</td>
                        </tr>
                    )
                })
            }
            {
                !users.length ?
                    <tr>
                        <td colSpan="6">
                            No records found.
                        </td>
                    </tr>
                : null
            }
        </tbody>
    )
})

const UserTableHead = view(() => {


    const options = [
        { sortBy: 'lastName', label: 'Name' },
        { sortBy: 'email', label: 'Email' },
        { sortBy: 'teamName', label: 'Team' },
        { sortBy: 'roleName', label: 'Role' },
    ]

    return (
        <thead>
            <tr>
                <th>
                    <div className="checkbox">
                        <span
                            param="all"
                            className={userStore.users.every(user => userStore.selectedUsers[user.userId]) ? 'checked' : ''} 
                            onClick={() => emit.ClickedSelectAllUsersCheckbox()}
                        />
                    </div>
                </th>
                {
                    options.map(({ sortBy, label }) => {
                        let className = 'greyColor'
                        if (userStore.sortBy === sortBy) {
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
    )
})

export default function Users() {

    // The grid layout that was used before requires the head and body to be define twice.
    // I think it's necessary for spacing purposes?
    // Don't ask me why but it works and looks alright, so leaving it for now.

    return (
        <div className="table-container admin-dashboard">
            <div className="table-header">
                <table className="table" param="pwrstation">
                    <UserTableHead />
                    <UserTableBody />
                </table>
            </div>
            <div className="table-content">
                <table className="table">
                    <UserTableHead />
                    <UserTableBody />
                </table>
            </div>
        </div>
    )
}
