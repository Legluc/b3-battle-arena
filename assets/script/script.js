function getJwtFromCookie() {
    const cookieName = 'BEARER=';
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.startsWith(cookieName)) {
            return cookie.substring(cookieName.length);
        }
    }
    return null;
}

/**
 * Ajoute le JWT aux requêtes fetch
 */
function fetchWithJwt(url, options = {}) {
    const token = getJwtFromCookie();
    if (token) {
        options.headers = {
            ...options.headers,
            'Authorization': `Bearer ${token}`,
        };
    }
    return fetch(url, options);
}
