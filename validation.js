document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("inscriptionForm");
  if (!form) return;

  form.addEventListener("submit", function (event) {
    const requiredFields = ["nom", "prenom", "email", "tel", "id_formation"];
    let allFilled = true;

    requiredFields.forEach((fieldId) => {
      const field = document.getElementById(fieldId);
      if (!field || field.value.trim() === "") {
        allFilled = false;
      }
    });

    const emailField = document.getElementById("email");
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailIsValid = emailField && emailPattern.test(emailField.value.trim());

    if (!allFilled) {
      event.preventDefault();
      alert("Veuillez remplir tous les champs obligatoires.");
      return;
    }

    if (!emailIsValid) {
      event.preventDefault();
      alert("Veuillez saisir une adresse email valide.");
    }
  });
});
