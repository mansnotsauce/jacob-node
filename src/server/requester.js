const axios = require('axios')

async function doRequest({ method, url, data, headers }) {
    const params = { method, url }
    if (data) params.data = data
    if (headers) params.headers = headers
    try {
        const result = await axios(params)
        if (result.data.error) {
            console.log({ error: result.data.error })
            alert(result.data.error.message)
        }
        else {
            return result.data
        }
    }
    catch (error) {
        console.log(error)
        alert(error.message)
    }
}

async function post(url, data, { headers } = { headers: null }) {
    const responseData = await doRequest({
        method: 'post',
        url,
        data,
        headers,
    })
    return responseData
}

async function get(url, { headers } = { headers: null }) {
    const responseData = await doRequest({
        method: 'get',
        url,
        headers,
    })
    return responseData
}

module.exports = {
    post,
    get
}
