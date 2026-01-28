const faq = {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [{
        "@type": "Question",
        "name": "Come funziona il percorso di adozione?",
        "acceptedAnswer": {
            "@type": "Answer",
            "text": `Adottare un animale è una scelta consapevole e che dimostra un gesto di vero amore nei suoi confronti. Il nostro rifugio offre la possibilità di inviare una <a href="{{root}}/adotta.php">richiesta</a> per adottare un animale, oppure è possibile recarsi presso la sede fisica per instaurare immediatamente un rapporto di fiducia. Nel caso di una richiesta virtuale si verrà contattati personalmente per fissare un appuntamento e avere la possibilità di conoscerci più a fondo. L'idoneità all'adozione sarà il passaggio finale per portare a casa il tuo nuovo amico a quattro zampe.`
        }
    }, {
        "@type": "Question",
        "name": "Posso adottare se vivo in appartamento e non ho un giardino?",
        "acceptedAnswer": {
            "@type": "Answer",
            "text": "Assolutamente sì! I nostri amici a quattro zampe non hanno bisogno di ettari di terreno, per loro è importante l'amore che gli puoi offrire e la possibilità di fare passeggiate (soprattutto per i cani) in compagnia. Qualsiasi dubbio possa nascere sulla possibilità o meno di un animale di stare in appartemento i nostri volontari sono a disposizione per consigliarti l'amico che meglio apprezza il tuo spazio domestico."
        }
    }, {
        "@type": "Question",
        "name": "Gli animali sono vaccinati e controllati?",
        "acceptedAnswer": {
            "@type": "Answer",
            "text": "Certamente! Ciascun animale possiede il proprio certificato con tutte le vaccinazioni al quale è stato sottoposto. Tutti i nostri amici a quattro zampe, che siano stati salvati dalla strada o accompagnati al nostro Rifugio da padroni consapevoli, affrontano controlli periodici per quanto riguarda possibili malattie/infezioni e il nostro personale veterinario è sempre a disposizione sia in rifugio che anche nella fase post-adozione dovesse servire qualsiasi aiuto."
        }
    }, {
        "@type": "Question",
        "name": "Non ho esperienza con cani o gatti, mi aiuterete?",
        "acceptedAnswer": {
            "@type": "Answer",
            "text": "Il nostro aiuto è un dovere. Sarai accompagnato sia nella fase di pre-adozione, con la corretta consapevolezza sull'azione che porterai avanti, che nella fase di post-adozione. Terminata la fase di adozione i volontari del Rifugio Amici per Sempre sono pronti ad offrire qualsiasi tipologia di supporto, che questo sia emotivo o fisico con la possibilità di donare all'adottante, in casi di necessità, il materiale necessario per fornire il miglior amore possibile all'amico appena adottato."
        }
    }, {
        "@type": "Question",
        "name": "Ho trovato un cane/gatto randagio, cosa posso fare?",
        "acceptedAnswer": {
            "@type": "Answer",
            "text": "Come prima azione da svolgere è fondamentale mettere in sicurezza l'animale, stando attenti a non farsi ferire. Dopo il ritrovamente è importante contattare subito la Polizia Locale del comune di ritrovamento o l'ASL veterinaria di riferimento. Non portare mai l'animale al Rifugio prima di aver chiamato le autorità competenti, le quali si occuperanno della verifica del microchip. Una volta che le autorità avranno effettuato le verifiche necessarie il Rifugio Amici per Sempre accoglierà il povero animale con l'obiettivo di farlo adottare da padroni più consapevoli e amorevoli."
        }
    }]
};

// Creazione ed iniezione dello script all'interno del tag <head>
const script = document.createElement("script");
script.type = "application/ld+json";
script.text = JSON.stringify(faq);
document.head.appendChild(script);