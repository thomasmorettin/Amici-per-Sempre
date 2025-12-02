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