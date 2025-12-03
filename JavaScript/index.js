document.addEventListener("DOMContentLoaded", () => {
  const faq = document.querySelectorAll("#section-faqs details");

  faq.forEach(detail => {
    const summary = detail.querySelector("summary");
    const contenuto = detail.querySelector("details > div");

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
        const faqArray = Array.from(faq);
        const openDetail = faqArray.find(detail => detail.hasAttribute("open"));

        if (openDetail) {
            const openContenuto = openDetail.querySelector("details > div");
            openDetail.classList.remove("dtl-aperto");    // Permette animazione fluida della freccia

            const closeOpenCont = openContenuto.animate({gridTemplateRows: ["1fr", "0fr"]}, {
            duration: 300,
            easing: "ease"
          });

          closeOpenCont.onfinish = () => {
            openDetail.removeAttribute("open");
          };
        }

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