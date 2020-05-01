 Tema este Calculatoare (un site asemanator INFOARENA)
 Pentru Generatorul de probleme am introdus numele "Ghenea Claudiu Florentin"


    Deoarece am implementat majoritatea functionalitatilor dinamic (prin intermediul AJAX si PHP) am atasat si baza de date
(mtarena.sql) ce contine datele aferente proiectului
    Toate requesturile sunt date catre demo.php (nu am reusit sa ii schimb numele nici acum)
    Pentru serviciul de email am folosit PHPMailer pe care l-am dezactivat in speranta sa nu fie erori de compatibilitate
    Am adaugat proiectului suport pentru login prin intermediul Facebook si GooglePlus

    Pagina de login si register sunt facute sub forma unui POP-up si un slider dupa apasarea butonului de login
    Pagina "Problems" contine o lista a problemelor validate, printr-un click pe acestea alte informatii vor fi afisate,
butonul de "View project" nu este functional deoarece nu am reusit sa implementez un mecanism de salvare al problemelor submise,
se poate ajunge la acest meniu dupa conectarea cu un utilizator oarecare.
    In cadrul paginii de "Problems" exista optiunea "New Project" ce va redirecta catre o pagina unde se poate face submiterea unui proiect
momentan se poate doar schita cerinta acestuia prin intermediul unui text editor si al unor scheme de desenare, design insipirat de pe GOOGLECOLAB
pentru adaugarea de noi casute de input.    
    In functie de privilegiile utilizatorului bara de stare se va schimba dupa login oferind acces paginii de Profil, Logout si unde
este cazul Admin. Pentru testare puteti folosi utilizatorul (test@test.ro, test1234567)
    In cadrul paginii admin sunt implementate afisarea numarului curent de utilizatori online, un grafic pe ultimele 2 saptamani
al traficului pe site, precum si accesul la o pagina ce va afisa detalii despre utilizatorii existenti in sistem, permitant editarea,
adaugarea si eliminarea acestora (momentan neimplementat)
    Padina de contact contine cateva informatii si o galerie pentru imagini (de care nu sunt foarte mandru).
    Revenirea la pagina principala se poate face prin apasarea Logo-ului.
    Ca un mic detaliu tehnic am adaugat functionalitate butonului de back chiar daca totul se afla in aceeasi pagina, acesta va schimba la pagina
anterioara.
    Se pot uploada poze de profil pentru fiecare utilizator in meniul Profile.


