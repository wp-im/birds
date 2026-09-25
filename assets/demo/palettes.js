(function () {
  'use strict';

  var select = document.querySelector('#birds-palette-select');
  var body = document.body;

  if (!select || !body) {
    return;
  }

  var allowed = ['classic', 'sunlit-yellow', 'mist-green', 'mist-blue', 'mist-red', 'mist-gray'];
  var stored = window.localStorage.getItem('birdsPalette');
  var initial = allowed.indexOf(stored) !== -1 ? stored : 'classic';

  select.value = initial;
  body.dataset.palette = initial;

  select.addEventListener('change', function () {
    var palette = allowed.indexOf(select.value) !== -1 ? select.value : 'classic';
    body.dataset.palette = palette;
    window.localStorage.setItem('birdsPalette', palette);
  });
}());
