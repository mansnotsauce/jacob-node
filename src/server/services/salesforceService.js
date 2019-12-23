const requester = require('../requester')
const dbService = require('./dbService')
const userService = require('./userService')
const teamService = require('./teamService')
const roleService = require('./roleService')
let querystring = require('querystring')

async function updateStats() {
    console.log('started')
    const credentialsUrl = 'https://login.salesforce.com/services/oauth2/token'
    let data = { grant_type: 'password', 
                   client_id: '3MVG9mclR62wycM2QCvilyDrGjq8DvpGohXz.nJsA8n7MAA2ntKXGwqv2jOXapE3dHIbaxIe2vix7M5emxMj1',
                    client_secret: 'E4B116D1D3BC3A56259361F17EB4395748D96F5B95CCDE496198C85C83BD6B94',
                    username: 'horizonpwr.salesforce@gmail.com',
                    password: '$Horizon$2019%' }
    data = querystring.stringify(data)
    const authHeaders = { 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': "*/*", 'Connection': 'keep-alive' }
    const { access_token, instance_url } = await requester.post(credentialsUrl, data, { authHeaders })

    const queryHeaders = { headers: { Authorization: 'Bearer ' + access_token, 'Content-Type': 'application/json' } }
    const queryUrl = instance_url + '/services/data/v47.0/query/?q='
    
    const opportunityUrl = queryUrl + 'SELECT Proposal_Requested_Date_Time__c, Field_Marketer__c, Sat__c, Appointment_Date__c, Id FROM Opportunity WHERE Proposal_Requested__c = true AND Field_Marketer__c != null'
    const opportunities = await requester.get(opportunityUrl, queryHeaders)  

    const residentialProjectUrl = queryUrl + 'SELECT Site_Audit_Scheduled_Date_Time__c, Account__c, Field_Marketer__c, Install_Complete__c, Install_Complete_Date_Time__C, Id FROM Residential_Projects__c'
    const residentialProjects = await requester.get(residentialProjectUrl, queryHeaders)

    const accountUrl = queryUrl + 'SELECT Id, Name FROM Account'
    const accounts = await requester.get(accountUrl, queryHeaders)

    for (let i = 0; i < opportunities.records.length; i++) {
        await dbService.query('INSERT IGNORE INTO _lead (Id, Proposal_Requested_Date_Time__c, Field_Marketer__c) VALUES (?, ?, ?)', [opportunities.records[i].Id, opportunities.records[i].Proposal_Requested_Date_Time__c, opportunities.records[i].Field_Marketer__c])
        if (opportunities.records[i].Sat__c === true) {
            await dbService.query('INSERT IGNORE INTO qs (Id, Appointment_Date__c, Field_Marketer__c) VALUES (?, ?, ?)', [opportunities.records[i].Id, opportunities.records[i].Appointment_Date__c, opportunities.records[i].Field_Marketer__c])
        }
    }
    for (let i = 0; i < residentialProjects.records[i].length; i++) {
        let Energy_Consultant__c = await dbService.query('SELECT Name FROM account WHERE Id = ?', [residentialProjects.records[i].Account__c])
        Energy_Consultant__c = Energy_Consultant__c[0].Name
        await dbService.query('INSERT IGNORE INTO _close (Id, Site_Audit_Scheduled_Date_Time__c, Energy_Consultant__c) VALUES (?, ?, ?)', [residentialProjects.records[i].Id, residentialProjects.records[i].Site_Audit_Scheduled_Date_Time__c, Energy_Consultant__c])
        console.log(residentialProjects.records[i].Field_Marketer__c)
        console.log(Energy_Consultant__c)
        if (residentialProjects.records[i].Field_Marketer__c !== Energy_Consultant__c) {
            await dbService.query('INSERT IGNORE INTO assisted_close (Id, Site_Audit_Scheduled_Date_Time__c, Field_Marketer__c) VALUES (?, ?, ?)', [residentialProjects.records[i].Id, residentialProjects.records[i].Site_Audit_Scheduled_Date_Time__c, residentialProjects.records[i].Field_Marketer__c])
            if (residentialProjects.records[i].Install_Complete__c === true) {
                await dbService.query('INSERT IGNORE INTO assisted_install (Id, Install_Complete_Date_Time__c, _Name) VALUES (?, ?, ?)', [residentialProjects.records[i].Id, residentialProjects.records[i].Install_Complete_Date_Time__c, residentialProjects.records[i].Field_Marketer__c])
                await dbService.query('INSERT IGNORE INTO assisted_install (Id, Install_Complete_Date_Time__c, _Name) VALUES (?, ?, ?)', [residentialProjects.records[i].Id, residentialProjects.records[i].Install_Complete_Date_Time__c, Energy_Consultant__c])
            }
        }
        else {
            if (residentialProjects.records[i].Install_Complete__c) {
                await dbService.query('INSERT IGNORE INTO self_gen_install (Id, Install_Complete_Date_Time__c, Energy_Consultant__c) VALUES (?, ?, ?)', [residentialProjects.records[i].Id, residentialProjects.records[i].Install_Complete_Date_Time__c, Energy_Consultant__c])
            }
        }
    }
    for (let i = 0; i < accounts.records.length; i++) {
        await dbService.query('INSERT IGNORE INTO account (Id, Name) VALUES(?, ?)', [accounts.records[i].Id, accounts.records[i].Name])
    }
    console.log('ended')
}
//run every 20 mins
setInterval(updateStats, 1200000)

async function getStats(dateRange) {
    const users = await userService.getUsers()
    const teams = await teamService.getTeams()
    const accounts = await dbService.query('SELECT * FROM account')
    const startDateTime = dateRange.start + ' 00:00:00'
    const endDateTime = dateRange.end + ' 00:00:00'
    let energyConsultants = []
    let fieldMarketers = []
    let teamScores = []
    console.log(accounts.length)
    for (let i = 0; i < accounts.length; i++) {
        //Can't do this until people actually make accounts on pwrstation
        // let role = ''
        // for (let i = 0; i < users.length; i++) {
        //     let name = users[i].firstName + users[i].lastName
        //     if (name === accounts[i].Name) {
        //         if (users[i].roleId === 7) {
        //             console.log('fm')
        //             role = "Field Marketer"
        //         }
        //         else {
        //             console.log('ec')
        //             role = "Energy Consultant"
        //         }
        //     }
        // }
        let role = 'Field Marketer'
        if (role === 'Field Marketer'){
            let leadCount = await dbService.query('SELECT COUNT(*) FROM _lead WHERE Field_Marketer__c = ? AND Proposal_Requested_Date_Time__c > ? AND Proposal_Requested_Date_Time__c < ?',[accounts[i].Name, startDateTime, endDateTime])
            leadCount = leadCount[0]['COUNT(*)']
            let qsCount = await dbService.query('SELECT COUNT(*) FROM qs WHERE Field_Marketer__c = ? AND Appointment_Date__c > ? AND Appointment_Date__c < ?',[accounts[i].Name, dateRange.start, dateRange.end])
            qsCount = qsCount[0]['COUNT(*)']
            let assistedClosesCount = await dbService.query('SELECT COUNT(*) FROM assisted_close WHERE Field_Marketer__c = ? AND Site_Audit_Scheduled_Date_Time__c > ? AND Site_Audit_Scheduled_Date_Time__c < ?',[accounts[i].Name, startDateTime, endDateTime])
            assistedClosesCount = assistedClosesCount[0]['COUNT(*)']
            let assistedInstallsCount = await dbService.query('SELECT COUNT(*) FROM assisted_install WHERE _Name = ? AND Install_Complete_Date_Time__c > ? AND Install_Complete_Date_Time__c < ?',[accounts[i].Name, startDateTime, endDateTime])
            assistedInstallsCount = assistedInstallsCount[0]['COUNT(*)']
            let score = leadCount + qsCount * 2 + assistedClosesCount * 3 + assistedInstallsCount * 4
            fieldMarketers.push({ Name: accounts[i].Name, Score: score, Leads: leadCount, Qs: qsCount, AssistedCloses: assistedClosesCount, AssistedInstalls: assistedInstallsCount })
        }
        else if (role === 'Energy Consultant') {
            let leadCount = await dbService.query('SELECT COUNT(*) FROM _lead WHERE Field_Marketer__c = ? AND Proposal_Requested_Date_Time__c > ? AND Proposal_Requested_Date_Time__c < ?',[accounts[i].Name, startDateTime, endDateTime])
            leadCount = leadCount[0]['COUNT(*)']
            let qsCount = await dbService.query('SELECT COUNT(*) FROM qs WHERE Field_Marketer__c = ? AND Appointment_Date__c > ? AND Appointment_Date__c < ?',[accounts[i].Name, dateRange.start, dateRange.end])
            qsCount = qsCount[0]['COUNT(*)']
            let closeCount = await dbService.query('SELECT COUNT(*) FROM _close WHERE Energy_Consultant__c = ? AND Site_Audit_Scheduled_Date_Time__c > ? AND Site_Audit_Scheduled_Date_Time__c < ?',[accounts[i].Name, startDateTime, endDateTime])
            closeCount = closeCount[0]['COUNT(*)']
            let assistedInstallsCount = await dbService.query('SELECT COUNT(*) FROM assisted_install WHERE _Name = ? AND Install_Complete_Date_Time__c > ? AND Install_Complete_Date_Time__c < ?',[accounts[i].Name, startDateTime, endDateTime])
            assistedInstallsCount = assistedInstallsCount[0]['COUNT(*)']
            let selfGenInstallsCount = await dbService.query('SELECT COUNT(*) FROM self_gen_install WHERE Energy_Consultant__c = ? AND Install_Complete_Date_Time__c > ? AND Install_Complete_Date_Time__c < ?',[accounts[i].Name, startDateTime, endDateTime])
            selfGenInstallsCount = selfGenInstallsCount[0]['COUNT(*)']
            let score = leadCount + qsCount * 0.25 + closeCount * 3 + assistedInstallsCount * 4 + selfGenInstallsCount * 6
            energyConsultants.push({ Name: accounts[i].Name, Score: score, Leads: leadCount, Qs: qsCount, Closes: closeCount, AssistedInstalls: assistedInstallsCount, SelfGenInstall: selfGenInstallsCount })
        }
    }
    console.log(fieldMarketers[0])
    return { stats: {EnergyConsultants: energyConsultants, FieldMarketers: fieldMarketers }}
}

async function getCommisions() {
    
}

module.exports = {
    getStats
}
