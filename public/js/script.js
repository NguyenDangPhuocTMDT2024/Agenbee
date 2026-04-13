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

	function normalizePath(path) {
		return path.replace(/\/+$/, "");
	}

	function activateByCurrentPath() {
		var currentPath = normalizePath(window.location.pathname);
		var matchedButton = null;
		var matchedLength = -1;

		navButtons.forEach(function (btn) {
			var href = btn.getAttribute("href");
			if (!href) {
				return;
			}

			var btnPath = normalizePath(new URL(href, window.location.origin).pathname);
			var isMatch = currentPath === btnPath || currentPath.indexOf(btnPath + "/") === 0;

			if (isMatch && btnPath.length > matchedLength) {
				matchedButton = btn;
				matchedLength = btnPath.length;
			}
		});

		if (matchedButton) {
			activateSection(matchedButton.dataset.section);
		}
	}

	activateByCurrentPath();
});
