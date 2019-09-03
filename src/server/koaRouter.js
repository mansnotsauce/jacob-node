const { resolve } = require('path')
const router = require('koa-router')()
const send = require('koa-send')
const constants = require('../constants')
const sessionService = require('./services/sessionService')

router.post('/login', async (ctx) => {
    const { username, password } = ctx.request.body
    
    const { sessionKey } = await sessionService.login({ username, password })
    
    if (!sessionKey) {
        ctx.body = { success: false }
        return
    }
    
    // ctx.cookies.set(constants.sessionKeyCookieName, sessionKey, { httpOnly: false, overwrite: true })
    // console.log(ctx.cookies.get(constants.sessionKeyCookieName, sessionKey))
    console.log(constants.sessionKeyCookieName, sessionKey, { httpOnly: false, overwrite: true })
    // ctx.cookies.set(constants.sessionKeyCookieName.toString(), sessionKey.toString(), { httpOnly: false, overwrite: true })
    ctx.cookies.set(constants.sessionKeyCookieName, sessionKey, { overwrite: true })
    ctx.body = { success: true }
})

router.post('/logout', async (ctx) => {
    const logoutCleared = await sessionService.logout(ctx.cookies.get(constants.sessionKeyCookieName))
    ctx.body = { success: logoutCleared }
})

router.get('/hosted/:resource', async (ctx) => {
    const { resource } = ctx.params
    console.log("getting hosted resource", resource)
    await send(ctx, resource, { root: resolve(__dirname, '../../hosted') })
})

// router.get('/.well-known/acme-challenge/:resource', async (ctx) => {
//     const { resource } = ctx.params
//     await send(ctx, resource, { root: resolve(__dirname, '../../hosted') })
// })

module.exports = router