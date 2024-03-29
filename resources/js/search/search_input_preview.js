import { getSearchResults } from "./search";

const searchMenuName = 'search-input';

let currentSearchPreview = `${searchMenuName}-users-preview-results`;

const searchPreviewResults = document.getElementById(`${searchMenuName}-search-results`);
const searchPreviewContent = document.getElementById(`${searchMenuName}-search-preview-content`);

const mobileSearchPreviewResults = document.getElementById(`mobile-${searchMenuName}-search-results`);
const mobileSearchPreviewContent = document.getElementById(`mobile-${searchMenuName}-search-preview-content`);

const previewOptions = searchPreviewResults.querySelectorAll(".preview-results-option");
const mobilePreviewOptions = mobileSearchPreviewResults.querySelectorAll(".preview-results-option");

const search = document.getElementById("search-navbar");
const mobileSearch = document.getElementById("mobile-search-trigger");

const borderType = "border-t-4";
const borderColor = "border-black";

function showSearchPreview(searchPreviewResults, target, previewContent) {
    searchPreviewResults.style.display = 'block';

    getSearchResults(currentSearchPreview, target.value, previewContent, true);
}

for (const previewOption of previewOptions) {
    previewOption.addEventListener("click", (e) => {
        e.stopPropagation();

        previewOptions.forEach(previewOption => {
            previewOption.classList.remove(borderType, borderColor);
        });

        if (!previewOption.classList.contains(borderType) && !previewOption.classList.contains(borderColor)) {
            previewOption.classList.add(borderType, borderColor);

            getSearchResults(previewOption.id, search.value, searchPreviewContent, true);
        }

        currentSearchPreview = `${previewOption.id}`;
    });
}

for (const previewOption of mobilePreviewOptions) {
    previewOption.addEventListener("click", (e) => {
        e.stopPropagation();

        const corrected_id = `${(previewOption.id).split("-").slice(1).join("-")}`;

        mobilePreviewOptions.forEach(previewOption => {
            previewOption.classList.remove(borderType, borderColor);
        });

        if (!previewOption.classList.contains(borderType) && !previewOption.classList.contains(borderColor)) {
            previewOption.classList.add(borderType, borderColor);

            getSearchResults(corrected_id, mobileSearch.value, mobileSearchPreviewContent, true);
        }

        currentSearchPreview = corrected_id;
    });
}

function addNavbarSearchListener() {
    search.addEventListener("input", (e) => {
        const value = e.target.value;

        if (value.trim() === '') {
            searchPreviewResults.style.display = 'none';
        } else {
            showSearchPreview(searchPreviewResults, search, searchPreviewContent);
        }
    });

    search.addEventListener("focus", (e) => {
        if (e.target.value.trim() !== '' && searchPreviewResults.style.display === '') {
            searchPreviewResults.style.display = 'block';
            showSearchPreview(searchPreviewResults, e);
        }
    });

    const searchForm = document.getElementById("search-form");

    searchForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const query = searchForm["search"].value;
        window.location.replace(`/search/${query}?toggled=${currentSearchPreview.split("-")[2]}`);
    });

    const mobileSearchForm = document.getElementById("mobile-search-form");

    mobileSearchForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const query = mobileSearchForm["search"].value;
        window.location.replace(`/search/${query}?toggled=${currentSearchPreview.split("-")[2]}`);
    });

    const mobileSearch = document.getElementById("mobile-search-trigger");
    const mobileSearchPreviewResults = document.getElementById("mobile-search-input-search-results");
    const mobileSearchPreviewContent = document.getElementById("mobile-search-input-search-preview-content");

    window.addEventListener("resize", () => {
        if (window.innerWidth > 600) {
            mobileSearchPreviewResults.style.display = 'none';
        }
    });

    mobileSearch.addEventListener("input", (e) => {

        if (e.target.value.trim() === '') {
            mobileSearchPreviewResults.style.display = 'none';
        } else {
            showSearchPreview(mobileSearchPreviewResults, e.target, mobileSearchPreviewContent);
        }
    });
}

addNavbarSearchListener();

