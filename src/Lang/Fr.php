<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base\Lang;
use Quid\Base;

// fr
// french language content used by this namespace
class Fr extends Base\Config
{
    // config
    protected static array $config = [
        // number
        'number'=>[

            // format
            'format'=>[
                'decimal'=>2,
                'separator'=>'.',
                'thousand'=>' '
            ],

            // moneyFormat
            'moneyFormat'=>[
                'decimal'=>2,
                'separator'=>'.',
                'thousand'=>' ',
                'output'=>'%v% $'
            ],

            // percentFormat
            'percentFormat'=>[
                'decimal'=>0,
                'separator'=>'.',
                'thousand'=>'',
                'output'=>'%v%%'
            ],

            // phoneFormat
            'phoneFormat'=>[
                'parenthesis'=>false,
                'areaDash'=>false,
                'extension'=>'#'
            ],

            // sizeFormat
            'sizeFormat'=>[
                'round'=>[
                    0=>0,
                    1=>0,
                    2=>1,
                    3=>2,
                    4=>2,
                    5=>3],
                'text'=>[
                    0=>'Octet',
                    1=>'Ko',
                    2=>'Mo',
                    3=>'Go',
                    4=>'To',
                    5=>'Po']
                ]
        ],

        // date
        'date'=>[

            // locale
            'locale'=>'fr-CA',

            // format
            'format'=>[
                0=>'j %n% Y',
                1=>'j %n% Y H:i:s',
                2=>'@n@ Y',
                3=>'d-m-Y',
                4=>'d-m-Y H:i:s',
                'dateToDay'=>'d-m-Y',
                'dateToMinute'=>'d-m-Y H:i',
                'dateToSecond'=>'d-m-Y H:i:s',
                'short'=>'j %n% Y',
                'long'=>'j %n% Y H:i:s',
                'calendar'=>'@n@ Y'
            ],

            // placeholder
            'placeholder'=>[
                'dateToDay'=>'JJ-MM-AAAA',
                'dateToMinute'=>'JJ-MM-AAAA HH:MM',
                'dateToSecond'=>'JJ-MM-AAAA HH:MM:SS',
                'sql'=>'YYYY-MM-DD HH:MM:SS'
            ],

            // str
            'str'=>[
                'year'=>'année',
                'month'=>'mois',
                'day'=>'jour',
                'hour'=>'heure',
                'minute'=>'minute',
                'second'=>'seconde',
                'and'=>'et'
            ],

            // month
            'month'=>[
                1=>'Janvier',
                2=>'Février',
                3=>'Mars',
                4=>'Avril',
                5=>'Mai',
                6=>'Juin',
                7=>'Juillet',
                8=>'Août',
                9=>'Septembre',
                10=>'Octobre',
                11=>'Novembre',
                12=>'Décembre'
            ],

            // dayShort
            'dayShort'=>[
                0=>'D',
                1=>'L',
                2=>'M',
                3=>'M',
                4=>'J',
                5=>'V',
                6=>'S'
            ],

            // day
            'day'=>[
                0=>'Dimanche',
                1=>'Lundi',
                2=>'Mardi',
                3=>'Mercredi',
                4=>'Jeudi',
                5=>'Vendredi',
                6=>'Samedi'
            ]
        ],

        // header
        'header'=>[

            // responseStatus
            'responseStatus'=>[
                100=>'Continuer',
                101=>'Changer de protocole',
                200=>'OK',
                201=>'Créé',
                202=>'Accepté',
                203=>'Information ne faisant pas autorité',
                204=>'Pas de contenu',
                205=>'Réinitialiser le contenu',
                206=>'Contenu partiel',
                207=>'Multi-statut',
                208=>'Déjà rapporté',
                226=>'IM utilisé',
                300=>'Multiples choix',
                301=>'Déplacé en permanence',
                302=>'Trouvé',
                303=>'Voir autre',
                304=>'Non modifié',
                305=>'Utiliser un proxy',
                306=>'Changer de proxy',
                307=>'Redirection temporaire',
                308=>'Redirection permanente',
                400=>'Mauvaise requête',
                401=>'Non autorisé',
                402=>'Paiement requis',
                403=>'Interdit',
                404=>'Pas trouvé',
                405=>'Méthode non autorisée',
                406=>'Pas acceptable',
                407=>'Authentification proxy requise',
                408=>'Délai dépassé pour la requête',
                409=>'Conflit',
                410=>'Disparu',
                411=>'Longueur requise',
                412=>'Échec de la condition préalable',
                413=>'Charge trop grande',
                414=>'URI trop longue',
                415=>'Type de média non supporté',
                416=>'La plage demandée ne peut être satisfaite',
                417=>'Les attentes ne peuvent être satisfaite',
                418=>'Je suis une théière',
                421=>'Requête mal dirigée',
                422=>'Entité non traitable',
                423=>'Barré',
                424=>'Dépendance échouée',
                425=>'Trop tôt',
                426=>'Mise à niveau requise',
                428=>'Condition préalable requise',
                429=>'Trop de requêtes',
                431=>"Champs d'en-têtes trop grands",
                451=>'Indisponible pour des raisons légales',
                500=>'Erreur interne du serveur',
                501=>'Pas implémenté',
                502=>'Mauvaise passerelle',
                503=>'Service indisponible',
                504=>'Délai dépassé pour la passerelle',
                505=>'Version HTTP non supportée',
                506=>'La variante négocie également',
                507=>'Espace insuffisant',
                508=>'Boucle détectée',
                510=>'Non étendu',
                511=>'Authentification réseau requise'
            ]
        ],

        // error
        'error'=>[
            'code'=>[
                E_ERROR=>'E_ERROR',
                E_WARNING=>'E_WARNING',
                E_PARSE=>'E_PARSE',
                E_NOTICE=>'E_NOTICE',
                E_CORE_ERROR=>'E_CORE_ERROR',
                E_CORE_WARNING=>'E_CORE_WARNING',
                E_COMPILE_ERROR=>'E_COMPILE_ERROR',
                E_COMPILE_WARNING=>'E_COMPILE_WARNING',
                E_USER_ERROR=>'E_USER_ERROR',
                E_USER_WARNING=>'E_USER_WARNING',
                E_USER_NOTICE=>'E_USER_NOTICE',
                E_STRICT=>'E_STRICT',
                E_RECOVERABLE_ERROR=>'E_RECOVERABLE_ERROR',
                E_DEPRECATED=>'E_DEPRECATED',
                E_USER_DEPRECATED=>'E_USER_DEPRECATED',
                E_ALL=>'E_ALL'
            ]
        ],

        // validate
        'validate'=>[
            'array'=>'Doit être un tableau',
            'bool'=>'Doit être un booléen',
            'callable'=>'Doit être appelable',
            'float'=>'Doit être un nombre flottant',
            'int'=>'Doit être un chiffre entier',
            'numeric'=>'Doit être numérique',
            'null'=>'Doit être null',
            'object'=>'Doit être un objet',
            'resource'=>'Doit être une ressource',
            'scalar'=>'Doit être chaîne scalaire',
            'string'=>'Doit être une chaîne',
            'instance'=>'Doit être une instance de [%]',
            'closure'=>'Doit passer le test de la fonction anynonyme',
            'empty'=>'Doit être vide',
            'notEmpty'=>'Ne peut pas être vide',
            'reallyEmpty'=>'Doit être vide (0 permis)',
            'notReallyEmpty'=>'Ne peut pas être vide (0 permis)',
            'arrKey'=>'Doit être une clé de tableau',
            'arrNotEmpty'=>'Doit être un tableau non vide',
            'dateToDay'=>'Doit être une date valide (DD-MM-YYYY)',
            'dateToMinute'=>'Doit être une date avec temps valide (DD-MM-YYYY HH:MM)',
            'dateToSecond'=>'Doit être une date avec temps valide (DD-MM-YYYY HH:MM:SS)',
            'numberNotEmpty'=>'Doit être un chiffre non vide',
            'numberPositive'=>'Doit être un chiffre positif',
            'numberNegative'=>'Doit être un chiffre négatif',
            'numberOdd'=>'Doit être un chiffre impair',
            'numberEven'=>'Doit être un chiffre pair',
            'intCast'=>'Doit être un chiffre entier',
            'intCastNotEmpty'=>'Doit être un chiffre entier non vide',
            'floatCast'=>'Doit être un chiffre avec décimal',
            'scalarNotBool'=>'Doit être chaîne scalaire non booléenne',
            'slug'=>"Doit être un slug d'uri",
            'slugPath'=>"Doit être un slug-chemin d'uri",
            'fragment'=>"Doit être un fragment d'uri",
            'strLatin'=>'Doit être une chaîne avec seulement des caractères latin',
            'strNotEmpty'=>'Doit être une chaîne non vide',
            'uriRelative'=>'Doit être une uri relative (/xyz)',
            'uriAbsolute'=>'Doit être une uri absolue (http://xyz.com/xyz)',
            'length'=>'Doit avoir une longueur de [%] caractère%s%',
            'minLength'=>'Doit avoir une longueur minimale de [%] caractère%s%',
            'maxLength'=>'Doit avoir une longueur maximale de [%] caractère%s%',
            'arrCount'=>'Doit être un tableau qui contient [%]',
            'arrMinCount'=>'Doit être un tableau qui contient au minimum [%]',
            'arrMaxCount'=>'Doit être un tableau qui contient au maximum [%]',
            'dateFormat'=>'Doit respecté le format de date [%]',
            'fileCount'=>'Doit contenir [%] fichier%s%',
            'fileMinCount'=>'Doit contenir au minimum [%] fichier%s%',
            'fileMaxCount'=>'Doit contenir au maximum [%] fichier%s%',
            'numberLength'=>'Doit être un nombre avec [%] caractère%s%',
            'numberMinLength'=>'Doit avoir une longueur minimale de [%] caractère%s%',
            'numberMaxLength'=>'Doit avoir une longueur maximale de [%] caractère%s%',
            'jsonCount'=>'Doit contenir [%]',
            'jsonMinCount'=>'Doit contenir au minimum [%]',
            'jsonMaxCount'=>'Doit contenir au maximum [%]',
            'setCount'=>'Doit contenir [%]',
            'setMinCount'=>'Doit contenir au minimum [%]',
            'setMaxCount'=>'Doit contenir au maximum [%]',
            'strLength'=>'Doit être une chaîne avec [%] caractère%s%',
            'strMinLength'=>'Doit avoir une longueur minimale de [%] caractère%s%',
            'strMaxLength'=>'Doit avoir une longueur maximale de [%] caractère%s%',
            'uriHost'=>'Doit avoir le domaine [%]',
            'alpha'=>'Doit être une chaîne alpha (A-z)',
            'alphanumeric'=>'Doit être une chaîne alpha numérique (A-z 0-9)',
            'alphanumericSlug'=> 'Doit être une chaîne alpha numérique (A-z 0-9 _-)',
            'alphanumericPlus'=> 'Doit être une chaîne alpha numérique (a-z 0-9 _-.@)',
            'alphanumericPlusSpace'=> 'Doit être une chaîne alpha numérique, espace accepté (a-z 0-9 _-.@)',
            'username'=>"Doit être un nom d'utilisateur valide d'au moins 4 caractères (a-z 0-9 _-)",
            'usernameLoose'=>"Doit être un nom d'utilisateur valide d'au moins 4 caractères (a-z 0-9 _-.@)",
            'password'=>"Doit être un mot de passe contenant une lettre, un chiffre et ayant une longueur d'au moins 5 caractères.",
            'passwordLoose'=>"Doit être un mot de passe ayant une longueur d'au moins 4 caractères.",
            'passwordHash'=>"Doit être un mot de passe contenant une lettre, un chiffre et ayant une longueur d'au moins 5 caractères.",
            'passwordHashLoose'=>"Doit être un mot de passe ayant une longueur d'au moins 4 caractères.",
            'email'=>'Doit être un courriel valide (x@x.com)',
            'hex'=>'Doit être un code HEX valide (ffffff)',
            'tag'=>'Doit être une balise HTML valide (&lt;tag&gt;&lt;/tag&gt;)',
            'year'=>'Doit être une année valide (YYYY)',
            'americanZipcode'=>'Doit être un zip code américain valide (11111)',
            'canadianPostalcode'=>'Doit être un code postal canadien valide (X1X1X1)',
            'northAmericanPhone'=>'Doit être un numéro de téléphone nord-américain valide (111-111-1111)',
            'phone'=>'Doit être un numéro de téléphone valide ',
            'ip'=>'Doit être un IP valide (1.2.3.4)',
            'date'=>'Doit être une date valide (YYYY-MM-DD)',
            'datetime'=>'Doit être une date-temps valide (YYYY-MM-DD HH:MM:SS)',
            'time'=>'Doit être un temps valide (HH:MM:SS)',
            'uriPath'=>"Doit être un chemin d'URI valide",
            'uri'=>'Doit être une uri relative ou absolue',
            'fqcn'=>'Doit être un FQCN valide (\)',
            'table'=>'Doit être un nom de table valide (A-z 0-9 _)',
            'col'=>'Doit être un nom de colonne valide (A-z 0-9 _)',
            '='=>'Doit être égal à [%]',
            '=='=>'Doit être égal à [%]',
            '==='=>'Doit être égal à [%]',
            '>'=>'Doit être plus grand que [%]',
            '>='=>'Doit être plus grand ou égal à [%]',
            '<'=>'Doit être plus petit que [%]',
            '<='=>'Doit être plus petit ou égal à [%]',
            '!'=>'Ne doit pas être égal à [%]',
            '!='=>'Ne doit pas être égal à [%]',
            '!=='=>'Ne doit pas être égal à [%]',
            'fileUpload'=>'Doit être un fichier téléversé',
            'fileUploads'=>'Doit être un ou plusieurs fichiers téléversés',
            'fileUploadInvalid'=>'Le tableau de chargement du fichier est invalide.',
            'fileUploadSizeIni'=>'La taille du fichier téléversé est trop volumineuse. Voir PHP Ini.',
            'fileUploadSizeForm'=>'La taille du fichier téléversé est trop volumineuse. Voir le formulaire.',
            'fileUploadPartial'=>'Le téléversement du fichier est partiel. Réesayer.',
            'fileUploadSizeEmpty'=>'Le fichier téléversé est vide.',
            'fileUploadTmpDir'=>'Erreur serveur: aucun dossier temporaire.',
            'fileUploadWrite'=>"Impossible d'écrire le fichier téléversé sur le serveur.",
            'fileUploadExists'=>"Le fichier téléversé n'existe pas sur le serveur.",
            'maxFilesize'=>'La taille du fichier doit être plus petite que [%]',
            'maxFilesizes'=>'La taille du ou des fichiers doit être plus petite que [%]',
            'extension'=>"L'extension du fichier doit être: [%]",
            'extensions'=>"L'extension du ou des fichiers doit être: [%]"
        ],

        // required
        'required'=>[
            'common'=>'Ne peut pas être vide'
        ],

        // unique
        'unique'=>[
            'common'=>'Doit être unique[%]'
        ],

        // editable
        'editable'=>[
            'common'=>'Ne peut pas être modifié'
        ],

        // compare
        'compare'=>[
            '='=>'Doit être égal à [%]',
            '=='=>'Doit être égal à [%]',
            '==='=>'Doit être égal à [%]',
            '>'=>'Doit être plus grand que [%]',
            '>='=>'Doit être plus grand ou égal à [%]',
            '<'=>'Doit être plus petit que [%]',
            '<='=>'Doit être plus petit ou égal à [%]',
            '!'=>'Ne doit pas être égal à [%]',
            '!='=>'Ne doit pas être égal à [%]',
            '!=='=>'Ne doit pas être égal à [%]'
        ]
    ];
}
?>