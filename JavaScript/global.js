function loadPage() {
  document.querySelector("header").classList.remove("scrolled");
  document.getElementById("btn-back-to-top").classList.remove("visible");
}

function pageScroll() {
  const header = document.querySelector("header");
  const backToTop = document.getElementById("btn-back-to-top");

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
  const nav = document.getElementById("navbar");

  hamburger.addEventListener("click", function() {
    nav.classList.toggle("active");
    hamburger.classList.toggle("active");
  })
}

function changeCopyright() {
  const year = document.getElementById("cop-year").innerHTML = new Date().getFullYear();
}

loadPage();   // Funzione per modificare pagina al login. Se JavaScript non attivo, vengono mantenuti gli elementi del file HTML

pageScroll();   // Modifica dello stile dell'header allo scorrimento della pagina e mostra il pulsante per ritornare in cima alla pagina

btnHamburgerClick();    // Click del menù hamburger in formato mobile

changeCopyright();    // Cambio dell'anno di copyright in base all'anno corrente

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

// Listener per rimozione/ripristino dello sfondo delle pagine
document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("btn-remove-bck");
  const root = document.documentElement;

  btn.addEventListener("click", () => {
    root.classList.toggle("background");

    if (root.classList.contains("background")) {
        btn.title = "Rimuovi Sfondo";
    } else {
        btn.title = "Ripristina Sfondo";
    }
  })
});