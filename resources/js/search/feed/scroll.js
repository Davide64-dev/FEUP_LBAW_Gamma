import { initReactionJs } from "../../post/reactions";
import { addPaginationListener } from "../infinite_scroll";
import { createPostCard } from "../search";

const posts = document.getElementById("posts");

const currentUrl = window.location.href;
const lastSlashIndex = currentUrl.lastIndexOf('/');

if (currentUrl.slice(lastSlashIndex + 1) === 'feed') {
    addPaginationListener("/api/feed/popular", posts, "?", (entityCard) => {
        initReactionJs(entityCard);
        const card = document.createElement("div");
        card.innerHTML = entityCard;
        togglePostCopyLink(card.querySelectorAll(".post-copy-link-btn"));
    }).then(() => { }).catch((e) => console.error(e));
} else if (currentUrl.slice(lastSlashIndex + 1) === 'personal') {
    addPaginationListener("/api/feed/personal", posts, "?", (entityCard) => {
        initReactionJs(entityCard);
        const card = document.createElement("div");
        card.innerHTML = entityCard;
        togglePostCopyLink(card.querySelectorAll(".post-copy-link-btn"));
    }).then(() => { }).catch((e) => console.error(e));
}
