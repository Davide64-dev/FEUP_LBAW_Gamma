import { togglePostCopyLink } from "../../post/copy_link";
import { initReactionJs } from "../../post/reactions";
import { getCurrentSearchQuery } from "../../utils";
import { addPaginationListener } from "../infinite_scroll";

const content = document.getElementById("main-search-preview-search-preview-content");
const currentQuery = getCurrentSearchQuery();

const currentUrl = window.location.href;
const toggled = (currentUrl.split("?")[1]);

addPaginationListener(`/api/search/${currentQuery}?${toggled}`, content, '&', (entityCard) => {
    initReactionJs(entityCard);
    togglePostCopyLink(entityCard.querySelectorAll(".post-copy-link-btn"));
})
    .then(() => { }).catch((e) => console.error(e));
