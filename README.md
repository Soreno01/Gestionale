# Gestione Documentale Personale – Istruzioni

## Requisiti
- PHP 8+
- Estensione `zip` abilitata
- Estensione `json` abilitata
- [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/) per l’importazione Excel (mettere in `/vendor`)

## Struttura delle cartelle
```
/config/config.php
/employees/        (qui verranno create le cartelle dei dipendenti)
/logs/admin.log
/assets/css/custom.css
/assets/js/main.js
/vendor/           (librerie esterne)
/uploads/          (eventuali upload temporanei)
```

## Avvio
1. Copia tutti i file nelle cartelle indicate.
2. Accedi a `login.php`
   - User admin: `admin`
   - Password predefinita: `admin123` (modifica subito il valore in `config.php`!)
3. Registra o importa i dipendenti (manuale o Excel).
4. Carica i documenti (ZIP massivo o singoli PDF).

## Funzioni principali
- **Login amministratore/dipendente**
- **Gestione dipendenti:** registra, importa, reset password
- **Carica documenti:** ZIP multiplo o singolo per ogni dipendente
- **Visualizza documenti:** per ogni dipendente, scarica o elimina
- **Dashboard dipendente:** download documenti propri
- **Log azioni:** visualizza/scarica log amministrativi

## Sicurezza
- Cambia la password admin dopo il primo accesso!
- Ogni dipendente ha una password casuale generata alla creazione/importazione.

## Note
- L’importazione da Excel richiede la libreria PhpSpreadsheet.
- I documenti devono essere PDF.

## Supporto
Contatta l’amministratore del sistema per eventuali problemi.
