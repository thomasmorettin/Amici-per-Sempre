const BACKGROUND_KEY = "backgroundRemoved";

(localStorage.getItem(BACKGROUND_KEY) == "true") ?
    document.documentElement.classList.remove("set-background") :
    document.documentElement.classList.add("set-background");

const THEME_KEY = "themeDark";

(localStorage.getItem(THEME_KEY) == "true") ?
    document.documentElement.dataset.theme = "dark" :
    document.documentElement.dataset.theme = "light";