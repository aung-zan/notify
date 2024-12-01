// let data;
// const token = document.querySelector('meta[name="csrf-token"]');
// const submitButton = document.getElementById('submit');

// function sendData() {
//   fetch(send_push_url, {
//     method: "POST",
//     body: JSON.stringify(data),
//     headers: {
//       "X-CSRF-TOKEN": token,
//       "Content-Type": "application/json",
//       "Accept": "application/json",
//     }
//   })
//   .then(async (response) => {
//     const json = await response.json();
//     if (! response.ok) throw json;
//   })
//   .catch(showErrorMessages);
// }

// submitButton.addEventListener('click', function () {
//   const form = document.getElementById('sendPush');
//   const formData = new FormData(form);

//   data = Object.fromEntries(formData.entries()); // Convert to an object

//   sendData();
// });
