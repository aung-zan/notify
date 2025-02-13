/**
 * This function is used to show the toast.
 * @param {string} id
 */
function showToast(id) {
  const toastElement = document.getElementById(id);

  if (toastElement) {
    const toast = bootstrap.Toast.getOrCreateInstance(toastElement);
    toast.show();
  }
}

/**
 * This function is used to show the validation error messages
 * with javascript.
 * @param {object} errors
 */
function showErrorMessages(errors) {
  const errorMessages = errors.errors;

  for (const key in errorMessages) {
    if (Object.prototype.hasOwnProperty.call(errorMessages, key)) {
      const message = errorMessages[key][0];

      // change the color of some UI.
      let div = document.querySelector(`div[name="${key}"]`);
      div?.classList.add('is-invalid');

      let input = document.querySelector(`label[name="${key}"].active`) ||
                  document.querySelector(`input[name="${key}"], textarea[name="${key}"]`);

      input?.classList.add(input.tagName === 'LABEL' ? 'btn-outline-danger' : 'is-invalid');
      // change the color of some UI.

      // show message
      let messageDiv = document.querySelector(`div[name="${key}"].invalid-feedback`);
      messageDiv.innerHTML = message;
      // show message
    }
  }
}

/**
 * for logout.
 */
const logoutButton = document.getElementById('logout_button');
const logoutForm = document.getElementById('logout');

logoutButton.addEventListener('click', function (event) {
  event.preventDefault();

  logoutForm.submit();
});