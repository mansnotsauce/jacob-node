const { resolve } = require('path')
const router = require('koa-router')()
const send = require('koa-send')
const constants = require('../shared/constants')
const permissionsUtils = require('../shared/permissionsUtils')
const sessionService = require('./services/sessionService')
const userService = require('./services/userService')

router.post('/login', async (ctx) => {
    const { email, password } = ctx.request.body
    const { sessionKey, userId, userData } = await sessionService.login({ email, password })
    ctx.cookies.set(constants.SESSION_KEY_COOKIE_NAME, sessionKey, { overwrite: true })
    ctx.body = {
        isLoggedIn: true,
        userId,
        userData,
    }
})

router.post('/logout', async (ctx) => {
    await sessionService.logout(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    ctx.body = { ok: true }
})

router.get('/userStatus', async (ctx) => {
    const { isLoggedIn, userId, userData } = await sessionService.getUserStatus(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    ctx.body = { isLoggedIn, userId, userData }
})

router.get('/assets/:assetType/:resource', async (ctx) => {
    const { assetType, resource } = ctx.params
    await send(ctx, resource, { root: resolve(__dirname, '../../assets', assetType) })
})

// hackish, as it requires 2 directory levels :P
router.get('/hosted/:parentDirectory/:subDirectory/:resource', async (ctx) => {
    const { parentDirectory, subDirectory, resource } = ctx.params
    await send(ctx, resource, { root: resolve(__dirname, '../../hosted', parentDirectory, subDirectory) })
})

router.get('/users', async (ctx) => {
    const { userData } = await sessionService.getUserStatus(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!permissionsUtils.isAdminRole(userData.role)) {
        throw new Error('Unauthorized get users request')
    }
    const users = await userService.getUsers()
    ctx.body = { users }
})

// router.get('/.well-known/acme-challenge/:resource', async (ctx) => {
//     const { resource } = ctx.params
//     await send(ctx, resource, { root: resolve(__dirname, '../../hosted') })
// })

module.exports = router
