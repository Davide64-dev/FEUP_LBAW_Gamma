import { deleteUserAction } from "../admin/user/delete";
import { unblockUserAction } from "../admin/user/unblock";

const csrfMeta = document.querySelector("meta[name='csrf-token']");

export async function searchUsers(query, searchPreviewContent, admin) {
    fetch(`/api/search/users/${query}`, {
        method: "GET",
    }).then(async (res) => {

        const users = await res.json();

        if (users.length == 0) {
            searchPreviewContent.innerHTML = getNoneFoundText("users");
        } else {
            searchPreviewContent.innerHTML = "";
            for (const user of users) {
                console.log("User received is: ", user);
                searchPreviewContent.innerHTML += `
<article data-user-image="${user.image}" data-username="${user.username}" class="my-4 p-2 border-b flex justify-between align-middle space-x-2">
    <div class="flex flex-row space-x-2 align-middle">
        <img class="rounded-full w-10 h-10" src="${user.image}" alt="Profile Picture">
        <h1>
            <a href="/user/${user.username}" class="underline">
                ${user.username}
            </a>
        </h1>
    </div>
    ${admin ?
                        `<div class="order-3 space-x-8">
        <a class="block-reason-trigger" cursor:pointer" href="/users/${user.username}/block" ${user.is_app_banned ? 'hidden' : ''}>Block</a>
        <button class="unblock-confirmation-trigger" ${user.is_app_banned ? '' : 'hidden'}> 
            Unblock 
        </button>
        <button class="delete-confirmation-trigger">
            Delete
        </button>
    </div>` : ''}
</article>

`;
                const deleteTriggerBtn = searchPreviewContent.querySelector(".delete-confirmation-trigger");
                deleteTriggerBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    deleteUserAction(deleteTriggerBtn);
                });
                const unblockTriggerBtn = searchPreviewContent.querySelector(".unblock-confirmation-trigger");
                unblockTriggerBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    unblockUserAction(unblockTriggerBtn);
                });
            }
        }
    }).catch((err) => {

    });

}

export async function searchPosts(query, searchPreviewContent) {
    fetch(`/api/search/posts/${query} `, {
        method: "GET",
    }).then(async (res) => {
        const posts = await res.json();

        if (posts.length == 0) {
            searchPreviewContent.innerHTML = getNoneFoundText("posts");
        } else {
            searchPreviewContent.innerHTML = ``;
            for (const post of posts) {
                searchPreviewContent.innerHTML += `
                    < article class="post-card border border-black rounded-md my-4 p-2 cursor-pointer" >
                        <div class="flex align-middle justify-between space-x-4">
                            <div class="flex space-x-4">
                                <img src="${post.author.image}" class="rounded-full w-10 h-10">
                                <a class="hover:underline" href="/user/${post.author.username}">
                                    ${post.author.username}
                                </a>
                            </div>
                            <span>
                                <time>${post.date.split(" ")[0]}</time>
                            </span>
                        </div>
                        <header class="my-4">
                            <h1 class="text-2xl">
                                <a href="/post/${post.title}"class="hover:underline">${post.title}</a>
                            </h1>
                        </header>
                        <p class="my-4">
                            ${post.content}
                        </p>
                    </article > `;
            }
        }
    }).catch((err) => {

    });
}

export async function searchGroups(query, searchPreviewContent) {
    fetch(`/api/search/groups/${query} `, {
        method: "GET",
    }).then(async (res) => {

        const groups = await res.json();

        if (groups.length == 0) {
            searchPreviewContent.innerHTML = getNoneFoundText("groups");
        } else {
            searchPreviewContent.innerHTML = "";
            for (const group of groups) {
                searchPreviewContent.innerHTML += `
                    < article class="my-4 p-2 border-b" >
                        <h1>
                            <a href="/group/${group.name}" class="underline">${group.name}</a>
                        </h1>
                </article >
                    `;
            }
        }
    }).catch((err) => {

    });
}

function getNoneFoundText(entity) {
    return `<p class="text-center"> No ${entity} found.</p>`;
}

export async function getSearchResults(type, query, searchPreviewContent) {
    type = type.split("-").slice(2).join("-");

    const actions = {
        "users-preview-results": searchUsers,
        "posts-preview-results": searchPosts,
        "groups-preview-results": searchGroups,
    };

    actions[type](query, searchPreviewContent, false);
}