function toggleNav(section) {
    if (document.querySelector("a.activeMenu")) {
        document.querySelector("a.activeMenu").classList.remove("activeMenu");
    }

    if (document.querySelector(`a[href="${section}"]`)) {
        document
            .querySelector(`a[href="${section}"]`)
            .classList.add("activeMenu");
    }
}

// Affichage d'une section
function displaySection() {
    const section = window.location.hash || "#home";
    const sectionSplit = section.split("-");

    toggleNav(sectionSplit[0]);
}

window.addEventListener("hashchange", displaySection);

displaySection();