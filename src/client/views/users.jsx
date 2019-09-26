import { view } from '../framework'
import userStore from '../stores/userStore'
import teamStore from '../stores/teamStore'

const UserTableBody = view(() => {

    const { users } = userStore
    console.log({ users })

    return (
        <tbody>
            {
                users.map((user, idx) => {

                    const team = teamStore.teams.find(team => team.teamId === user.teamId)
                    const teamName = team ? team.teamName : '--'

                    return (
                        <tr key={user.userId} className={idx % 2 ? '' : 'even-column'}>
                            <td>
                                <div className="checkbox">
                                    <span param={user.userId} />
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
                                        <a href={`/profile/${user.userId}/edit`}>Edit</a> | <a href={`/profile/${user.userId}`}>View</a>
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
        { sortBy: 'role', label: 'Role' },
        { sortBy: 'status', label: 'Status' }, // as of right now this means nothing
    ]

    return (
        <thead>
            <tr>
                <th><div className="checkbox"><span param="all"></span></div></th>
                <th className="greyColor" param="ASC">Name</th>                                
                <th className="greyColor" param="ASC">Email</th>
                <th className="greyColor" param="ASC">Team</th>
                <th className="greyColor" param="ASC">Role</th>
                <th className="greyColor">Status</th>
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
