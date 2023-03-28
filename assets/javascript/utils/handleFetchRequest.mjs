export function handleFetchRequest(url, method = 'GET', body = "", headers = {}) {
    if (method === 'GET' || method === 'HEAD') {
        return fetch(url, {
            method,
            headers
        }).catch(e => {
            alert(`Something went wrong ${e.message}`)
        })
    }
    return fetch(url, {
        method,
        body,
        headers
    }).catch(e => {
        alert(`Something went wrong ${e.message}`)
    })
}