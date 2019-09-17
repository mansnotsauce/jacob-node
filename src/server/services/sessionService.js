const dbService = require('./dbService')
const cryptoService = require('./cryptoService')
const userService = require('./userService')

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
            const userData = await userService.getUserData(userId)
            return { sessionKey, userId, userData }
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
            const userData = await userService.getUserData(userId)
            return {
                isLoggedIn: true,
                userId,
                userData,
            }
        }
        else {
            return {
                isLoggedIn: false,
                userId: null,
                userData: null,
            }
        }
    },
}
