// Runs in the page head, before anything is shown. Lets the CSS hide content until
// main.js reveals it on scroll. If main.js fails to load, the class is removed
// again so the page is never left blank.
// (Kept in its own file because the site's security policy blocks inline scripts.)
document.documentElement.classList.add('js');
window.addEventListener('load', function () {
    if (!window.bpsScriptsLoaded) {
        document.documentElement.classList.remove('js');
    }
});
