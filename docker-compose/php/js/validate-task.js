document.querySelector(".edit-task-form").addEventListener("submit", function (event) {
  event.preventDefault();

  let formIsValid = true;

  document.getElementById("error-title").textContent = "";
  document.getElementById("error-description").textContent = "";

  if (document.getElementById("title").value.trim() === "") {
    document.getElementById("error-title").textContent =
      "Παρακαλώ εισάγετε έναν τίτλο";
    formIsValid = false;
  }

  if (document.getElementById("description").value.trim() === "") {
    document.getElementById("error-description").textContent =
      "Παρακαλώ εισάγετε μια περιγραφή";
    formIsValid = false;
  }

  if (formIsValid) {
    this.submit();
  }
});
  