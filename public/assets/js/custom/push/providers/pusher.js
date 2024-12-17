pusherConfig = JSON.parse(pusherConfig);
const sendButton = document.getElementById('send-button');

// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

var pusher = new Pusher(pusherConfig.key, {
  cluster: pusherConfig.cluster
});

var channel = pusher.subscribe('pushNotificationTest');
channel.bind('pushNotificationTest', function(data) {
  console.log(data.message);
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
