const { resolve } = require('path')
const router = require('koa-router')()
const send = require('koa-send')
const constants = require('../constants')
const sessionService = require('./services/sessionService')

router.post('/login', async (ctx) => {
    const { email, password } = ctx.request.body
    try {
        const { sessionKey } = await sessionService.login({ email, password })
        ctx.cookies.set(constants.sessionKeyCookieName, sessionKey, { overwrite: true })
        ctx.body = { success: true }
    }
    catch (error) {
        ctx.body = { success: false }
    }
})

router.post('/logout', async (ctx) => {
    await sessionService.logout(ctx.cookies.get(constants.sessionKeyCookieName))
    ctx.body = { ok: true }
})

router.get('/loginStatus', async (ctx) => {
    const isLoggedIn = await sessionService.isLoggedIn(ctx.cookies.get(constants.sessionKeyCookieName))
    ctx.body = { isLoggedIn }
})

router.get('/assets/:assetType/:resource', async (ctx) => {
    const { assetType, resource } = ctx.params
    console.log("getting hosted resource", resource)
    await send(ctx, resource, { root: resolve(__dirname, '../../assets', assetType) })
})

// router.get('/.well-known/acme-challenge/:resource', async (ctx) => {
//     const { resource } = ctx.params
//     await send(ctx, resource, { root: resolve(__dirname, '../../hosted') })
// })

module.exports = router