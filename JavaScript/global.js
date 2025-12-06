function loadPage() {
  document.querySelector("header").classList.remove("scrolled");
  document.getElementById("btn-back-to-top").classList.remove("visible");
}

function updateTitleIcon() {
  const icnTitle = document.getElementsByClassName("title-icon")
  if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
    icnTitle.href = "../Resources/Vectors/LogoRidotto_Light.svg";
  } else {
    icnTitle.href = "../Resources/Vectors/LogoRidotto.svg";
  }
}

function pageScroll() {
  const header = document.querySelector("header");
  const backToTop = document.getElementById("btn-back-to-top");
  const footer = document.querySelector("footer");

  if (header && backToTop) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        header.classList.add("scrolled");
        backToTop.classList.add("visible");
      } else {
        header.classList.remove("scrolled");
        backToTop.classList.remove("visible");
      }
    });
  }
}

function backToTop() {
  window.scrollTo({top: 0, behavior: "smooth"});
}

function btnHamburgerClick() {
  const hamburger = document.getElementById("hamburger");
  const nav = document.getElementById("menu");

  hamburger.addEventListener("click", function() {
    nav.classList.toggle("active");
    hamburger.classList.toggle("active");
  })
}

loadPage();   // Funzione per modificare pagina al login. Se JavaScript non attivo, vengono mantenuti gli elementi del file HTML

updateTitleIcon();    // Avvio della pagina web
window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", updateTitleIcon);    // Aggiornamento automatico

pageScroll();   // Modifica dello stile dell'header allo scorrimento della pagina e mostra il pulsante per ritornare in cima alla pagina

btnHamburgerClick();    // Click del menù hamburger in formato mobile

// Listener per la pulizia del tag details con summary:hover
document.addEventListener("DOMContentLoaded", () => {
    const details = document.querySelectorAll("details");

    details.forEach(detail => {
        const summary = detail.querySelector("summary");

        summary.addEventListener("mouseenter", () => {
            detail.classList.add("summary-hovered");
        });

        summary.addEventListener("mouseleave", () => {
            detail.classList.remove("summary-hovered");
        });
    });
});