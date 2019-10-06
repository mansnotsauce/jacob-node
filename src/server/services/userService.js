const jetpack = require('fs-jetpack')
const path = require('path')
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
    roleId,
    firstName,
    lastName,
    phoneNumber,
    teamId,
    profileImageFile = null,
}) {
    await dbService.query('INSERT INTO user (firstName, lastName, roleId, email, phoneNumber, teamId, profileImageFile) VALUES (?, ?, ?, ?, ?, ?, ?)', [ firstName, lastName, roleId, email, phoneNumber, teamId, profileImageFile ])
}

async function editUser({
    userId,
    roleId,
    teamId,
    firstName,
    lastName,
    phoneNumber,
}) {
    await dbService.query('UPDATE user SET roleId = ?, teamId = ?, firstName = ?, lastName = ?, phoneNumber = ? WHERE userId = ?', [ roleId, teamId, firstName, lastName, phoneNumber, userId ])
}

async function setProfileImage({ userId, filename, fileBuffer }) {
    // maybe break out hosted dir location into a util or a service or osmething?
    jetpack.write(path.resolve(__dirname, `../../../hosted/users/${userId}/${filename}`), fileBuffer)
    await dbService.query('UPDATE user SET profileImageFile = ?', [filename])
}

module.exports = {
    getUser,
    getUsers,
    createUser,
    editUser,
    setProfileImage,
}
