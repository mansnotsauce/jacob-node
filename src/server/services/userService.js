const dbService = require('./dbService')

async function getUser(userId) {
    const [ user ] = await dbService.query(`
        SELECT
            userId,
            firstName,
            lastName,
            email,
            phoneNumber,
            user.teamId,
            profileImageFile,
            team.teamName,
            user.roleId,
            role.roleName,
            role.isAdmin,
            role.isOnboarder
        FROM
            user
        LEFT JOIN
            team
        ON
            user.teamId = team.teamId
        LEFT JOIN
            role
        ON
            user.roleId = role.roleId
        WHERE
            userId = ?
    `, [ userId ])
    return user
}

async function getUsers() {
    const users = await dbService.query(`
        SELECT
            userId,
            firstName,
            lastName,
            email,
            phoneNumber,
            user.teamId,
            profileImageFile,
            team.teamName,
            user.roleId,
            role.roleName,
            role.isAdmin,
            role.isOnboarder
        FROM
            user
        LEFT JOIN
            team
        ON
            user.teamId = team.teamId
        LEFT JOIN
            role
        ON
            user.roleId = role.roleId
    `)
    return users
}

async function createUser({
    email,
    role,
    firstName,
    lastName,
    phoneNumber,
    teamId,
    profileImageFile = null,
}) {
    await dbService.query('INSERT INTO user (firstName, lastName, role, email, phoneNumber, teamId, profileImageFile) VALUES (?, ?, ?, ?, ?, ?)', [ firstName, lastName, role, email, phoneNumber, teamId, profileImageFile ])
}

module.exports = {
    getUser,
    getUsers,
    createUser,
}
