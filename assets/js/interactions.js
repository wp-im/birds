/* Generic Birds interaction helpers. */
(function () {
	'use strict';

	function confirmAction(element) {
		var message = element.getAttribute('data-confirm');
		return !message || window.confirm(message);
	}

	document.addEventListener('click', function (event) {
		var trigger = event.target.closest ? event.target.closest('[data-confirm]') : null;

		if (trigger && !confirmAction(trigger)) {
			event.preventDefault();
			event.stopPropagation();
		}
	});

	document.addEventListener('submit', function (event) {
		var form = event.target;

		if (form && form.matches && form.matches('[data-confirm]') && !confirmAction(form)) {
			event.preventDefault();
			event.stopPropagation();
		}
	}, true);
}());
