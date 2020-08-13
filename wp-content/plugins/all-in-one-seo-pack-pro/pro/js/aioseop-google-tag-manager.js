
/**
 * Contains all Google Tag Manager code.
 * 
 * @since 3.3.0
 */

aioseopInsertGtm( aioseopGoogleTagManager.gtmContainerId );

/**
 * Prints the Google Tag Manager code in the BODY section.
 * 
 * @since 3.3.0
 * 
 * @param string gtmContainerId The Google Tag Manager Container ID.
 */
function aioseopInsertGtm( gtmContainerId ) {

    let iframe = document.createElement('iframe');
    iframe.src = `https://www.googletagmanager.com/ns.html?id=${gtmContainerId}`;

    iframe.style.display = "none";
    iframe.style.visibility  = "hidden";
    iframe.height = 0;
    iframe.width = 0;

    let noscript = document.createElement('noscript');
    noscript.appendChild(iframe);

    document.body.insertBefore(noscript, document.body.firstChild);
}
