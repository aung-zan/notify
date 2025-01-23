let chips = document.querySelectorAll('.chip');
let isActive = false;

function addRemoveService(chip) {
  let serviceId = chip.getAttribute('data-type');
  let service = document.getElementById(serviceId);

  isActive = chip.classList.contains('active');

  isActive ? service.removeAttribute('disabled') : service.setAttribute('disabled', true);
}

function showHideChannels(chip) {
  let dataType = chip.getAttribute('data-type') + '-channels';

  let channel = document.getElementById(dataType);
  let channelSelect = channel.querySelector('select');

  if (isActive) {
    channel.removeAttribute('hidden');
    channelSelect.removeAttribute('disabled');
  } else {
    channel.setAttribute('hidden', true);
    channelSelect.setAttribute('disabled', true);
  }
}

chips.forEach(chip => {
  chip.addEventListener('click', () => {
    chip.classList.toggle('active');

    addRemoveService(chip);
    showHideChannels(chip);
  });
});