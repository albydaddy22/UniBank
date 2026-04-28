per avviare il sito e testare fare i seguenti passaggi: 
1- avvia su xampp apache e mysql, poi vai su 127.0.0.1/phpmyadmin
2- apri il file install/install.html da localhost
3- compila il form, poi installa (se come username metti root, non inserire la password, a meno che sul tuo dispositivo siano modificate le impostazioni di default di mysql)
4- ti troverai sulla pagina di login del nostro sito, puoi testare e far finta di essere un admin o un utente, segui questi passaggi:
ADMIN: inserisci nel campo email "admin@unibank.it"; password: admin123 (password hashata: $2y$10$BD5U4OmkC5XNsdreEPHIWeY2L0Sm9Q6S9PnbEcm3lfy0UturghFum)
UTENTE NORMALE: campo email "utente@email.it"; password: utente123 (password hashata: $2y$10$TlFlxinOGhp9pdSOhh75/On.06pm7RcTi48.eMdRSRmKW9Cq8pi1C)
password hashate utili nel caso in cui il db non riconosca le password, quindi resettare direttamente da 127.0.0.1/phpmyadmin le password, inserendo quelle hashate qui sopra

REGISTRAZIONE
se si vuole provare la registrazione al nostro sito, schiaccia il pulsante registrati in basso al form.
