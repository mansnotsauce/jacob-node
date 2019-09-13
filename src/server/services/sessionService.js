const dbService = require('./dbService')
const cryptoService = require('./cryptoService')

module.exports = {
    
    async login({ email, password }) {
        const [ user ] = await dbService.query('SELECT userId, passwordSalt, passwordHash FROM user WHERE email = ?', [email])
        if (!user) {
            throw new Error('User does not exist')
        }
        const { userId, passwordSalt, passwordHash } = user
        const pwh = cryptoService.getHash(password, passwordSalt)
        if (pwh === passwordHash) { // login successful
            const sessionKey = cryptoService.getRandomSalt()
            await dbService.query('DELETE FROM session WHERE userId = ?', [userId])
            await dbService.query('INSERT INTO session (sessionKey, userId) values (?, ?)', [sessionKey, userId])
            return { sessionKey }
        }
        else {
            throw new Error('Invalid email/password combination')
        }
    },
    async logout(sessionKey) {
        await dbService.query('DELETE FROM session WHERE sessionKey = ?', [sessionKey])
    },
    async isLoggedIn(sessionKey) {
        const [ session ] = await dbService.query('SELECT * FROM session WHERE sessionKey = ?', [sessionKey])
        return !!session
    },
    async getUser(sessionKey) {
        const [ session ] = await dbService.query('SELECT userId FROM session WHERE sessionKey = ?', [sessionKey])
        if (!session) {
            throw new Error('Could not retrieve user')
        }
        const { userId } = session
        const [ user ] = await dbService.query('SELECT * FROM user WHERE userId = ?', [userId])
        return user
    },
}
