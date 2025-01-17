<?php

/**
 * IT-Revision: 04.Apr.2013
 */
return [
    // Laminas\I18n\Validator\Alnum
    "Invalid type given. String, integer or float expected" => "Tipo di dato non valido. Era atteso un dato di tipo string, float o integer",
    "The input contains characters which are non alphabetic and no digits" => "L'input contiene caratteri che non sono alfanumerici",
    "The input is an empty string" => "L'input è una stringa vuota",

    // Laminas\I18n\Validator\Alpha
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input contains non alphabetic characters" => "L'input contiene caratteri non alfabetici",
    "The input is an empty string" => "L'input è una stringa vuota",

    // Laminas\I18n\Validator\DateTime
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input does not appear to be a valid datetime" => "L'input non sembra essere una combinazione valida di data e orario",

    // Laminas\I18n\Validator\Float
    "Invalid type given. String, integer or float expected" => "Tipo di dato non valido. Era atteso un dato di tipo string, float o integer",
    "The input does not appear to be a float" => "L'input non sembra essere un dato di tipo float",

    // Laminas\I18n\Validator\Int
    "Invalid type given. String or integer expected" => "Tipo di dato non valido. Era atteso un dato di tipo string o integer",
    "The input does not appear to be an integer" => "L'input non sembra essere un intero",

    // Laminas\I18n\Validator\PhoneNumber
    "The input does not match a phone number format" => "L'input non corrisponde ad un formato di numero telefonico",
    "The country provided is currently unsupported" => "La nazionalità fornita non è correntemente supportata",
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",

    // Laminas\I18n\Validator\PostCode
    "Invalid type given. String or integer expected" => "Tipo di dato non valido. Era atteso un dato di tipo string o integer",
    "The input does not appear to be a postal code" => "L'input non sembra essere un codice postale",
    "An exception has been raised while validating the input" => "Un'eccezione è stata sollevata durante la validazione dell'input",

    // Laminas\Validator\Barcode
    "The input failed checksum validation" => "L'input non ha un checksum valido",
    "The input contains invalid characters" => "L'input contiene caratteri non permessi",
    "The input should have a length of %length% characters" => "L'input non ha la lunghezza corretta di %length% caratteri",
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",

    // Laminas\Validator\Between
    "The input is not between '%min%' and '%max%', inclusively" => "L'input non è compreso tra '%min%' e '%max%', inclusi",
    "The input is not strictly between '%min%' and '%max%'" => "L'input non è strettamente compreso tra '%min%' e '%max%'",

    // Laminas\Validator\Callback
    "The input is not valid" => "L'input non è valido",
    "An exception has been raised within the callback" => "Un'eccezione è stata sollevata all'interno della callback",

    // Laminas\Validator\CreditCard
    "The input seems to contain an invalid checksum" => "L'input sembra avere un checksum non valido",
    "The input must contain only digits" => "L'input deve contenere solo cifre",
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input contains an invalid amount of digits" => "L'input contiene un numero non valido di cifre",
    "The input is not from an allowed institute" => "L'input proviene da un istituto non supportato",
    "The input seems to be an invalid credit card number" => "L'input sembra essere un numero di carta di credito non valido",
    "An exception has been raised while validating the input" => "Un'eccezione è stata sollevata durante la validazione dell'input",

    // Laminas\Validator\Csrf
    "The form submitted did not originate from the expected site" => "La form inviata non ha avuto origine dal luogo previsto",

    // Laminas\Validator\Date
    "Invalid type given. String, integer, array or DateTime expected" => "Tipo di dato non valido. Era atteso un dato di tipo string, integer, array o DateTime",
    "The input does not appear to be a valid date" => "L'input non sembra essere una data valida",
    "The input does not fit the date format '%format%'" => "L'input non corrisponde al formato data '%format%'",

    // Laminas\Validator\DateStep
    "The input is not a valid step" => "L'input non è uno step valido",

    // Laminas\Validator\Db\AbstractDb
    "No record matching the input was found" => "Non è stata trovata nessuna riga corrispondente all'input",
    "A record matching the input was found" => "È già stata trovata una riga corrispondente all'input",

    // Laminas\Validator\Digits
    "The input must contain only digits" => "L'input deve contenere solo cifre",
    "The input is an empty string" => "L'input è una stringa vuota",
    "Invalid type given. String, integer or float expected" => "Tipo di dato non valido. Era atteso un dato di tipo string, float o integer",

    // Laminas\Validator\EmailAddress
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input is not a valid email address. Use the basic format local-part@hostname" => "L'input non è un indirizzo email valido nel formato base local-part@hostname",
    "'%hostname%' is not a valid hostname for the email address" => "'%hostname%' non è un hostname valido nell'indirizzo email",
    "'%hostname%' does not appear to have any valid MX or A records for the email address" => "'%hostname%' non sembra avere un record MX o A valido per l'indirizzo email",
    "'%hostname%' is not in a routable network segment. The email address should not be resolved from public network" => "'%hostname%' non è in un segmento di rete instradabile. L'indirizzo email non può essere risolto nella rete pubblica",
    "'%localPart%' can not be matched against dot-atom format" => "'%localPart%' non può essere validato nel formato dot-atom",
    "'%localPart%' can not be matched against quoted-string format" => "'%localPart%' non può essere validato nel formato quoted-string",
    "'%localPart%' is not a valid local part for the email address" => "'%localPart%' non è una local part valida nell'indirizzo email",
    "The input exceeds the allowed length" => "L'input supera la lunghezza consentita",

    // Laminas\Validator\Explode
    "Invalid type given" => "Tipo di dato non valido",

    // Laminas\Validator\File\Count
    "Too many files, maximum '%max%' are allowed but '%count%' are given" => "Troppi file, sono consentiti massimo '%max%' file ma ne sono stati passati '%count%'",
    "Too few files, minimum '%min%' are expected but '%count%' are given" => "Troppi pochi file, sono attesi minimo '%min%' file ma ne sono stato passati solo '%count%'",

    // Laminas\Validator\File\Crc32
    "File does not match the given crc32 hashes" => "Il file non ha un hash crc32 tra quelli consentiti",
    "A crc32 hash could not be evaluated for the given file" => "Non può essere calcolato un hash crc32 per il file dato",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\ExcludeExtension
    "File has an incorrect extension" => "Il file ha un'estensione non valida",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\Exists
    "File does not exist" => "Il file non esiste",

    // Laminas\Validator\File\Extension
    "File has an incorrect extension" => "Il file ha un'estensione non valida",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\FilesSize
    "All files in sum should have a maximum size of '%max%' but '%size%' were detected" => "I file devono avere in totale una dimensione massima di '%max%' ma è stata rilevata una dimensione di '%size%'",
    "All files in sum should have a minimum size of '%min%' but '%size%' were detected" => "I file devono avere in totale una dimensione minima di '%min%' ma è stata rilevata una dimensione di '%size%'",
    "One or more files can not be read" => "Uno o più file non possono essere letti",

    // Laminas\Validator\File\Hash
    "File does not match the given hashes" => "Il file non corrisponde agli hash dati",
    "A hash could not be evaluated for the given file" => "Non può essere calcolato un hash per il file dato",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\ImageSize
    "Maximum allowed width for image should be '%maxwidth%' but '%width%' detected" => "La larghezza massima consentita per l'immagine è '%maxwidth%' ma è stata rilevata una larghezza di '%width%'",
    "Minimum expected width for image should be '%minwidth%' but '%width%' detected" => "La larghezza minima consentita per l'immagine è '%minwidth%' ma è stata rilevata una larghezza di '%width%'",
    "Maximum allowed height for image should be '%maxheight%' but '%height%' detected" => "L'altezza massima consentita per l'immagine è '%maxheight%' ma è stata rilevata un'altezza di '%height%'",
    "Minimum expected height for image should be '%minheight%' but '%height%' detected" => "L'altezza minima consentita per l'immagine è '%minheight%' ma è stata rilevata un'altezza di '%height%'",
    "The size of image could not be detected" => "Le dimensioni dell'immagine non possono essere rilevate",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\IsCompressed
    "File is not compressed, '%type%' detected" => "Il file non è un file compresso, ma un file di tipo '%type%'",
    "The mimetype could not be detected from the file" => "Il mimetype del file non può essere rilevato",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\IsImage
    "File is no image, '%type%' detected" => "Il file non è un'immagine, ma un file di tipo '%type%'",
    "The mimetype could not be detected from the file" => "Il mimetype del file non può essere rilevato",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\Md5
    "File does not match the given md5 hashes" => "Il file non corrisponde agli hash md5 dati",
    "An md5 hash could not be evaluated for the given file" => "Non può essere calcolato un hash md5 per il file dato",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\MimeType
    "File has an incorrect mimetype of '%type%'" => "Il file ha un mimetype non valido: '%type%'",
    "The mimetype could not be detected from the file" => "Il mimetype del file non può essere rilevato",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\NotExists
    "File exists" => "Il file esiste già",

    // Laminas\Validator\File\Sha1
    "File does not match the given sha1 hashes" => "Il file non corrisponde agli hash sha1 dati",
    "A sha1 hash could not be evaluated for the given file" => "Non può essere calcolato un file sha1 per il file dato",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\Size
    "Maximum allowed size for file is '%max%' but '%size%' detected" => "La dimensione massima consentita per il file è '%max%' ma è stata rilevata una dimensione di '%size%'",
    "Minimum expected size for file is '%min%' but '%size%' detected" => "La dimensione minima consentita per il file è '%min%' ma è stata rilevata una dimensione di '%size%'",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\File\Upload
    "File '%value%' exceeds the defined ini size" => "Il file '%value%' eccede la dimensione definita nell'ini",
    "File '%value%' exceeds the defined form size" => "Il file '%value%' eccede la dimensione definita nella form",
    "File '%value%' was only partially uploaded" => "Il file '%value%' è stato caricato solo parzialmente",
    "File '%value%' was not uploaded" => "Il file '%value%' non è stato caricato",
    "No temporary directory was found for file '%value%'" => "Non è stata trovata una directory temporanea per il file '%value%'",
    "File '%value%' can't be written" => "Il file '%value%' non può essere scritto",
    "A PHP extension returned an error while uploading the file '%value%'" => "Un'estensione di PHP ha generato un errore durante il caricamento del file '%value%'",
    "File '%value%' was illegally uploaded. This could be a possible attack" => "Il file '%value%' è stato caricato irregolarmente. Potrebbe trattarsi di un attacco",
    "File '%value%' was not found" => "Il file '%value%' non è stato trovato",
    "Unknown error while uploading file '%value%'" => "Errore sconosciuto durante il caricamento del file '%value%'",

    // Laminas\Validator\File\UploadFile
    "File exceeds the defined ini size" => "Il file eccede la dimensione definita nell'ini",
    "File exceeds the defined form size" => "Il file eccede la dimensione definita nella form",
    "File was only partially uploaded" => "Il file è stato caricato solo parzialmente",
    "File was not uploaded" => "Il file non è stato caricato",
    "No temporary directory was found for file" => "Non è stata trovata una directory temporanea per il file",
    "File can't be written" => "Il file non può essere scritto",
    "A PHP extension returned an error while uploading the file" => "Un'estensione di PHP ha generato un errore durante il caricamento del file",
    "File was illegally uploaded. This could be a possible attack" => "Il file è stato caricato irregolarmente. Potrebbe trattarsi di un attacco",
    "File was not found" => "Il file non è stato trovato",
    "Unknown error while uploading file" => "Errore sconosciuto durante il caricamento del file",

    // Laminas\Validator\File\WordCount
    "Too many words, maximum '%max%' are allowed but '%count%' were counted" => "Il file contiene troppe parole, ne sono consentite massimo '%max%' ma ne sono state contate '%count%'",
    "Too few words, minimum '%min%' are expected but '%count%' were counted" => "Il file contiene troppe poche parole, ne sono consentite minimo '%min%' ma ne sono state contate '%count%'",
    "File is not readable or does not exist" => "Il file non è leggibile o non esiste",

    // Laminas\Validator\GreaterThan
    "The input is not greater than '%min%'" => "L'input non è maggiore di '%min%'",
    "The input is not greater than or equal to '%min%'" => "L'input non è maggiore o uguale a '%min%'",

    // Laminas\Validator\Hex
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input contains non-hexadecimal characters" => "L'input contiene caratteri non esadecimali",

    // Laminas\Validator\Hostname
    "The input appears to be a DNS hostname but the given punycode notation cannot be decoded" => "L'input sembra essere un hostname DNS ma la notazione punycode data non può essere decodificata",
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input appears to be a DNS hostname but contains a dash in an invalid position" => "L'input sembra essere un hostname DNS ma contiene un trattino in una posizione non valida",
    "The input does not match the expected structure for a DNS hostname" => "L'input non rispetta la struttura attesa per un hostname DNS",
    "The input appears to be a DNS hostname but cannot match against hostname schema for TLD '%tld%'" => "L'input sembra essere un hostname DNS ma non rispetta lo schema per il TLD '%tld%'",
    "The input does not appear to be a valid local network name" => "L'input non sembra essere un nome valido per una rete locale",
    "The input does not appear to be a valid URI hostname" => "L'input non sembra essere un hostname URI valido",
    "The input appears to be an IP address, but IP addresses are not allowed" => "L'input sembra essere un indirizzo IP, ma gli indirizzi IP non sono consentiti",
    "The input appears to be a local network name but local network names are not allowed" => "L'input sembra essere un nome di una rete locale ma i nomi di reti locale non sono consentiti",
    "The input appears to be a DNS hostname but cannot extract TLD part" => "L'input sembra essere un hostname DNS ma non è possibile estrarne il TLD",
    "The input appears to be a DNS hostname but cannot match TLD against known list" => "L'input sembra essere un hostname DNS ma il suo TLD è sconosciuto",

    // Laminas\Validator\Iban
    "Unknown country within the IBAN" => "Codice paese sconosciuto nell'IBAN fornito",
    "Countries outside the Single Euro Payments Area (SEPA) are not supported" => "I paesi fuori dall'Area Unica dei Pagamenti in Euro (SEPA) non sono supportati",
    "The input has a false IBAN format" => "L'input ha un formato IBAN non valido",
    "The input has failed the IBAN check" => "L'input ha fallito il controllo IBAN",

    // Laminas\Validator\Identical
    "The two given tokens do not match" => "I due token dati non corrispondono",
    "No token was provided to match against" => "Non è stato dato nessun token per il confronto",

    // Laminas\Validator\InArray
    "The input was not found in the haystack" => "L'input non è stato trovato nell'array",

    // Laminas\Validator\Ip
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input does not appear to be a valid IP address" => "L'input non sembra essere un indirizzo IP valido",

    // Laminas\Validator\IsInstanceOf
    "The input is not an instance of '%className%'" => "L'input non è un'istanza di '%className%'",

    // Laminas\Validator\Isbn
    "Invalid type given. String or integer expected" => "Tipo di dato non valido. Era atteso un dato di tipo string o integer",
    "The input is not a valid ISBN number" => "L'input non è un numero ISBN valido",

    // Laminas\Validator\LessThan
    "The input is not less than '%max%'" => "L'input non è minore di '%max%'",
    "The input is not less or equal than '%max%'" => "L'input non è minore o uguale a '%max%'",

    // Laminas\Validator\NotEmpty
    "Value is required and can't be empty" => "Il dato è richiesto e non può essere vuoto",
    "Invalid type given. String, integer, float, boolean or array expected" => "Tipo di dato non valido. Era atteso un dato di tipo string, integer, float, boolean o array",

    // Laminas\Validator\Regex
    "Invalid type given. String, integer or float expected" => "Tipo di dato non valido. Era atteso un dato di tipo string, float o integer",
    "The input does not match against pattern '%pattern%'" => "L'input non corrisponde al pattern '%pattern%'",
    "There was an internal error while using the pattern '%pattern%'" => "Si è verificato un errore interno usando il pattern '%pattern%'",

    // Laminas\Validator\Sitemap\Changefreq
    "The input is not a valid sitemap changefreq" => "L'input non è una sitemap changefreq valida",
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",

    // Laminas\Validator\Sitemap\Lastmod
    "The input is not a valid sitemap lastmod" => "L'input non è una sitemap lastmod valida",
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",

    // Laminas\Validator\Sitemap\Loc
    "The input is not a valid sitemap location" => "L'input non è una sitemap location valida",
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",

    // Laminas\Validator\Sitemap\Priority
    "The input is not a valid sitemap priority" => "L'input non è una sitemap priority valida",
    "Invalid type given. Numeric string, integer or float expected" => "Tipo di dato non valido. Era atteso un dato di tipo stringa numerica, integer o float",

    // Laminas\Validator\Step
    "Invalid value given. Scalar expected" => "Tipo di dato non valido. Era atteso un numero scalare",
    "The input is not a valid step" => "L'input non è uno step valido",

    // Laminas\Validator\StringLength
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input is less than %min% characters long" => "L'input è meno lungo di %min% caratteri",
    "The input is more than %max% characters long" => "L'input è più lungo di %max% caratteri",

    // Laminas\Validator\Uri
    "Invalid type given. String expected" => "Tipo di dato non valido. Era atteso un dato di tipo string",
    "The input does not appear to be a valid Uri" => "L'input non sembra essere un indirizzo URI valido",
];
