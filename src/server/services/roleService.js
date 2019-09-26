const dbService = require('./dbService')

async function getRoles() {
    const roles = await dbService.query('SELECT roleId, roleName, isAdmin, isOnboarder FROM role')
    return roles
}

module.exports = {
    getRoles,
}
