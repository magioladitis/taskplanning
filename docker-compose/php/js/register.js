document
  .querySelector(".register-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    let formIsValid = true;

    document.getElementById("error-password").textContent = "";
    document.getElementById("error-email").textContent = "";

    if (document.getElementById("password").value.trim() === "") {
      document.getElementById("error-password").textContent =
        "Παρακαλώ εισάγετε έναν κωδικό";
      formIsValid = false;
    }

    if (document.getElementById("username").value.trim() === "") {
      document.getElementById("error-username").textContent =
        "Παρακαλώ εισάγετε ένα username";
      formIsValid = false;
    }

    if (document.getElementById("email").value.trim() === "") {
      document.getElementById("error-email").textContent =
        "Παρακαλώ εισάγετε ένα email";
      formIsValid = false;
    }

    if (formIsValid) {
      this.submit();
    }
  });
