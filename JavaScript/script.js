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

function headerScroll() {
  const header = document.querySelector("header");
  if (header) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        header.classList.add("scrolled");
      } else {
        header.classList.remove("scrolled");
      }
    });
  }
}

function showBtnBackToTop() {
  const backToTop = document.getElementById("btn-back-to-top");
  if (backToTop) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        backToTop.classList.add("visible");
      } else {
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

headerScroll();   // Modifica dello stile dell'header allo scorrimento della pagina
showBtnBackToTop();   // Mostra il pulsante per ritornare in cima alla pagina

btnHamburgerClick();    // Click del menù hamburger in formato mobile

document.addEventListener("DOMContentLoaded", () => {
  const details = document.querySelectorAll("details.dtl-animale");

  details.forEach(detail => {
    const summary = detail.querySelector("summary");
    const contenuto = detail.querySelector(".contenuto-nascosto");

    summary.addEventListener("click", (event) => {
      event.preventDefault();   // Prevenzione dell'azione di default

      if (detail.open) {    // È aperto = si vuole chiudere
        detail.classList.remove("dtl-aperto");    // Permette animazione fluida della freccia

        const animeChiusura = contenuto.animate({gridTemplateRows: ["1fr", "0fr"]}, {
          duration: 300,
          easing: "ease"
        });

        animeChiusura.onfinish = () => {
          detail.removeAttribute("open");
        };

      } else {    // È chiuso = si vuole aprire
        detail.classList.add("dtl-aperto");    // Permette animazione fluida della freccia
        detail.setAttribute("open", "");
        
        contenuto.animate({gridTemplateRows: ["0fr", "1fr"]}, {
          duration: 300,
          easing: "ease"
        });
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {

  const dialogApp = document.getElementById("dia-appuntamento");
  const cliente = document.getElementById("nome-cliente");
  const btnChiudi = dialogApp.querySelector(".btn-close");
  
  const btnPopUp = document.querySelectorAll(".btn-popup-app, .btn-modifica-app");

  btnPopUp.forEach(btn => {
    btn.addEventListener("click", () => {
      const nome = btn.dataset.nomeRichiedente;
      cliente.textContent = `Sig./ra. ${nome}`;
      
      dialogApp.showModal();
    });
  });

  const chiudiDialog = () => {
    dialogApp.close();
  };

  btnChiudi.addEventListener("click", chiudiDialog);
});