require('./scripts/prebuild') // for side-effects

const { resolve } = require('path')
const fs = require('fs')
const http = require('http')
const https = require('https')
const config = require('./config')
const koaServer = require('./src/server/koaServer')
const socketServer = require('./src/server/socketServer')

const koaServerCallback = koaServer.callback()

const createServer = config.isDevMode ? http.createServer : https.createServer

// might not want this if we use nginx
const options = config.isDevMode ? {} : {
    key: fs.readFileSync(`/etc/letsencrypt/live/${config.domainName}/privkey.pem`),
    cert: fs.readFileSync(`/etc/letsencrypt/live/${config.domainName}/cert.pem`),
}

let createServerCallback = koaServerCallback

if (config.isDevMode) {
    
    // support parcel bundler callbacks
    
    const Bundler = require('parcel-bundler')
    const indexPath = resolve(__dirname, './index.html')
    const outDir = resolve(__dirname, './tmp/parcel') // bundler spits out static file for some reason
    const bundler = new Bundler(indexPath, { outDir, sourceMaps: true })
    
    const bundlerRequestListener = bundler.middleware()
    
    createServerCallback = (req, res) => {
        if ([
            '/api/',
            '/assets/',
            '/hosted/',
        ].some(route => req.url.indexOf(route) === 0)) {
            koaServerCallback(req, res)
        }
        else {
            bundlerRequestListener(req, res)
        }
    }
}

const server = createServer(options, createServerCallback)

// redirect to https
if (!config.isDevMode && config.port !== 80) {
    http.createServer((req, res) => {
        console.log("REQ URL", req.url)
        // if (req.url.indexOf('/hosted/') === 0) { // allow access to hosted resources over http
        //     createServerCallback(req, res)
        //     return
        // }
        res.writeHead(302, {'Location': `https://${config.domainName}` + req.url})
        res.end()
    }).listen(80)
}

socketServer.initialize(server)

server.listen(config.port, () => {
    console.log(`Server listening on port ${config.port}`)
})
