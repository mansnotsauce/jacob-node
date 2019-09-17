const dbService = require('./dbService')

async function getUserData(userId) {
    const [ { firstName, lastName, role } ] = await dbService.query('SELECT userId, firstName, lastName, role FROM user WHERE userId = ?', [userId])
    return {
        userId,
        firstName,
        lastName,
        role,
    }
}

module.exports = {
    getUserData,
}
