import axios from 'axios'

async function doRequest({ method, url, data }) {
    const params = { method, url }
    if (data) params.data = data
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
    async post(url, data) {
        const responseData = await doRequest({
            method: 'post',
            url,
            data,
        })
        return responseData
    },
    async get(url) {
        const responseData = await doRequest({
            method: 'get',
            url,
        })
        return responseData
    },
}
