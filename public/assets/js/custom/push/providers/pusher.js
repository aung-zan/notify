const pusherConfig = JSON.parse(config);
const sendButton = document.getElementById('send-button');

const toastElement = document.getElementById('notification_toast');
const toast = bootstrap.Toast.getOrCreateInstance(toastElement);
const toastMessage = document.getElementById('toast-message');

// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

let pusher = new Pusher(pusherConfig.key, {
  cluster: pusherConfig.cluster
});

let channel = pusher.subscribe('pushNotificationTest');
channel.bind('pushNotificationTest', function(data) {
  toastMessage.innerText = data.message;
  toast.show();
});

sendButton.addEventListener("click", async function () {
  const token = document.querySelector('meta[name="csrf-token"]').content;
  const message = document.getElementById('message').value;
  const channelId = document.getElementById('channel-id').value;

  const url = test_url.replace('id', channelId);

  let response = await fetch(url, {
    headers: {
      'X-CSRF-TOKEN': token,
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    method: 'POST',
    body: JSON.stringify({
      'message': message
    })
  });

  if (! response.ok) {
    const data = await response.json();
    toastMessage.innerText = data.message;
    toast.show();
  }
});
