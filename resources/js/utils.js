export function getCsrfToken() {
    const metaTag = document.querySelector("meta[name='csrf-token']").getAttribute("content");
    return metaTag;
}

export function getUsername() {
    const username = document.querySelector("meta[name='username']").getAttribute("content");

    return username;
}
