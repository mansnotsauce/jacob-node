const jetpack = require('fs-jetpack')
const path = require('path')
const config = require('../../../config')
const dbService = require('./dbService')
const cryptoService = require('./cryptoService')
const emailService = require('./emailService')

const getUsersQuery = `
    SELECT
        userId,
        firstName,
        lastName,
        email,
        phoneNumber,
        approved,
        percentComplete,
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
        deleted <> 1
`

async function getUser(userId) {
    const [ user ] = await dbService.query(`
    
        ${getUsersQuery}
        AND userId = ?

    `, [ userId ])
    return user
}

async function getUsers() {
    const users = await dbService.query(getUsersQuery)
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
    const { insertId } = await dbService.query('INSERT INTO user (firstName, lastName, roleId, email, phoneNumber, teamId, profileImageFile) VALUES (?, ?, ?, ?, ?, ?, ?)', [ firstName, lastName, roleId, email, phoneNumber, teamId, profileImageFile ])
    const userId = insertId
    await resetPassword({ userId })
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

async function deleteUser({ userId }) {
    await dbService.query('UPDATE user SET deleted = 1 WHERE userId = ?', [userId])
}

async function setProfileImage({ userId, filename, fileBuffer }) {
    // maybe break out hosted dir location into a util or a service or osmething?
    jetpack.write(path.resolve(__dirname, `../../../hosted/users/${userId}/${filename}`), fileBuffer)
    await dbService.query('UPDATE user SET profileImageFile = ?', [filename])
}

async function resetPassword({ userId }) {
    const [ row ] = await dbService.query('SELECT email FROM user WHERE userId = ?', [userId])
    if (!row) {
        throw new Error(`User with id "${userId}" could not be found`)
    }
    const { email } = row
    const salt = cryptoService.getRandomSalt()
    const password = cryptoService.getRandomSalt()
    const hash = cryptoService.getHash(password, salt)
    await dbService.query('UPDATE user SET passwordHash = ?, passwordSalt = ? WHERE userId = ?', [hash, salt, userId])
    await emailService.sendEmail({
        from: 'noreply@' + config.domainName,
        to: email,
        subject: 'password update',
        body: `
Your password has been updated.

Your new password is: ${password}
`
    })
}

module.exports = {
    getUser,
    getUsers,
    createUser,
    editUser,
    setProfileImage,
    resetPassword,
    deleteUser,
}
