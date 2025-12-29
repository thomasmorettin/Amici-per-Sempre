document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".btn-toggle");
    const weeks = document.querySelectorAll("section");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            const selectBtn = document.querySelector(".btn-toggle.active");
            if (selectBtn) { selectBtn.classList.remove("active"); }

            this.classList.add("active");

            weeks.forEach(week => {
              week.classList.add("hidden");
            });

            const targetId = this.getAttribute("data-target");
            const targetWeek = document.getElementById(targetId);
            if (targetWeek) { targetWeek.classList.remove("hidden"); }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;

  const dialogApp = document.getElementById("dia-appuntamento");
  const dialogDel = document.getElementById("dia-cancellazione");

  const btnChiudiApp = dialogApp.querySelector(".btn-close");
  const btnChiudiDel = dialogDel.querySelector(".btn-close");
  
  const btnPopUp = document.querySelectorAll(".btn-popup-app");
  const btnDel = document.querySelectorAll(".btn-elimina-app");

  btnPopUp.forEach(btn => {
    btn.addEventListener("click", () => {
      dialogApp.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("data-appuntamento").innerHTML = `${btn.dataset.data}`;
      document.getElementById("ora-appuntamento").innerHTML = `${btn.dataset.ora}`;
      
      dialogApp.showModal();
      body.classList.add("no-scroll");
    });
  });

  btnDel.forEach(btn => {
    btn.addEventListener("click", () => {
      dialogDel.querySelector(".nome-cliente").innerHTML = `${btn.dataset.nome}`;
      document.getElementById("data-app").innerHTML = `${btn.dataset.data}`;
      document.getElementById("ora-app").innerHTML = `${btn.dataset.ora}`;

      dialogDel.showModal();
      body.classList.add("no-scroll");
    })
  })

  const chiudiDialogApp = () => {
    dialogApp.close();
    body.classList.remove("no-scroll");
  };

  const chiudiDialogDel = () => {
    dialogDel.close();
    body.classList.remove("no-scroll");
  };

  btnChiudiApp.addEventListener("click", chiudiDialogApp);
  btnChiudiDel.addEventListener("click", chiudiDialogDel);
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