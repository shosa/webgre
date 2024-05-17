window.addEventListener('beforeunload', function () {
    document.body.classList.add('fade-out');
});
function scrollToServices() {
    var targetDiv = document.getElementById('services');
    targetDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }