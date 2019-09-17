const dbService = require('./dbService')

async function getUserData(userId) {
    const [ { firstName, lastName } ] = await dbService.query('SELECT userId, firstName, lastName FROM user WHERE userId = ?', [userId])
    return {
        userId,
        firstName,
        lastName,
    }
}

module.exports = {
    getUserData,
}
