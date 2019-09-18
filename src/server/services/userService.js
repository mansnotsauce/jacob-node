const dbService = require('./dbService')

async function getUser(userId) {
    const [ { firstName, lastName, role, email, phoneNumber, teamId } ] = await dbService.query('SELECT userId, firstName, lastName, role, email, phoneNumber, teamId FROM user WHERE userId = ?', [ userId ])
    return {
        userId,
        firstName,
        lastName,
        role,
        email,
        phoneNumber,
        teamId,
    }
}

async function getUsers() {
    const users = await dbService.query('SELECT userId, firstName, lastName, role, email, phoneNumber, teamId FROM user')
    return users
}

async function createUser({
    email,
    role,
    firstName,
    lastName,
    phoneNumber,
    teamId,
}) {
    const { insertId } = await dbService.query('INSERT INTO user (firstName, lastName, role, email, phoneNumber, teamId) VALUES (?, ?, ?, ?, ?, ?)', [ email, role, firstName, lastName, phoneNumber, teamId ])
    const user = await getUser(insertId)
    return user
}

module.exports = {
    getUser,
    getUsers,
}
