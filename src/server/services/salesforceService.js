const requester = require('../requester')
const https = require('https')

async function getStats() {
    const credentialsUrl = 'https://login.salesforce.com/services/oauth2/token';
    const data = { grant_type: 'password', 
                   client_id: '3MVG9mclR62wycM2QCvilyDrGjq8DvpGohXz.nJsA8n7MAA2ntKXGwqv2jOXapE3dHIbaxIe2vix7M5emxMj1',
                    client_secret: 'E4B116D1D3BC3A56259361F17EB4395748D96F5B95CCDE496198C85C83BD6B94',
                    username: 'horizonpwr.salesforce@gmail.com',
                    password: '$Horizon$2019%' };
    const headers = { 'Content-Type': 'multipart/form-data', 'Accept': "*/*", 'Connection': 'keep-alive' }
    const { access_token, instance_url } = await requester.post(credentialsUrl, data, { headers });
    // const Https = new XMLHttpRequest()
    // Https.open('POST', credentialsUrl);
    // Https.setRequestHeader('Access-Control-Allow-Origin', '*');
    // Https.setRequestHeader('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
    // Https.setRequestHeader('Access-Control-Allow-Headers', 'Content-Type, Content-Range, Content-Disposition, Content-Description');
    // Https.send(data);
    // Https.onload = function() {
    //     let {access_token, instance_url } = Https.response;
    //     console.log(access_token);
    //     console.log(instance_url);
    // }
    return { stats: 'eieieieieie' }
}

async function otherStats() {
    const data = { grant_type: 'password', 
    client_id: '3MVG9mclR62wycM2QCvilyDrGjq8DvpGohXz.nJsA8n7MAA2ntKXGwqv2jOXapE3dHIbaxIe2vix7M5emxMj1',
    client_secret: 'E4B116D1D3BC3A56259361F17EB4395748D96F5B95CCDE496198C85C83BD6B94',
    username: 'horizonpwr.salesforce@gmail.com',
    password: '$Horizon$2019%' }

    const options = {
        hostname: 'login.salesforce.com',
        path: '/services/oauth2/token',
        method: 'POST',
        headers: { 'Content-Type': 'multipart/form-data', 'Accept': "*/*", 'Connection': 'keep-alive' }
    }
    const req = https.request( options, (res) => {
        res.on('data', (d) => {
            console.log(d)
        })
    })
    req.write(data)
    req.end();
    console.log('asdf')
}

async function asdf() {
    return { stats: 'thank god'}
}

module.exports = {
    asdf,
    getStats,
    otherStats
}
