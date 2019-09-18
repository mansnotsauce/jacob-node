const dbService = require('./dbService')
const cryptoService = require('./cryptoService')
const userService = require('./userService')

module.exports = {
    
    async login({ email, password }) {
        const [ row ] = await dbService.query('SELECT userId, passwordSalt, passwordHash FROM user WHERE email = ?', [email])
        if (!row) {
            throw new Error('User does not exist')
        }
        const { userId, passwordSalt, passwordHash } = row
        const pwh = cryptoService.getHash(password, passwordSalt)
        if (pwh === passwordHash) { // login successful
            const sessionKey = cryptoService.getRandomSalt()
            await dbService.query('DELETE FROM session WHERE userId = ?', [ userId ])
            await dbService.query('INSERT INTO session (sessionKey, userId) values (?, ?)', [ sessionKey, userId ])
            const user = await userService.getUser(userId)
            return { sessionKey, user }
        }
        else {
            throw new Error('Invalid email/password combination')
        }
    },
    async logout(sessionKey) {
        await dbService.query('DELETE FROM session WHERE sessionKey = ?', [sessionKey])
    },
    async getUserStatus(sessionKey) {
        const [ session ] = await dbService.query('SELECT userId FROM session WHERE sessionKey = ?', [sessionKey])
        if (session) {
            const { userId } = session
            const user = await userService.getUser(userId)
            return {
                isLoggedIn: true,
                user,
            }
        }
        else {
            return {
                isLoggedIn: false,
                user: {},
            }
        }
    },
}
