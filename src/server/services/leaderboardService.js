//const requester = require('../requester');

function getStats() {
    const Https = new XMLHttpRequest()
    const credentialsUrl = 'https://login.salesforce.com/services/oauth2/token';
    const data = { 'grant_type': 'password', 
                   'client_id': '3MVG9mclR62wycM2QCvilyDrGjq8DvpGohXz.nJsA8n7MAA2ntKXGwqv2jOXapE3dHIbaxIe2vix7M5emxMj1',
                    'client_secret': 'E4B116D1D3BC3A56259361F17EB4395748D96F5B95CCDE496198C85C83BD6B94',
                    'username': 'horizonpwr.salesforce@gmail.com',
                    'password': 'horizonpwr.salesforce@gmail.com' };
    //const {access_token, instance_url} = requester.post(credentialsUrl, data);
    Https.open('POST', credentialsUrl);
    Https.setRequestHeader('Access-Control-Allow-Origin', '*');
    Https.setRequestHeader('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
    Https.setRequestHeader('Access-Control-Allow-Headers', 'Content-Type, Content-Range, Content-Disposition, Content-Description');
    Https.send(data);
    Https.onload = function() {
        let {access_token, instance_url } = Https.response;
        console.log(access_token);
        console.log(instance_url);
    }
}

getStats()
