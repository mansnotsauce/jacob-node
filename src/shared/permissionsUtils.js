const constants = require('./constants')

function isAdminRole(role) {
    return [
        constants.CEO_ROLE,
        constants.VP_ROLE,
        constants.SALES_SUPPORT_ROLE,
        constants.ADMIN_ROLE,
    ].includes(role)
}

function isOnboarderRole(role) {
    return isAdminRole(role) || [
        constants.MANAGER_ROLE,
        constants.REGIONAL_MANAGER_ROLE
    ].includes(role)
}

module.exports = {
    isAdminRole,
    isOnboarderRole,
}
