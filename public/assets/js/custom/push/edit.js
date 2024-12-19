let editCredentials = document.getElementById('edit-credentials');
let credentials = document.getElementById('credentials');
let cancel = document.getElementById('cancel');

editCredentials.addEventListener("click", function () {
  editCredentials.hidden = true;

  cancel.hidden = false;
  credentials.hidden = false;
  credentials.disabled = false;
});

cancel.addEventListener("click", function () {
  editCredentials.hidden = false;

  cancel.hidden = true;
  credentials.hidden = true;
  credentials.disabled = true;
});