const dbService = require('./dbService')

async function getTeams() {
    const teams = await dbService.query('SELECT teamId, teamName FROM team')
    return teams
}

async function createTeam({ newTeamName }) {
    await dbService.query('INSERT INTO team (teamName) VALUES (?)', [ newTeamName ])
}

module.exports = {
    getTeams,
    createTeam,
}
