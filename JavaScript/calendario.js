document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".btn-toggle");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            const selectBtn = document.querySelector(".btn-toggle.active");
            selectBtn.classList.remove("active");

            this.classList.add("active");
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {

  const appuntamento = document.getElementById("dia-appuntamento");
  const cliente = document.getElementById("nome-cliente");
  const btnChiudi = dialogApp.querySelector(".btn-close");
  
  const btnPopUp = document.querySelectorAll(".btn-popup-app, .btn-elimina-app");

  btnPopUp.forEach(btn => {
    btn.addEventListener("click", () => {
      const nome = btn.dataset.nomeRichiedente;
      cliente.textContent = `${nome}`;
      
      dialogApp.showModal();
    });
  });

  const chiudiDialog = () => {
    dialogApp.close();
  };

  btnChiudi.addEventListener("click", chiudiDialog);
});

function pageScroll() {
  const floating = document.getElementById("btns-flottanti");

  if (floating) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 200) {
        floating.classList.add("scrolled");
      } else {
        floating.classList.remove("scrolled");
      }
    });
  }
}

pageScroll();