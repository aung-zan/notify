const copyButtons = document.querySelectorAll('.copy');

copyButtons.forEach(copyButton => {
  const target = copyButton.parentElement.previousElementSibling.childNodes[1];
  const icon = copyButton.childNodes[1];

  new ClipboardJS(copyButton, {
    target: target,
    text: function () {
      return target.innerHTML;
    }
  }).on('success', function () {
    icon.classList.remove('bi-copy', 'fs-3');
    icon.classList.add('bi-check2', 'fs-1', 'text-success');

    setTimeout(() => {
      icon.classList.remove('bi-check2', 'fs-1', 'text-success');
      icon.classList.add('bi-copy', 'fs-3');
    }, 1000);
  });
});