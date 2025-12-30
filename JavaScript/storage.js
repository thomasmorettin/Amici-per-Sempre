const STORAGE_KEY = "backgroundRemoved";
if (localStorage.getItem(STORAGE_KEY) === "true") { document.documentElement.classList.remove("set-background"); }