const form = document.getElementById("ideenForm");
const submitButton = document.getElementById("submitButton");
const statusMessage = document.getElementById("statusMessage");

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    submitButton.disabled = true;
    submitButton.textContent = "Wird gesendet...";

    statusMessage.textContent = "";
    statusMessage.className = "";

    const projekt = document.getElementById("projekt").value;
    const idee = document.getElementById("idee").value.trim();
    const stichpunkte = document.getElementById("stichpunkte").value.trim();
    const beschreibung = document.getElementById("beschreibung").value.trim();

    const discordMessage = {
        username: "KB Mod Studios",
        embeds: [
            {
                title: "💡 Neue Idee",
                color: 5621642,
                fields: [
                    {
                        name: "📦 Projekt",
                        value: projekt,
                        inline: false
                    },
                    {
                        name: "💡 Idee",
                        value: idee,
                        inline: false
                    },
                    {
                        name: "📌 Stichpunkte",
                        value: stichpunkte,
                        inline: false
                    },
                    {
                        name: "📝 Beschreibung",
                        value: beschreibung,
                        inline: false
                    }
                ],
                footer: {
                    text: "KB Mod Studios • Ideen-System"
                },
                timestamp: new Date().toISOString()
            }
        ]
    };

    try {
        const response = await fetch(DISCORD_WEBHOOK, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(discordMessage)
        });

        if (!response.ok) {
            throw new Error("Discord hat die Anfrage abgelehnt.");
        }

        statusMessage.textContent =
            "✅ Deine Idee wurde erfolgreich gesendet!";
        statusMessage.className = "success";

        form.reset();

    } catch (error) {
        console.error(error);

        statusMessage.textContent =
            "❌ Die Idee konnte nicht gesendet werden.";
        statusMessage.className = "error";
    }

    submitButton.disabled = false;
    submitButton.textContent = "Idee absenden";
});
