const mysql = require('mysql')
const config = require('../../../config')
const schema = require('../schema')

const connection = mysql.createConnection({
    host    : config.dbHost,
    user    : config.dbUser,
    password: config.dbPassword,
    database: config.dbName,
})

connection.connect()

let initialized = false
const toResolve = []
const waitForInitialization = () => new Promise((resolve, reject) => {
    if (initialized) return resolve()
    toResolve.push(resolve)
})

const query = (query, values = [], otherParams = {}) => new Promise(async (resolve, reject) => {
    if (!otherParams.skipInitCheck) {
        await waitForInitialization()
    }
    connection.query(query, values, (error, result/*, fields*/) => {
        if (error) return reject(error)
        if (Array.isArray(result)) {
            resolve(result)
        }
        else if (result.insertId || typeof result.insertId === 'number') {
            resolve({ insertId: result.insertId })
        }
        else {
            console.error('Unrecognized result structure from query', { query, result })
            reject(new Error('Unrecognized query result structure'))
        }
    })
})

const dbService = { query }

;(async () => {
    await schema.initialize(dbService)
    initialized = true
    toResolve.forEach(resolve => resolve())
})();

module.exports = dbService
