const isDevMode = process.env.NODE_ENV !== 'production'

const config = {
    
    // production config values go here
    
    isDevMode,

    domainName: 'indexr.io',
    
    port: isDevMode ? 8080 : 443,

}

module.exports = Object.assign(
    config,
    isDevMode ? require('./config-dev') : {}
)
