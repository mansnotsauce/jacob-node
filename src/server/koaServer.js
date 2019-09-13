const { resolve } = require('path')
const Koa = require('koa')
const bodyparser = require('koa-bodyparser')
const config = require('../../config')
const constants = require('../constants')
const router = require('./koaRouter')
const sessionService = require('./services/sessionService')

const app = new Koa()

if (!config.isDevMode) {
    const serve = require('koa-static')
    app.use(serve(resolve(__dirname, '../../build/client')))
}

const openUrls = [ // urls that don't require the user to be logged in
    '/assets',
    '/login',
]

async function authMiddleware(ctx, next) {
    if (!openUrls.some(url => ctx.request.url.indexOf(url) === 0)) {
        const isLoggedIn = await sessionService.isLoggedIn(ctx.cookies.get(constants.sessionKeyCookieName))
        console.log({ isLoggedIn })
        console.log(ctx.cookies.get(constants.sessionKeyCookieName))
        if (!isLoggedIn) {
            ctx.status = 401
            return
        }
    }
    await next()
}

async function errorHandlingMiddleware(ctx, next) {
    try {
        await next()
    }
    catch (error) {
        ctx.status = 200
        ctx.body = {
            error: {
                message: error.message,
                stack: error.stack.split('\n'),
            }
        }
    }
}

app.use(bodyparser())
app.use(authMiddleware)
app.use(errorHandlingMiddleware)
app.use(router.routes())
app.use(router.allowedMethods())

module.exports = app
