/**
 * Show the preloader on the button
 * @param btn
 */
export function showLoaderBtn(btn) {
    let child = btn.children;
    btn.style.height = btn.offsetHeight + "px";
    btn.style.width = btn.offsetWidth + "px";
    btn.style.overflow = "hidden";
    child[0].classList.add('d-none');
    child[1].classList.remove('d-none');
}

/**
 * Hide the preloader on the button
 * @param btn
 */
export function hideLoaderBtn(btn) {
    let child = btn.children;
    child[0].classList.remove('d-none');
    child[1].classList.add('d-none');
    btn.removeAttribute('style');
}
