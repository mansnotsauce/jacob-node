const { resolve } = require('path')
const router = require('koa-router')()
const send = require('koa-send')
const constants = require('../shared/constants')
const sessionService = require('./services/sessionService')

router.post('/login', async (ctx) => {
    const { email, password } = ctx.request.body
    try {
        const { sessionKey, userId, userData } = await sessionService.login({ email, password })
        ctx.cookies.set(constants.SESSION_KEY_COOKIE_NAME, sessionKey, { overwrite: true })
        ctx.body = {
            isLoggedIn: true,
            userId,
            userData,
        }
    }
    catch (error) {
        ctx.body = {
            isLoggedIn: false,
            userId: null,
            userData: null,
        }
    }
})

router.post('/logout', async (ctx) => {
    await sessionService.logout(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    ctx.body = { ok: true }
})

router.get('/userStatus', async (ctx) => {
    const { isLoggedIn, userId, userData } = await sessionService.isLoggedIn(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    ctx.body = { isLoggedIn, userId, userData }
})

router.get('/assets/:assetType/:resource', async (ctx) => {
    const { assetType, resource } = ctx.params
    console.log("getting hosted resource", resource)
    await send(ctx, resource, { root: resolve(__dirname, '../../assets', assetType) })
})

// hackish, as it requires 2 directory levels :P
router.get('/hosted/:parentDirectory/:subDirectory/:resource', async (ctx) => {
    const { parentDirectory, subDirectory, resource } = ctx.params
    await send(ctx, resource, { root: resolve(__dirname, '../../hosted', parentDirectory, subDirectory) })
})

// router.get('/.well-known/acme-challenge/:resource', async (ctx) => {
//     const { resource } = ctx.params
//     await send(ctx, resource, { root: resolve(__dirname, '../../hosted') })
// })

module.exports = router
