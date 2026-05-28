<?php
$data = [
	'title' => 'Hồ sơ Reviewer',
];
if (isset($user)) {
	$data['user'] = $user;
}
if (isset($cartItemCount)) {
	$data['cartItemCount'] = $cartItemCount;
}
$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
layout('reviewer-header', $data);
layout('reviewer-sidebar', $data);

$reviewerProfile = $reviewerProfile ?? [];
$oldData = $oldData ?? [];
$profileData = !empty($oldData) ? array_merge($reviewerProfile, $oldData) : $reviewerProfile;
$categoriesSelected = [];
if (!empty($profileData['categories'])) {
	$categoriesSelected = array_map('trim', explode(',', $profileData['categories']));
}

function reviewerOldValue($name, $default = '') {
	global $profileData;
	return isset($profileData[$name]) ? htmlspecialchars($profileData[$name]) : htmlspecialchars($default);
}
?>

<main class="reviewer-page flex-grow-1 px-3 px-md-4 py-4">
	<section class="container-fluid px-0">
		<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
			<div>
				<p class="text-uppercase text-secondary mb-1">KOC Reviewer</p>
				<h1 class="h2 mb-0">Hồ sơ & Cấu hình kênh</h1>
				<p class="text-muted">Quản lý thông tin cá nhân, kênh và cấu hình nhận tiền.</p>
			</div>
			<div class="d-flex gap-2 flex-wrap">
				<button id="editBtn" class="btn btn-outline-primary">Chỉnh sửa hồ sơ</button>
				<button id="saveBtn" class="btn btn-primary d-none">Lưu thay đổi</button>
			</div>
		</div>

		<div class="row g-4">
			<div class="col-12 col-lg-4">
				<div class="card shadow-sm">
					<div class="card-body">
						<h3 class="h5">Thông tin cá nhân</h3>
						<?php if (!empty($msg)): ?>
			<div class="mb-3"><?php echo showMsg($msg, $msgType); ?></div>
		<?php endif; ?>

		<form id="profileForm" method="post" action="<?php echo _HOST_URL; ?>/reviewer/profile" enctype="multipart/form-data">
			<div class="row g-4">
				<div class="col-12 col-lg-4">
					<div class="card shadow-sm">
						<div class="card-body">
							<h3 class="h5">Thông tin cá nhân</h3>
							<div class="text-center mb-3">
								<?php $avatar = isset($user['avatar']) && !empty($user['avatar']) ? $user['avatar'] : '/public/img/default-avatar.png'; ?>
								<img id="avatarPreview" src="<?php echo htmlspecialchars($avatar); ?>" alt="avatar" class="rounded-circle" width="120" height="120" style="object-fit:cover;">
							</div>
							<div class="mb-3">
								<label class="form-label">Ảnh đại diện</label>
								<input type="file" name="avatar" id="avatarInput" class="form-control" accept="image/*" disabled>
							</div>
							<div class="mb-3">
								<label class="form-label">Họ và tên</label>
								<input type="text" name="name" id="name" class="form-control" value="<?php echo reviewerOldValue('name', $user['name'] ?? ''); ?>" readonly>
							</div>
							<div class="mb-3">
								<label class="form-label">Số điện thoại / Zalo</label>
								<input type="text" name="phone" id="phone" class="form-control" value="<?php echo reviewerOldValue('phone', $user['phone'] ?? ''); ?>" readonly>
							</div>

							<h5 class="h6 mt-4">Thông tin ngân hàng</h5>
							<div class="mb-3">
								<label class="form-label">Tên ngân hàng</label>
								<input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Ví dụ: Vietcombank" readonly>
							</div>
							<div class="mb-3">
								<label class="form-label">Số tài khoản</label>
								<input type="text" name="bank_account" id="bank_account" class="form-control" placeholder="Số tài khoản" readonly>
							</div>
							<div class="mb-3">
								<label class="form-label">Tên chủ tài khoản</label>
								<input type="text" name="bank_account_name" id="bank_account_name" class="form-control" placeholder="Tên chủ tài khoản" readonly>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-lg-8">
					<div class="card shadow-sm">
						<div class="card-body">
							<h3 class="h5">Năng lực & Định danh kênh</h3>
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="mb-3">
										<label class="form-label">Thế mạnh ngành hàng</label>
										<div id="categoriesList" class="d-flex flex-wrap gap-2">
											<?php $tags = ['Mỹ phẩm','Công nghệ','Gia dụng','Thời trang','Đồ dùng mẹ bé','Thực phẩm','Du lịch'];
											foreach ($tags as $tag): ?>
												<label class="form-check form-check-inline">
													<input class="form-check-input category-checkbox" type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($tag); ?>" <?php echo in_array($tag, $categoriesSelected, true) ? 'checked' : ''; ?> disabled>
													<span class="form-check-label"><?php echo htmlspecialchars($tag); ?></span>
												</label>
											<?php endforeach; ?>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="mb-3">
										<label class="form-label">Liên kết kênh Social</label>
										<input type="url" name="tiktok" id="tiktok" class="form-control mb-2" placeholder="Link TikTok" value="<?php echo reviewerOldValue('tiktok'); ?>" readonly>
										<input type="url" name="instagram" id="instagram" class="form-control mb-2" placeholder="Link Instagram" value="<?php echo reviewerOldValue('instagram'); ?>" readonly>
										<input type="url" name="facebook" id="facebook" class="form-control" placeholder="Link Facebook" value="<?php echo reviewerOldValue('facebook'); ?>" readonly>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-6">
									<div class="mb-3">
										<label class="form-label">Số lượng Người theo dõi (Followers)</label>
										<input type="number" min="0" name="followers" id="followers" class="form-control" placeholder="Số followers" value="<?php echo reviewerOldValue('followers'); ?>" readonly>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3">
										<label class="form-label">Tỷ lệ tương tác trung bình (%)</label>
										<input type="number" step="0.1" min="0" max="100" name="engagement_rate" id="engagement_rate" class="form-control" placeholder="VD: 4.5" value="<?php echo reviewerOldValue('engagement_rate'); ?>" readonly>
									</div>
								</div>
							</div>

							<div class="mt-4 text-end">
								<small class="text-muted">Lưu ý: Dữ liệu kênh sẽ được dùng để tự động điền khi ứng tuyển và gửi cho Admin khi rút tiền.</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="d-none d-flex justify-content-end mt-3">
			<button type="submit" class="btn btn-success" id="submitProfileBtn">Lưu thông tin</button>
		</div>
	</form>
	</section>
</main>
<?php layout('reviewer-footer'); ?>

<script>
document.addEventListener('DOMContentLoaded', function(){
	const editBtn = document.getElementById('editBtn');
	const saveBtn = document.getElementById('saveBtn');
	const profileForm = document.getElementById('profileForm');
	const avatarInput = document.getElementById('avatarInput');
	const avatarPreview = document.getElementById('avatarPreview');

	function setEditable(editable){
		document.querySelectorAll('#profileForm input').forEach(i => {
			if (i.type !== 'button' && i.type !== 'submit') {
				i.readOnly = !editable;
				i.disabled = !editable && i.type === 'file' ? true : i.disabled;
			}
		});
		document.querySelectorAll('.category-checkbox').forEach(cb => cb.disabled = !editable);
		avatarInput.disabled = !editable;
		if(editable){
			editBtn.classList.add('d-none');
			saveBtn.classList.remove('d-none');
		} else {
			editBtn.classList.remove('d-none');
			saveBtn.classList.add('d-none');
		}
	}

	editBtn.addEventListener('click', function(){ setEditable(true); });
	saveBtn.addEventListener('click', function(){
		profileForm.submit();
	});

	avatarInput && avatarInput.addEventListener('change', function(e){
		const f = e.target.files && e.target.files[0];
		if(!f) return;
		const reader = new FileReader();
		reader.onload = function(ev){ avatarPreview.src = ev.target.result; };
		reader.readAsDataURL(f);
	});

	setEditable(false);
});
</script>

