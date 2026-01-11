const STORAGE_KEY = "backgroundRemoved";

(localStorage.getItem(STORAGE_KEY) == "true") ?
    document.documentElement.classList.remove("set-background") :
    document.documentElement.classList.add("set-background");