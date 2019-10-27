const { resolve } = require('path')
const fs = require('fs')
const socketRouter = require('../src/server/socketRouter')

/*
Generate serverGenerated.json, which is used in the front-end bundle
*/
const serverGeneratedJson = JSON.stringify({
    eventTypesSubscribedToByServer: socketRouter.getEventTypesSubscribedTo(),
}, null, 4)
fs.writeFileSync(resolve(__dirname, '../src/client/serverGenerated.json'), serverGeneratedJson)
