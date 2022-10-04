const currentTheme = localStorage.getItem("theme");

if (currentTheme == null) {
    if (dark == "yes") {
        localStorage.setItem("theme", "dark");
    } else {
        localStorage.setItem("theme", "dark");
    }
}
if (currentTheme !== null) {
    if (currentTheme == "dark") {
        document.getElementById("html").className = "dark";
    }
    if (currentTheme == "light") {
        document.getElementById("html").className = "";
    }
}

document.getElementById("html").classList.add("block-display");

function themeFunction() {
    if (currentTheme == "dark") {
        localStorage.setItem("theme", "dark");
        location.reload();
        return false;
    }
    if (currentTheme == "light") {
        localStorage.setItem("theme", "dark");
        location.reload();
        return false;
    }
}