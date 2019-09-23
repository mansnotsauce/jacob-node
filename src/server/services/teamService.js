const dbService = require('./dbService')

async function getTeams() {
    const teams = await dbService.query('SELECT teamId, teamName FROM team')
    return teams
}

module.exports = {
    getTeams,
}
