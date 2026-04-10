document.addEventListener("DOMContentLoaded", function () {
	var navButtons = Array.from(document.querySelectorAll(".nav-item-btn"));
	var tooltipTriggers = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

	function activateSection(sectionName) {
		navButtons.forEach(function (btn) {
			btn.classList.toggle("active", btn.dataset.section === sectionName);
		});
	}

	navButtons.forEach(function (btn) {
		btn.addEventListener("click", function () {
			activateSection(btn.dataset.section);
		});
	});

	tooltipTriggers.forEach(function (item) {
		new bootstrap.Tooltip(item);
	});

	activateSection("home");
});
