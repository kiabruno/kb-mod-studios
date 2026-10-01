<?php

// ==================================================
// DISCORD WEBHOOK
// ==================================================

$webhook_url = "https://discord.com/api/webhooks/1555249207449751665/Biwh8zMnJZGuP0IZXoT4InnCzud_XEaoidw4LT-lBtqedfXx9nVhvTZotS_1gsraII3F";

// ==================================================
// NUR POST-ANFRAGEN ERLAUBEN
// ==================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Ungültige Anfrage.");
}

// ==================================================
// FORMULAR-DATEN
// ==================================================

$projekt = trim($_POST["projekt"] ?? "");
$idee = trim($_POST["idee"] ?? "");
$stichpunkte = trim($_POST["stichpunkte"] ?? "");
$beschreibung = trim($_POST["beschreibung"] ?? "");

// ==================================================
// EINGABEN PRÜFEN
// ==================================================

$erlaubte_projekte = [
    "KB Pack",
    "KB Item Pack"
];

if (!in_array($projekt, $erlaubte_projekte, true)) {
    exit("Ungültiges Projekt.");
}

if ($idee === "" || $stichpunkte === "" || $beschreibung === "") {
    exit("Bitte fülle alle Felder aus.");
}

// ==================================================
// LÄNGEN BEGRENZEN
// ==================================================

if (mb_strlen($idee) > 100) {
    exit("Die Idee ist zu lang.");
}

if (mb_strlen($stichpunkte) > 1000) {
    exit("Die Stichpunkte sind zu lang.");
}

if (mb_strlen($beschreibung) > 3000) {
    exit("Die Beschreibung ist zu lang.");
}

// ==================================================
// DISCORD NACHRICHT
// ==================================================

$discord_data = [

    "username" => "KB Mod Studios",

    "embeds" => [

        [

            "title" => "💡 Neue Idee",

            "color" => 5621642,

            "fields" => [

                [
                    "name" => "📦 Projekt",
                    "value" => $projekt,
                    "inline" => false
                ],

                [
                    "name" => "💡 Idee",
                    "value" => $idee,
                    "inline" => false
                ],

                [
                    "name" => "📌 Stichpunkte",
                    "value" => $stichpunkte,
                    "inline" => false
                ],

                [
                    "name" => "📝 Beschreibung",
                    "value" => $beschreibung,
                    "inline" => false
                ]

            ],

            "footer" => [
                "text" => "KB Mod Studios • Ideen-System"
            ],

            "timestamp" => date("c")

        ]

    ]

];

// ==================================================
// AN DISCORD SENDEN
// ==================================================

$json = json_encode(
    $discord_data,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

$ch = curl_init($webhook_url);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    [
        "Content-Type: application/json"
    ]
);

curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    $json
);

curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);

$response = curl_exec($ch);

$http_code = curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);

curl_close($ch);

// ==================================================
// ERFOLG
// ==================================================

if ($http_code >= 200 && $http_code < 300) {

    echo '
    <!DOCTYPE html>
    <html lang="de">

    <head>

        <meta charset="UTF-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >

        <title>KB Mod Studios | Idee gesendet</title>

        <link
            rel="icon"
            type="image/png"
            href="logo.png"
        >

        <style>

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;

                min-height: 100vh;

                display: flex;
                align-items: center;
                justify-content: center;

                padding: 25px;

                background: #0b0d10;
                color: #ffffff;
            }

            .box {
                width: 100%;
                max-width: 600px;

                padding: 45px;

                text-align: center;

                border-radius: 18px;

                background:
                    linear-gradient(
                        145deg,
                        #15191d,
                        #0e1114
                    );

                border: 1px solid #292e34;
            }

            .icon {
                font-size: 55px;
                margin-bottom: 20px;
            }

            h1 {
                font-size: 35px;
                margin-bottom: 15px;
            }

            h1 span {
                color: #55d68a;
            }

            p {
                color: #9ca3aa;
                line-height: 1.7;
                margin-bottom: 30px;
            }

            a {
                display: inline-block;

                padding: 13px 23px;

                border-radius: 9px;

                background: #55d68a;
                color: #08100b;

                text-decoration: none;
                font-weight: 700;

                transition: 0.2s ease;
            }

            a:hover {
                transform: translateY(-2px);
                filter: brightness(1.08);
            }

        </style>

    </head>

    <body>

        <div class="box">

            <div class="icon">
                💡
            </div>

            <h1>
                Idee <span>gesendet!</span>
            </h1>

            <p>
                Vielen Dank für deine Idee!
                Sie wurde erfolgreich an KB Mod Studios
                gesendet.
            </p>

            <a href="ideen.html">
                Weitere Idee senden
            </a>

        </div>

    </body>

    </html>
    ';

    exit;
}

// ==================================================
// FEHLER
// ==================================================

echo "Beim Senden der Idee ist ein Fehler aufgetreten.";

?>
