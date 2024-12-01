// (function () {
//   Pusher.logToConsole = true;

//   let pusher = new Pusher('9d4cf63cf16c877f6d5e', {
//     cluster: 'ap1'
//   });

//   let channel = pusher.subscribe('micro-noti');
//   channel.bind('public.event', function (data) {
//     // alert(JSON.stringify(data?.message));
//     const notiDiv = document.getElementById('noti-message');
//     notiDiv.innerHTML = JSON.stringify(data?.message);

//     // toolbar toast
//     showToast('noti-toast');
//   });

//   showToast('kt_docs_toast_toggle');
// })();