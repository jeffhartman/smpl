import { Toast, Tooltip } from './_bootstrap';

(() => {
	// * Tooltip initialization, remove it if not necessary
	for (const tooltipElement of document.querySelectorAll(
		'[data-toggle="tooltip"]',
	)) {
		new Tooltip(tooltipElement);
	}

	// * Toast initialization, remove it if not necessary
	for (const toastElement of document.querySelectorAll(".toast")) {
		new Toast(toastElement);
	}
})();
