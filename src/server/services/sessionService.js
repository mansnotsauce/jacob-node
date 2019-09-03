const dbService = require('./dbService')

// logout timeout?
async function markAsActive({ username, password }) {
    const sessionKey = Math.random().toString().slice(2)
    await dbService.run('INSERT INTO session (sessionKey, username, password) VALUES (?, ?, ?)', [sessionKey, username, password])
    return sessionKey
}

module.exports = {
    
    async login({ username, password }) {
        throw new Error("NOT YET IMPLEMENTED: LOGIN")
        const account = await dbService.getRow(`SELECT * FROM account WHERE username = ?`, [username])
        if (!account) {
            return { sessionKey: null }
        }
        if (account.isTestAccount) {
            const sessionKey = await markAsActive({ username, password })
            return { sessionKey }
        }
        await dbService.run('DELETE FROM session WHERE username = ?', [username])
        const loginWasSuccessful = await login({ username, password })
        if (loginWasSuccessful) {
            const sessionKey = await markAsActive({ username, password })
            await dbService.run('INSERT INTO user (username, password) values (?, ?)', [username, password])
            return { sessionKey }
        }
        else {
            return { sessionKey: null }
        }
    },
    async logout(sessionKey) {
        throw new Error("NOT YET IMPLEMENTED: LOGOUT")
        const logoutCleared = await this.isLoggedIn(sessionKey)
        await dbService.run(`DELETE FROM session WHERE sessionKey = $sessionKey`, {
            $sessionKey: sessionKey
        })
        return logoutCleared
    },
    async isLoggedIn(sessionKey) {
        throw new Error("NOT YET IMPLEMENTED: ISLOGGEDIN")
        const user = await this.getUser(sessionKey)
        return !!user
    },
    async getUser(sessionKey) {
        throw new Error("NOT YET IMPLEMENTED: GET USER")
        const row = await dbService.getRow(`SELECT username, password FROM session WHERE sessionKey = $sessionKey`, {
            $sessionKey: sessionKey
        })
        return row || null
    },
}
