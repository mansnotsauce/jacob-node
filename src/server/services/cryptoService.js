const crypto = require('crypto')

function getRandomSalt() {
    const salt = crypto.randomBytes(16).toString('hex')
    return salt
}

function getHash(password, salt) { 
    const hash = crypto.pbkdf2Sync(password, salt, 1000, 64, `sha512`).toString(`hex`)
    return hash
}

module.exports = {
    getRandomSalt,
    getHash,
}
