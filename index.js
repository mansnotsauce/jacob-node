const { resolve } = require('path')
const fs = require('fs')
const http = require('http')
const https = require('https')
const config = require('./config')
const koaServer = require('./src/server/koaServer')
const koaRouter = require('./src/server/koaRouter')

const routes = koaRouter.stack.map(i => i.path.split(':')[0])
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
        if (routes.some(route => req.url.indexOf(route) === 0)) {
            koaServerCallback(req, res)
        }
        else {
            bundlerRequestListener(req, res)
        }
    }
}

const server = createServer(options, createServerCallback)

if (!config.isDevMode && config.port !== 80) {
    http.createServer((req, res) => {
        console.log("REQ URL", req.url)
        if (req.url.indexOf('/hosted/') === 0) {
            createServerCallback(req, res)
            return
        }
        res.writeHead(302, {'Location': `https://${config.domainName}` + req.url})
        res.end()
    }).listen(80)
}

server.listen(config.port, () => {
    console.log(`Server listening on port ${config.port}`)
})
