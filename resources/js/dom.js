window.onscroll = function(e) {

    for (const obj of[{ "selector": '#containerInfoPage h1', "menu": "#home" }, { "selector": '#NY h3', "menu": "#NY" }, { "selector": '#RP h3', "menu": "#RP" }, { "selector": '#MA h3', "menu": "#MA" }, { "selector": '#MAP h3', "menu": "#MAP" }]) {
        let value = isInViewport(document.querySelector(obj.selector));
        if (value) {
            console.log(obj);
            toggleNav(obj.menu)
        }
    }

}

function toggleNav(section) {
    console.log(section);
    if (document.querySelector("a.activeMenu")) {
        document.querySelector("a.activeMenu").classList.remove("activeMenu");
    }

    if (document.querySelector(`a[href="${section}"]`)) {
        document
            .querySelector(`a[href="${section}"]`)
            .classList.add("activeMenu");
    }
}


function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}