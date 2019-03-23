function waitNav() {
    var nav = document.getElementById("wait-nav");
    if (nav.style.display === "flex") {
        nav.style.display = "none";
    } else {
        nav.style.display = "flex";
    }
}