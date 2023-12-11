const postCopyLinkBtns = document.querySelectorAll(".post-copy-link-btn");

export function togglePostCopyLink(postCopyLinkBtns) {
    if (!postCopyLinkBtns) {
        return;
    }

    for (const postCopyLinkBtn of postCopyLinkBtns) {
        postCopyLinkBtn.addEventListener("click", function(e) {
            const postId = postCopyLinkBtn.parentElement.getAttribute("data-entity-id");

            const postUrl = `/post/${postId}`;

            const url = window.location.href;
            const urlObject = new URL(url);

            const newUrl = `${urlObject.protocol}//${urlObject.host}${postUrl}`;

            navigator.clipboard.writeText(newUrl).then(() => {
            }, () => {
                console.error('Failed to copy');
            }).catch((e) => console.error(e));
        });
    }
}

togglePostCopyLink(postCopyLinkBtns);
