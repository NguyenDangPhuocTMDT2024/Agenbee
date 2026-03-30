document.addEventListener("DOMContentLoaded", function () {
	var navButtons = Array.from(document.querySelectorAll(".nav-item-btn"));
	var cartBtn = document.getElementById("cartBtn");
	var logoutBtn = document.getElementById("logoutBtn");
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

	if (cartBtn) {
		cartBtn.addEventListener("click", function () {
			alert("Mo gio hang cua ban.");
		});
	}

	if (logoutBtn) {
		logoutBtn.addEventListener("click", function () {
			var isConfirmed = confirm("Ban co chac chan muon dang xuat?");
			if (isConfirmed) {
				alert("Dang xuat thanh cong.");
			}
		});
	}

	activateSection("home");
});
