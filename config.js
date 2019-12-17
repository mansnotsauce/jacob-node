const isDevMode = process.env.NODE_ENV !== 'production'

// production config values go here
const config = {

    isDevMode,

    domainName  : 'horizonpwr.com',
    port        : isDevMode ? 8080 : 443,

    dbHost      : 'localhost',
    dbUser      : 'root',
    dbPassword  : 'Claire69',
    dbName      : 'horizonpwr',
}

module.exports = Object.assign(
    config,
    isDevMode ? require('./config-dev') : {}
)
