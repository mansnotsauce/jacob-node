const { resolve } = require('path')
const router = require('koa-router')()
const send = require('koa-send')
const asyncBusboy = require('async-busboy')
const constants = require('../shared/constants')
const sessionService = require('./services/sessionService')
const userService = require('./services/userService')
const teamService = require('./services/teamService')
const roleService = require('./services/roleService')

// assets directory structure is a rigid 1 level deep :P
router.get('/assets/:assetType/:resource', async (ctx) => {
    const { assetType, resource } = ctx.params
    await send(ctx, resource, { root: resolve(__dirname, '../../assets', assetType) })
})

// hosted directory structure is a rigid 2 levels deep :P
// TODO: get rid of this (and above) restriction by doing some more legit programmatic url parsing
router.get('/hosted/:parentDirectory/:subDirectory/:resource', async (ctx) => {
    const { parentDirectory, subDirectory, resource } = ctx.params
    await send(ctx, resource, { root: resolve(__dirname, '../../hosted', parentDirectory, subDirectory) })
})

async function getEagerlyLoadedEntities(user) {
    const [
        users,
        teams,
        roles,
    ] = await Promise.all([
        user.isAdmin ? userService.getUsers() : [],
        user.isAdmin || user.isOnboarder ? teamService.getTeams() : [],
        roleService.getRoles()
    ])
    return {
        user,
        users,
        teams,
        roles,
    }
}

router.post('/api/login', async (ctx) => {
    const { email, password } = ctx.request.body
    const { sessionKey, user } = await sessionService.login({ email, password })
    const entities = await getEagerlyLoadedEntities(user)
    ctx.cookies.set(constants.SESSION_KEY_COOKIE_NAME, sessionKey, { overwrite: true })
    ctx.body = {
        isActive: true,
        entities,
    }
})

router.get('/api/sessionStatus', async (ctx) => {
    const { isActive, user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    const entities = isActive ? await getEagerlyLoadedEntities(user) : {}
    ctx.body = {
        isActive,
        entities,
    }
})

router.post('/api/logout', async (ctx) => {
    await sessionService.logout(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    ctx.body = { ok: true }
})    

// router.get('/api/users', async (ctx) => {
//     const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
//     if (!user.isAdmin) {
//         throw new Error('Unauthorized get users request')
//     }
//     const users = await userService.getUsers()
//     ctx.body = { users }
// })

router.post('/api/createUser', async (ctx) => {
    const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!user.isAdmin) {
        throw new Error('Unauthorized create user request')
    }
    const {
        email,
        roleId,
        firstName,
        lastName,
        phoneNumber,
        teamId,
    } = ctx.request.body
    await userService.createUser({
        email,
        roleId,
        firstName,
        lastName,
        phoneNumber,
        teamId,
    })
    const users = await userService.getUsers()
    ctx.body = { users }
})

router.post('/api/editUser', async (ctx) => {
    const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!user.isAdmin) {
        throw new Error('Unauthorized create user request')
    }
    const {
        userId,
        roleId,
        teamId,
        firstName,
        lastName,
        phoneNumber,
    } = ctx.request.body
    await userService.editUser({
        userId,
        roleId,
        teamId,
        firstName,
        lastName,
        phoneNumber,
    })
    const users = await userService.getUsers()
    ctx.body = { users }
})

router.post('/api/createTeam', async (ctx) => {
    const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!user.isAdmin) {
        throw new Error('Unauthorized create user request')
    }
    const { newTeamName } = ctx.request.body
    await teamService.createTeam({ newTeamName })
    const teams = await teamService.getTeams()
    ctx.body = { teams }
})

router.post('/api/uploadProfileImage/:userId', async (ctx) => {
    const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!user.isAdmin) {
        throw new Error('Unauthorized create user request')
    }
    const { userId } = ctx.params // NOT session user userId
    const { files, fields } = await asyncBusboy(ctx.req)
    const fileStream = files[0]
    const { filename } = fileStream
    console.log({ filename })
    const fileBuffer = await new Promise((resolve, reject) => {
        let buffers = []
        fileStream.on('data', data => buffers.push(data))
        fileStream.on('end', () => {
            resolve(Buffer.concat(buffers))
        })
    })
    await userService.setProfileImage({ userId, filename, fileBuffer })
    const users = await userService.getUsers()
    ctx.body = { users }
})

router.post('/api/resetPassword', async (ctx) => {
    const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
    if (!user.isAdmin) {
        throw new Error('Unauthorized create user request')
    }
    const { userId } = ctx.request.body
    await sessionService.resetPassword({ userId })
    ctx.body = { ok: true }
})

// router.get('/api/teams', async (ctx) => {
//     const { user } = await sessionService.getSession(ctx.cookies.get(constants.SESSION_KEY_COOKIE_NAME))
//     if (!user.isAdmin && !user.isOnboarder) {
//         throw new Error('Unauthorized get users request')
//     }
//     const teams = await teamService.getTeams()
//     ctx.body = { teams }
// })

// router.get('/api/roles', async (ctx) => {
//     const roles = await roleService.getRoles()
//     ctx.body = { roles }
// })

// router.get('/.well-known/acme-challenge/:resource', async (ctx) => {
//     const { resource } = ctx.params
//     await send(ctx, resource, { root: resolve(__dirname, '../../hosted') })
// })

module.exports = router
