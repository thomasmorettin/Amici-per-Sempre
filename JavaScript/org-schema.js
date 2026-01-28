const organizationData = {
    "@context": "https://schema.org",
    "@type": "AnimalShelter",       // Specifico per il settore del Rifugio
    "name": "Rifugio Amici per Sempre",
    "url": PROJECT_ROOT,
    "logo": PROJECT_ROOT + "/Resources/Vectors/LogoDark.svg",
    "image": PROJECT_ROOT + "/Resources/Images/Operatori.jpg",
    "description": "Rifugio per cani e gatti a Padova. Adozioni, supporto veterinario ed addestramento.",
    "telephone": "+390491234567",
    "email": "rifugio.amicipersempre@gmail.com",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Via Trieste, 63",
        "addressLocality": "Padova",
        "postalCode": "35121",
        "addressCountry": "IT",
        "addressRegion": "Veneto"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": 45.41118,
        "longitude": 11.88757
    },
    "openingHoursSpecification": [
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "08:30",
            "closes": "19:30"
        },
        {   // Orari differenti per il weekend
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Saturday"],
            "opens": "08:30",
            "closes": "12:30"
        }
    ],
    "priceRange": "$"       // Organizzazione no-profit
    /* "sameAs": [     // Possibile collegamento ai socials per l'identità anche se non esistenti
        "https://www.facebook.com/",
        "https://www.instagram.com/",
        "https://x.com/"
    ] */
};