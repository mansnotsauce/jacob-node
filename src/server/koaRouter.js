const { resolve } = require('path')
const router = require('koa-router')()
const send = require('koa-send')
const constants = require('../shared/constants')
const permissionsUtils = require('../shared/permissionsUtils')
const sessionService = require('./services/sessionService')
const userService = require('./services/userService')
const teamService = require('./services/teamService')

// assets directory structure is a rigid 1 level deep :P
router.get('/assets/:assetType/:resource', async (ctx) => {
    const { assetType, resource } = ctx.params
    await send(ctx, resource, { root: resolve(__dirname, '../../assets', assetType) })
})

// hosted directory structure is a rigid 2 levels deep :P
router.get('/hosted/:parentDirectory/:subDirectory/:resource', async (ctx) => {
    const { parentDirectory, subDirectory, resource } = ctx.params
    await send(ctx, resource, { root: resolve(__dirname, '../../hosted', parentDirectory, subDirectory) })
})

router.post('/api/login', async (ctx) => {
    const { email, password } = ctx.request.body
    const { sessionKey, user } = await sessionService.login({ email, password })
    ctx.cookies.set(constants.SESSION_KEY_COOKIE_NAME, sessionKey, { overwrite: true })
    ctx.body = {
        isLoggedIn: true,
        user,
    }
})

router.post('/api/logout', async (ctx) => {
    await sessionService.logout(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    ctx.body = { ok: true }
})

router.get('/api/userStatus', async (ctx) => {
    const { isLoggedIn, user } = await sessionService.getUserStatus(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    ctx.body = { isLoggedIn, user }
})

router.get('/api/users', async (ctx) => {
    const { user } = await sessionService.getUserStatus(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!permissionsUtils.isAdminRole(user.role)) {
        throw new Error('Unauthorized get users request')
    }
    const users = await userService.getUsers()
    ctx.body = { users }
})

router.post('/api/createUser', async (ctx) => {
    const { user } = await sessionService.getUserStatus(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!permissionsUtils.isAdminRole(user.role)) {
        throw new Error('Unauthorized create user request')
    }
    const {
        email,
        role,
        firstName,
        lastName,
        phoneNumber,
        teamId,
    } = ctx.request.body
    await userService.createUser({
        email,
        role,
        firstName,
        lastName,
        phoneNumber,
        teamId,
    })
    const users = await userService.getUsers()
    ctx.body = { users }
})

router.get('/api/teams', async (ctx) => {
    const { user } = await sessionService.getUserStatus(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!permissionsUtils.isAdminRole(user.role)) {
        throw new Error('Unauthorized get users request')
    }
    const teams = await teamService.getTeams()
    ctx.body = { teams }
})

// router.get('/.well-known/acme-challenge/:resource', async (ctx) => {
//     const { resource } = ctx.params
//     await send(ctx, resource, { root: resolve(__dirname, '../../hosted') })
// })

module.exports = router
