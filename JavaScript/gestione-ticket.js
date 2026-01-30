import { checkInput, updateDialogRichiesta, deleteDialogRichiesta, infoDialog, currentNumRich } from "./genera-dialogs.js";

document.addEventListener("DOMContentLoaded", () => {
  animaleDetails();
  dialogsSettings();
  checkInput();
})

// Funzione per apertura/chiusura del tag details per ciascun animale del rifugio
function animaleDetails() {
  const details = document.querySelectorAll("details.dtl-animale");

  details.forEach(detail => {
    const summary = detail.querySelector("summary");
    const contenuto = detail.querySelector(".contenuto-nascosto");

    summary.addEventListener("click", (event) => {
      event.preventDefault();   // Prevenzione dell'azione di default

      if (detail.open) {    // È aperto = si vuole chiudere
        detail.classList.remove("is-expanded");    // Permette animazione fluida della freccia

        const animeChiusura = contenuto.animate({gridTemplateRows: ["1fr", "0fr"]}, {
          duration: 300,
          easing: "ease"
        })

        animeChiusura.onfinish = () => {
          detail.removeAttribute("open");
        }

      }
      
      else {    // È chiuso = si vuole aprire
        detail.classList.add("is-expanded");    // Permette animazione fluida della freccia
        detail.setAttribute("open", "");
        
        contenuto.animate({gridTemplateRows: ["0fr", "1fr"]}, {
          duration: 300,
          easing: "ease"
        })
      }
    })
  })

  currentNumRich();
}

// Funzione per la gestione di tutti i dialog all'interno della pagina
function dialogsSettings() {
  const body = document.body;
  
  updateDialogRichiesta(body);
  deleteDialogRichiesta(body);
  infoDialog(body);
}