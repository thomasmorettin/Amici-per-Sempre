INSERT INTO Razza (Nome, Lingua, Tipo) VALUES
-- Razze di Cani
('Labrador Retriever', 'en', 'Cane'),
('Pastore Tedesco', 'it', 'Cane'),
('Golden Retriever', 'en', 'Cane'),
('Beagle', 'en', 'Cane'),
('Barboncino', 'it', 'Cane'),
('Rottweiler', 'en', 'Cane'),
('Boxer', 'en', 'Cane'),
('Chihuahua', 'en', 'Cane'),
('Husky Siberiano', 'it', 'Cane'),
('Cocker Spaniel', 'en', 'Cane'),
('Meticcio', 'it', 'Cane'),
-- Razze di Gatti
('Persiano', 'it', 'Gatto'),
('Siamese', 'it', 'Gatto'),
('Maine Coon', 'en', 'Gatto'),
('British Shorthair', 'en', 'Gatto'),
('Bengala', 'it', 'Gatto'),
('Ragdoll', 'en', 'Gatto'),
('Europeo', 'it', 'Gatto'),
('Certosino', 'it', 'Gatto'),
('Abissino', 'it', 'Gatto'),
('Norvegese delle Foreste', 'it', 'Gatto');

-- Popolamento tabella Utente
INSERT INTO Utente (Nome, PasswordHash) VALUES
('admin', '$2y$10$OSFliIXbZo7iElK/INvlUeR.rXYqcyZSSlDsZ5oc2IeZ0nSEphQ4.');


-- Popolamento tabella Persona
INSERT INTO Persona (Nome, Cognome, Email, Telefono) VALUES
('Mario', 'Rossi', 'mario.rossi@email.com', '3331234567'),
('Giulia', 'Verdi', 'giulia.verdi@email.com', '3347891234'),
('Luca', 'Bianchi', 'luca.bianchi@email.com', '3356789012'),
('Anna', 'Ferrari', 'anna.ferrari@email.com', '3312345678'),
('Paolo', 'Romano', 'paolo.romano@email.com', '3398765432'),
('Laura', 'Conti', 'laura.conti@email.com', '3341122334'),
('Marco', 'Marino', 'marco.marino@email.com', '3355667788'),
('Chiara', 'Colombo', 'chiara.colombo@email.com', '3369988776'),
('Francesco', 'Ricci', 'francesco.ricci@email.com', '3323456789'),
('Elena', 'Greco', 'elena.greco@email.com', '3334567890'),
('Alessandro', 'Bruno', 'alessandro.bruno@email.com', '3345678901'),
('Valentina', 'De Luca', 'valentina.deluca@email.com', '3356789123'),
('Davide', 'Moretti', 'davide.moretti@email.com', '3367890234'),
('Silvia', 'Fontana', 'silvia.fontana@email.com', '3378901345'),
('Roberto', 'Martini', 'roberto.martini@email.com', '3389012456'),
('Federica', 'Galli', 'federica.galli@email.com', '3390123567'),
('Andrea', 'Costa', 'andrea.costa@email.com', '3301234678'),
('Sara', 'Mancini', 'sara.mancini@email.com', '3312345789'),
('Matteo', 'Lombardi', 'matteo.lombardi@email.com', '3323456890'),
('Martina', 'Caruso', 'martina.caruso@email.com', '3334567901');

INSERT INTO AnimaleRifugio (Nome, Storia, Sesso, Peso, Eta, PthImg, Colore, Caratteristiche, Razza) VALUES
('Max', 'Max è stato trovato abbandonato in un parco durante l''inverno. Nonostante il difficile inizio, è un cane incredibilmente affettuoso e socievole. Ama giocare con la palla e fare lunghe passeggiate.', 'M', 28.5, 3.5, '001.jpg', 'Dorato', '["Sterilizzato", "Vaccinato", "Con microchip", "Socievole con altri cani", "Ama i bambini", "Adatto alla vita in appartamento"]', 'Labrador Retriever'),
('Luna', 'Luna proviene da un canile sovraffollato dove non riceveva le attenzioni che meritava. All''inizio era molto timida, ma con pazienza e amore ha imparato a fidarsi. Oggi è una compagna dolce e fedele che aspetta solo di trovare la sua famiglia per sempre.', 'F', 25.0, 4.0, '002.jpg', 'Nero', '["Sterilizzata", "Vaccinata", "Con microchip", "Addestrata ai comandi base", "Necessita pazienza iniziale", "Ottima guardiana"]', 'Pastore Tedesco'),
('Rocky', 'Rocky ha lavorato come cane da guardia per anni prima di essere portato al rifugio. Ha bisogno di un proprietario esperto che sappia gestire la sua forza e il suo carattere forte. Con la persona giusta diventa un compagno leale e protettivo.', 'M', 45.0, 7.5, '003.jpg', 'Nero/Marrone', '["Sterilizzato", "Vaccinato", "Con microchip", "Addestrato professionalmente", "Necessita proprietario esperto", "Non adatto a bambini piccoli"]', 'Rottweiler'),
('Bella', 'Bella è stata trovata vagante per le strade quando aveva solo pochi mesi. È una cucciola piena di vita ed energia che adora giocare e esplorare. Perfetta per una famiglia attiva che possa dedicarle tempo e attenzioni.', 'F', 8.5, 0.8, '004.jpg', 'Tricolore', '["Vaccinata", "Con microchip", "Molto energica", "In fase di addestramento", "Perfetta per famiglie attive", "Ama giocare"]', 'Beagle'),
('Charlie', 'Charlie ha trascorso tutta la vita con la sua precedente famiglia, ma dopo la scomparsa del suo proprietario anziano si è ritrovato al rifugio. È un cane tranquillo e affettuoso che cerca una casa serena dove passare gli ultimi anni in pace e comfort.', 'M', 30.0, 1.0, '005.jpg', 'Dorato', '["Sterilizzato", "Vaccinato", "Con microchip", "Addestrato", "Carattere calmo e tranquillo", "Ideale per anziani", "Necessita cure veterinarie regolari"]', 'Golden Retriever'),
('Daisy', 'Daisy è stata salvata da una situazione di maltrattamento. Nonostante il passato difficile, ha un cuore enorme e tanto amore da dare. Cerca una famiglia paziente che le dimostri che il mondo può essere un posto sicuro e felice.', 'F', 22.0, 4.0, '006.jpg', 'Bianco/Nero', '["Sterilizzata", "Vaccinata", "Con microchip", "Cerca affetto e sicurezza", "Leale e protettiva", "Necessita ambiente tranquillo"]', 'Meticcio'),
('Rex', 'Rex è arrivato al rifugio quando la sua famiglia si è trasferita all''estero. È un cane giovane, vivace e pieno di energia. Adora giocare, correre e fare lunghe passeggiate. Sarebbe perfetto per chi pratica sport o attività all''aria aperta.', 'M', 32.0, 3.0, '007.jpg', 'Fulvo', '["Sterilizzato", "Vaccinato", "Con microchip", "Addestrato", "Molto giocherellone", "Ama correre e fare attività", "Perfetto per sport cinofili"]', 'Boxer'),
('Molly', 'Molly è stata ceduta al rifugio perché la sua famiglia ha avuto un bambino con allergie. È una cagnolina dolcissima, paziente ed estremamente affettuosa. È cresciuta con bambini e sa come comportarsi in loro presenza.', 'F', 27.0, 4.5, '008.jpg', 'Marrone', '["Sterilizzata", "Vaccinata", "Con microchip", "Addestrata", "Eccellente con i bambini", "Carattere dolce e paziente", "Ideale come primo cane"]', 'Cocker Spaniel'),
('Zeus', 'Zeus è stato abbandonato perché la sua famiglia sottovalutava le esigenze di un Husky. È un cane bellissimo, intelligente e forte che ha bisogno di molto esercizio fisico e di uno spazio adeguato. Ama il freddo e le attività all''aperto.', 'M', 35.0, 6.0, '009.jpg', 'Grigio/Bianco', '["Sterilizzato", "Vaccinato", "Con microchip", "Addestrato", "Necessita molto esercizio", "Non adatto a climi caldi", "Ama la neve"]', 'Husky Siberiano'),
('Lola', 'Lola è una piccola Chihuahua che è stata trovata smarrita in centro città. Nonostante le dimensioni ridotte ha un grande carattere. È vivace, affettuosa e perfetta per chi vive in appartamento e cerca un compagno di piccola taglia.', 'F', 3.5, 0.5, '010.jpg', 'Marrone', '["Sterilizzata", "Vaccinata", "Con microchip", "Perfetta per appartamento", "Molto affettuosa", "Facile da trasportare"]', 'Chihuahua'),
('Buddy', 'Buddy è stato lasciato al rifugio quando il suo proprietario ha dovuto trasferirsi in una struttura che non accettava animali. È un cane adorabile che ama stare in compagnia e soffre la solitudine. Va d''accordo con altri animali ed è sempre pronto per le coccole.', 'M', 26.0, 1.5, '011.jpg', 'Bianco/Nero', '["Sterilizzato", "Vaccinato", "Con microchip", "Ama stare in compagnia", "Va d\'accordo con altri animali", "Soffre la solitudine"]', 'Meticcio'),
('Coco', 'Coco è una barboncina elegante e raffinata che cerca una famiglia che possa dedicarle le attenzioni che merita. È intelligente, vivace e il suo pelo ipoallergenico la rende adatta anche a persone con allergie lievi.', 'F', 5.5, 3.0, '012.jpg', 'Bianco', '["Sterilizzata", "Vaccinata", "Con microchip", "Addestrata", "Pelo che necessita toelettatura regolare", "Intelligente e vivace", "Ipoallergenica"]', 'Barboncino'),
('Micio', 'Micio è un gatto europeo dal carattere equilibrato. È stato trovato che vagava per il quartiere, probabilmente abbandonato. È indipendente ma ama anche ricevere coccole quando decide lui. Perfetto per chi cerca un compagno felino tranquillo.', 'M', 5.2, 3.0, '013.jpg', 'Arancione', '["Sterilizzato", "Vaccinato", "Con microchip", "Solo per vita indoor", "Indipendente ma affettuoso", "Silenzioso"]', 'Europeo'),
('Whiskers', 'Whiskers è un gatto certosino molto socievole che adora la compagnia umana. È stato ceduto al rifugio perché la sua famiglia si è trasferita all''estero. Ama essere coccolato e si trova bene anche con i bambini rispettosi.', 'M', 6.8, 5.5, '014.jpg', 'Grigio', '["Sterilizzato", "Vaccinato", "Con microchip", "Solo per vita indoor", "Molto socievole", "Ama essere coccolato", "Ottimo con bambini"]', 'Certosino'),
('Mimi', 'Mimi è una giovane gattina persiana dal pelo lungo e setoso. È stata trovata sola in un parco, probabilmente persa. Ha un carattere dolce e giocoso, perfetta per chi cerca un''amica felina da coccolare.', 'F', 3.5, 1.5, '015.jpg', 'Bianco/Grigio', '["Sterilizzata", "Vaccinata", "Con microchip", "Solo per vita indoor", "Pelo lungo che necessita spazzolature", "Giocherellona", "Carattere dolce"]', 'Persiano'),
('Felix', 'Felix è un Siamese puro con il tipico carattere vocale della razza. Ama comunicare con i suoi umani e cerca attenzioni costanti. È intelligente, curioso e perfetto per chi desidera un gatto interattivo e presente.', 'M', 4.2, 2.0, '016.jpg', 'Seal Point', '["Sterilizzato", "Vaccinato", "Con microchip", "Solo per vita indoor", "Molto comunicativo", "Cerca attenzioni costanti", "Intelligente e curioso"]', 'Siamese'),
('Snowball', 'Snowball è una gattina persiana dal pelo candido come la neve. È stata ceduta al rifugio perché richiedeva troppe cure per il mantello. Ha un carattere placido e tranquillo, ideale per chi cerca un compagno sereno e ama la routine della toelettatura.', 'F', 5.8, 4.5, '017.jpg', 'Bianco', '["Sterilizzata", "Vaccinata", "Con microchip", "Solo per vita indoor", "Pelo lungo da spazzolare quotidianamente", "Carattere placido", "Ama la tranquillità"]', 'Persiano'),
('Tiger', 'Tiger è un magnifico Maine Coon di taglia grande. È stato lasciato al rifugio quando si è rivelato troppo ingombrante per l''appartamento della sua famiglia. È un gatto maestoso che necessita di spazio ma è anche dolce e affettuoso.', 'M', 8.5, 3.5, '018.jpg', 'Tabby', '["Sterilizzato", "Vaccinato", "Con microchip", "Può vivere anche outdoor", "Taglia grande", "Necessita spazio", "Cacciatore abile"]', 'Maine Coon'),
('Princess', 'Princess è una Ragdoll che fa onore al nome della sua razza: si lascia andare completamente quando viene presa in braccio. È estremamente docile e affettuosa, perfetta per chi cerca un gatto coccolone e tranquillo da tenere in appartamento.', 'F', 5.0, 2.5, '019.jpg', 'Crema', '["Sterilizzata", "Vaccinata", "Con microchip", "Solo per vita indoor", "Estremamente docile", "Ama stare in braccio", "Perfetta per appartamento"]', 'Ragdoll'),
('Shadow', 'Shadow è un gatto nero europeo dal carattere indipendente e misterioso. È stato trovato che viveva per strada e ha mantenuto il suo istinto da cacciatore. È perfetto per chi cerca un gatto che possa godere anche di spazi esterni controllati.', 'M', 4.8, 6.0, '020.jpg', 'Nero', '["Sterilizzato", "Vaccinato", "Con microchip", "Può vivere anche outdoor", "Indipendente", "Ottimo cacciatore", "Notturno"]', 'Europeo'),
('Ginger', 'Ginger è una giovane gattina rossa piena di energia e curiosità. È stata trovata come randagia nel nostro giardino. Ama esplorare ogni angolo e giocare con qualsiasi cosa si muova. Ideale per una famiglia giovane e dinamica.', 'F', 4.0, 1.6, '021.jpg', 'Rosso', '["Vaccinata", "Con microchip", "Solo per vita indoor", "Molto giocosa", "Curiosa ed esploratrice", "Adatta a famiglie giovani", "Da sterilizzare"]', 'Europeo'),
('Oliver', 'Oliver è un British Shorthair dal carattere flemmatico e tranquillo. È stato ceduto per motivi di allergia familiare. È un gatto perfetto per la vita in appartamento, facile da gestire e dal pelo corto che richiede poche cure.', 'M', 6.0, 5.0, '022.jpg', 'Grigio/Blu', '["Sterilizzato", "Vaccinato", "Con microchip", "Solo per vita indoor", "Carattere flemmatico", "Facile da gestire", "Pelo corto"]', 'British Shorthair'),
('Cleo', 'Cleo è una splendida gattina Bengala dal mantello maculato. È molto attiva e atletica, ama saltare e arrampicarsi. Necessita di stimoli e giochi per esprimere la sua energia. Perfetta per chi cerca un gatto dinamico e coinvolgente.', 'F', 3.8, 3.0, '023.jpg', 'Spotted', '["Sterilizzata", "Vaccinata", "Con microchip", "Solo per vita indoor", "Molto attiva", "Necessita stimoli e giochi", "Atletica e agile"]', 'Bengala'),
('Simba', 'Simba è un Norvegese delle Foreste dal pelo semilungo e dal carattere avventuroso. Ama arrampicarsi e esplorare, è resistente al freddo grazie al suo folto mantello. Ideale per chi ha spazi esterni sicuri e cerca un gatto robusto e coraggioso.', 'M', 7.2, 4.0, '024.jpg', 'Rosso/Bianco', '["Sterilizzato", "Vaccinato", "Con microchip", "Può vivere anche outdoor", "Pelo semilungo", "Resistente al freddo", "Ama arrampicarsi"]', 'Norvegese delle Foreste'),
('Nala', 'Nala è una bellissima gattina Abissina dal mantello fulvo e dal carattere vivace. È elegante nei movimenti ed estremamente energica. Ha la particolarità di amare giocare con l\'acqua, cosa rara per un gatto. Perfetta per chi cerca un compagno attivo e curioso.', 'F', 4.5, 2.0, '025.jpg', 'Fulvo', '["Sterilizzata", "Vaccinata", "Con microchip", "Solo per vita indoor", "Molto elegante", "Energica e vivace", "Ama giocare con l\'acqua"]', 'Abissino');

-- Popolamento tabella EntitaDatabile
INSERT INTO EntitaDatabile (Note, DataRichiesta) VALUES
-- Ticket per adozioni (ID 1-20)
('Richiesta di adozione - famiglia con giardino', '2026-01-22'),
('Richiesta di adozione - appartamento in città', '2026-01-23'),
('Richiesta di adozione - casa con altri animali', '2026-01-24'),
('Richiesta di adozione - prima esperienza', '2026-01-26'),
('Richiesta di adozione - esperto di cani grandi', '2026-01-27'),
('Richiesta di adozione - cerca gatto tranquillo', '2026-01-28'),
('Richiesta di adozione - famiglia numerosa', '2026-01-29'),
('Richiesta di adozione - persona anziana', '2026-01-30'),
('Richiesta di adozione - giovane coppia', '2026-02-02'),
('Richiesta di adozione - casa in campagna', '2026-02-03'),
('Richiesta di adozione - appartamento piccolo', '2026-02-04'),
('Richiesta di adozione - amante dei gatti', '2026-02-05'),
('Richiesta di adozione - seconda adozione', '2026-02-06'),
('Richiesta di adozione - cerca cane da compagnia', '2026-02-09'),
('Richiesta di adozione - famiglia con bambini', '2026-02-10'),
('Richiesta di adozione - cerca gatto giocherellone', '2026-02-11'),
('Richiesta di adozione - cerca cane di piccola taglia', '2026-02-12'),
('Richiesta di adozione - cerca animale anziano', '2026-02-13'),
('Richiesta di adozione - amante dello sport', '2026-02-16'),
('Richiesta di adozione - cerca gatto da esterno', '2026-02-17'),
-- Animali esterni lasciati al rifugio (ID 21-40)
('Abbandono - trasferimento proprietario all''estero', '2026-01-23'),
('Affidamento temporaneo - problemi di salute proprietario', '2026-01-24'),
('Abbandono - allergia familiare improvvisa', '2026-01-26'),
('Affidamento - proprietario ricoverato', '2026-01-27'),
('Abbandono - cambio abitazione', '2026-01-28'),
('Affidamento temporaneo - situazione economica difficile', '2026-01-29'),
('Abbandono - incompatibilità con altri animali', '2026-01-30'),
('Affidamento - decesso proprietario', '2026-02-02'),
('Abbandono - troppo impegnativo', '2026-02-03'),
('Affidamento temporaneo - ristrutturazione casa', '2026-02-04'),
('Abbandono - nascita bambino', '2026-02-05'),
('Affidamento - problemi comportamentali non gestibili', '2026-02-06'),
('Abbandono - divorzio', '2026-02-09'),
('Affidamento temporaneo - viaggio prolungato', '2026-02-10'),
('Abbandono - animale trovato per strada', '2026-02-11'),
('Abbandono - trasferimento in casa senza animali', '2026-02-12'),
('Affidamento temporaneo - intervento chirurgico proprietario', '2026-02-13'),
('Abbandono - troppo grande per l''appartamento', '2026-02-16'),
('Affidamento - famiglia con nuovo membro allergico', '2026-02-17'),
('Abbandono - perdita di lavoro proprietario', '2026-02-18');

INSERT INTO Calendario (ID, Data, Ora) VALUES
-- Appuntamenti per ticket di adozione (ID 1-15, lasciando 16-20 da gestire)
(1, '2026-01-26', '10:00:00'),  -- Lunedì
(2, '2026-01-26', '14:30:00'),  -- Lunedì
(3, '2026-01-27', '11:00:00'),  -- Martedì
(4, '2026-01-28', '15:00:00'),  -- Mercoledì
(5, '2026-01-29', '09:30:00'),  -- Giovedì
(6, '2026-01-30', '14:00:00'),  -- Venerdì
(7, '2026-02-02', '10:30:00'),  -- Lunedì
(8, '2026-02-03', '16:00:00'),  -- Martedì
(9, '2026-02-04', '11:30:00'),  -- Mercoledì
(10, '2026-02-05', '15:30:00'), -- Giovedì
(11, '2026-02-06', '09:00:00'), -- Venerdì
(12, '2026-02-09', '14:30:00'), -- Lunedì
(13, '2026-02-10', '10:00:00'), -- Martedì
(14, '2026-02-11', '16:30:00'), -- Mercoledì
(15, '2026-02-12', '11:00:00'), -- Giovedì
-- ID 16-20 NON hanno appuntamenti in calendario (da gestire)
-- Appuntamenti per accoglienza animali esterni (ID 21-35, lasciando 36-40 da gestire)
(21, '2026-01-26', '09:00:00'), -- Lunedì
(22, '2026-01-27', '10:00:00'), -- Martedì
(23, '2026-01-28', '11:00:00'), -- Mercoledì
(24, '2026-01-29', '14:00:00'), -- Giovedì
(25, '2026-01-30', '15:30:00'), -- Venerdì
(26, '2026-02-02', '09:30:00'), -- Lunedì
(27, '2026-02-03', '10:30:00'), -- Martedì
(28, '2026-02-04', '14:30:00'), -- Mercoledì
(29, '2026-02-05', '11:00:00'), -- Giovedì
(30, '2026-02-06', '15:00:00'), -- Venerdì
(31, '2026-02-09', '09:00:00'), -- Lunedì
(32, '2026-02-10', '11:30:00'), -- Martedì
(33, '2026-02-11', '10:00:00'), -- Mercoledì
(34, '2026-02-12', '14:30:00'), -- Giovedì
(35, '2026-02-13', '16:30:00'); -- Venerdì
-- ID 36-40 NON hanno appuntamenti in calendario (da gestire)

-- Popolamento tabella AnimaleEsterno
INSERT INTO AnimaleEsterno (ID, Sesso, Peso, Eta, Proprietario, Razza) VALUES
(21, 'M', '15 kg', '5 anni', 1, 'Cocker Spaniel'),
(22, 'F', '4 kg', '2 anni', 2, 'Chihuahua'),
(23, 'M', '30 kg', '7 anni', 3, 'Labrador Retriever'),
(24, 'F', '6 kg', '3 anni', 4, 'Persiano'),
(25, 'M', '22 kg', '4 anni', 5, 'Beagle'),
(26, 'M', '35 kg', '6 anni', 6, 'Pastore Tedesco'),
(27, 'F', '5 kg', '8 anni', 7, 'Europeo'),
(28, 'M', '28 kg', '5 anni', 8, 'Golden Retriever'),
(29, 'F', '4 kg', '1 anno', 9, 'Siamese'),
(30, 'M', '25 kg', '3 anni', 10, 'Boxer'),
(31, 'F', '7 kg', '4 anni', 11, 'Maine Coon'),
(32, 'M', '12 kg', '9 anni', 12, 'Barboncino'),
(33, 'F', '5 kg', '2 anni', 13, 'British Shorthair'),
(34, 'M', '40 kg', '8 anni', 14, 'Rottweiler'),
(35, 'M', '6 kg', '10 anni', 15, 'Certosino'),
(36, 'F', '18 kg', '3 anni', 16, 'Meticcio'),
(37, 'M', '5 kg', '4 anni', 17, 'Abissino'),
(38, 'F', '27 kg', '6 anni', 18, 'Husky Siberiano'),
(39, 'M', '4 kg', '5 anni', 19, 'Ragdoll'),
(40, 'F', '6 kg', '2 anni', 20, 'Bengala');

-- Popolamento tabella Ticket
INSERT INTO Ticket (ID, Richiedente, Animale) VALUES
(1, 1, 1),   -- Mario Rossi interessato a Max
(2, 2, 13),  -- Giulia Verdi interessata a Micio
(3, 3, 2),   -- Luca Bianchi interessato a Luna
(4, 4, 4),   -- Anna Ferrari interessata a Bella
(5, 5, 3),   -- Paolo Romano interessato a Rocky
(6, 6, 14),  -- Laura Conti interessata a Whiskers
(7, 7, 5),   -- Marco Marino interessato a Charlie
(8, 8, 15),  -- Chiara Colombo interessata a Mimi
(9, 9, 7),   -- Francesco Ricci interessato a Rex
(10, 10, 9), -- Elena Greco interessata a Zeus
(11, 11, 10),-- Alessandro Bruno interessato a Lola
(12, 12, 17),-- Valentina De Luca interessata a Snowball
(13, 13, 11),-- Davide Moretti interessato a Buddy
(14, 14, 8), -- Silvia Fontana interessata a Molly
(15, 15, 20),-- Roberto Martini interessato a Shadow
(16, 16, 21),-- Federica Galli interessata a Ginger (SENZA APPUNTAMENTO)
(17, 17, 10),-- Andrea Costa interessato a Lola (SENZA APPUNTAMENTO)
(18, 18, 5), -- Sara Mancini interessata a Charlie (SENZA APPUNTAMENTO)
(19, 19, 7), -- Matteo Lombardi interessato a Rex (SENZA APPUNTAMENTO)
(20, 20, 22);-- Martina Caruso interessata a Oliver (SENZA APPUNTAMENTO)