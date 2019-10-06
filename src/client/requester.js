import axios from 'axios'

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
        alert(error.message)
    }
}

export default {
    async post(url, data, { headers } = { headers: null }) {
        const responseData = await doRequest({
            method: 'post',
            url,
            data,
            headers,
        })
        return responseData
    },
    async get(url, { headers } = { headers: null }) {
        const responseData = await doRequest({
            method: 'get',
            url,
            headers,
        })
        return responseData
    },
}
