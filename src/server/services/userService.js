const dbService = require('./dbService')

async function getUserData(userId) {
    const [ { firstName, lastName, role } ] = await dbService.query('SELECT userId, firstName, lastName, role, email, phoneNumber FROM user WHERE userId = ?', [userId])
    return {
        userId,
        firstName,
        lastName,
        role,
    }
}

async function getUsers() {
    const users = await dbService.query('SELECT userId, firstName, lastName, role, email, phoneNumber FROM user')
    return users
}

module.exports = {
    getUserData,
    getUsers,
}
