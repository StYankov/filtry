/**
 * Toggle on or off the loader for the products list
 * 
 * @param {bool} state State of the loader
 */
export function toggleLoader(state = true) {
    if(state) {
        document.body.classList.add('filtry--loader-active');
    } else {
        document.body.classList.remove('filtry--loader-active');
    }
}