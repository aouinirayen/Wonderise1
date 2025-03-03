document.addEventListener("DOMContentLoaded", function () {
    const translateBtn = document.getElementById("translate-btn");
    const descriptionField = document.getElementById("description-text");

    translateBtn.addEventListener("click", function () {
        const textToTranslate = descriptionField.value;

        if (textToTranslate.trim() === "") {
            alert("Veuillez entrer un texte à traduire.");
            return;
        }

        fetch("/reclamation/translate", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ text: textToTranslate }),
        })
        .then(response => response.json())
        .then(data => {
            descriptionField.value = data.translatedText; // Remplace le texte traduit
        })
        .catch(error => console.error("Erreur de traduction :", error));
    });
});
