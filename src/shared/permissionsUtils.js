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

function getRoleLabel(role) {
    return ({
        CEO_ROLE                        : 'CEO',
        VP_ROLE                         : 'VP',
        SALES_SUPPORT_ROLE              : 'Sales Support',
        ADMIN_ROLE                      : 'Administrator',
        MANAGER_ROLE                    : 'Manager',
        REGIONAL_MANAGER_ROLE           : 'Regional Manager',
        FIELD_MARKETER_ROLE             : 'Field Marketer',
        FIELD_MARKETER_ELITE_ROLE       : 'Elite Field Marketer',
        JUNIOR_ENERGY_CONSULTANT_ROLE   : 'Junior Energy Consultant',
        SENIOR_ENERGY_CONSULTANT_ROLE   : 'Senior Energy Consultant',
    })[role] || null
}

module.exports = {
    isAdminRole,
    isOnboarderRole,
    getRoleLabel,
}
