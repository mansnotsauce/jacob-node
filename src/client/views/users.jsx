import { view } from '../framework'
import userStore from '../stores/userStore'
import teamStore from '../stores/teamStore'
import { getRoleLabel } from '../../shared/permissionsUtils'

// TODO: why is the table body define twice??

export default view(function Users() {

    const { users } = userStore
    console.log({ users })

    return (
        <div className="table-container admin-dashboard">
            <div className="table-header">
                <table className="table" param="pwrstation">
                    <thead>
                        <tr>
                            <th><div className="checkbox"><span param="all"></span></div></th>
                            <th className="sort greyColor" param="ASC">Name</th>                                
                            <th className="sort greyColor" param="ASC">Email</th>
                            <th className="sort greyColor" param="ASC">Team</th>
                            <th className="sort greyColor" param="ASC">Role</th>
                            <th className="greyColor">Status</th>
                        </tr>
                    </thead>
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
                                                    src={`/hosted/users/${user.userId}/profilePicture.png`} className="prof-table-img"
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
                                        <td className="greyColor">{getRoleLabel(user.role) || '--'}</td>
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
                </table>
            </div>
            <div className="table-content">
                <table className="table">
                    <thead>
                        <tr>
                            <th><div className="checkbox"><span param="all"></span></div></th>
                            <th className="greyColor">Name</th>                                
                            <th className="colorBlue">Email</th>
                            <th className="greyColor">Team</th>
                            <th className="greyColor">Role</th>
                            <th className="greyColor">Status</th>
                        </tr>
                    </thead>
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
                                                    src={`/hosted/users/${user.userId}/profilePicture.png`} className="prof-table-img"
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
                                        <td className="greyColor">{getRoleLabel(user.role) || '--'}</td>
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
                </table>
            </div>
        </div>
    )
})
