let flashMessage = document.getElementById('flash-message');

if (flashMessage) {
  let toastElement = document.getElementById('notification_toast');
  let toastMessage = document.getElementById('toast-message');

  toastMessage.innerText = flashMessage.value;

  const toast = bootstrap.Toast.getOrCreateInstance(toastElement);
  toast.show();
}