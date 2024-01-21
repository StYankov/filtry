/**
 * 
 * @param {string} queryString 
 * @param {object} data
 */
export function pushRoute(queryString, data = {}) {
    if(queryString.length === 0) {
        window.history.pushState(data, '', window.location.href.split('?')[0]);
    } else {
        window.history.pushState(data, '', `${window.location.href.split('?')[0]}?${queryString}`);
    }
}