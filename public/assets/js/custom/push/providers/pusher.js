pusherConfig = JSON.parse(pusherConfig);
const sendButton = document.getElementById('send-button');

// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

var pusher = new Pusher(pusherConfig.key, {
  cluster: pusherConfig.cluster
});

var channel = pusher.subscribe('pushNotificationTest');
channel.bind('pushNotificationTest', function(data) {
  const toastElement = document.getElementById('notification_toast');
  const toastMessage = document.getElementById('toast-message');

  toastMessage.innerText = data.message;

  const toast = bootstrap.Toast.getOrCreateInstance(toastElement);
  toast.show();
});

sendButton.addEventListener("click", async function () {
  const token = document.querySelector('meta[name="csrf-token"]').content;
  const message = document.getElementById('message').value;
  const channelId = document.getElementById('channel-id').value;

  test_url = test_url.replace('id', channelId);

  const response = await fetch(test_url, {
    headers: {
      'X-CSRF-TOKEN': token,
      'Content-Type': 'application/json',
    },
    method: 'POST',
    body: JSON.stringify({
      'message': message
    })
  });

  const data = await response.json();
});
