<?php
$data = [
    'title' => 'Hồ sơ cá nhân',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$profileData = [
    'username' => 'sinhvinhnghoxoch',
    'name' => 'sinh viên nghèo',
    'email' => 'ng***********@gmail.com',
    'phone' => '0123 456 789',
    'avatar' => _HOST_URL_PUBLIC . '/img/defaultAvatar.png',
];

$shopData = [
    'shop_name' => 'Sinh Vinh Shop',
    'logo' => _HOST_URL_PUBLIC . '/img/service_picture.jpg',
    'address' => 'Quận 1, TP. Hồ Chí Minh',
    'shop_description' => 'Shop chuyên bán phụ kiện và sản phẩm trend, đang trong giai đoạn tối ưu lại hiển thị, danh mục và nội dung bán hàng để tăng chuyển đổi.',
    'bank' => 'MB Bank - 9704 **** 1234',
    'zalo' => '0912 345 678',
    'facebook' => 'facebook.com/sinhvinhshop',
];

$navItems = [
    ['label' => 'Hồ sơ', 'icon' => 'bi-person-vcard-fill', 'active' => true],
    ['label' => 'Ngân hàng', 'icon' => 'bi-bank2', 'active' => false],
    ['label' => 'Địa chỉ', 'icon' => 'bi-geo-alt-fill', 'active' => false],
    ['label' => 'Đổi mật khẩu', 'icon' => 'bi-shield-lock-fill', 'active' => false],
    ['label' => 'Cài đặt thông báo', 'icon' => 'bi-bell-fill', 'active' => false],
    ['label' => 'Thiết lập riêng tư', 'icon' => 'bi-lock-fill', 'active' => false],
    ['label' => 'Thông tin cá nhân', 'icon' => 'bi-card-text', 'active' => false],
];
?>
<style>
    .profile-page {
        background:
            radial-gradient(circle at top left, rgba(244, 196, 48, 0.14), transparent 30%),
            linear-gradient(180deg, #fffdf8 0%, #fff7e4 100%);
        border-radius: 28px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }

    .profile-shell {
        display: grid;
        grid-template-columns: 260px minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }

    .profile-sidebar,
    .profile-main {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(201, 154, 17, 0.12);
        box-shadow: 0 16px 36px rgba(31, 24, 9, 0.08);
    }

    .profile-sidebar {
        padding: 18px 16px;
    }

    .profile-user-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 18px;
        border-bottom: 1px solid rgba(201, 154, 17, 0.12);
    }

    .profile-avatar {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(244, 196, 48, 0.5);
    }

    .profile-user-name {
        font-size: 1.02rem;
        font-weight: 800;
        color: #1b1b1b;
        line-height: 1.2;
        margin-bottom: 2px;
    }

    .profile-user-edit {
        color: #8b8b8b;
        font-size: 0.94rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .profile-user-edit i {
        color: #9b7500;
    }

    .profile-section {
        margin-top: 18px;
    }

    .profile-section-title {
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: #9b7500;
        font-weight: 800;
        margin: 0 0 10px;
    }

    .profile-menu {
        display: grid;
        gap: 8px;
    }

    .profile-menu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 14px;
        color: #303030;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .profile-menu-item i {
        color: #1f66e5;
        font-size: 1.05rem;
    }

    .profile-menu-item.active,
    .profile-menu-item:hover {
        background: rgba(244, 196, 48, 0.14);
        color: #111111;
    }

    .profile-badge {
        margin-left: auto;
        background: #ff4d2d;
        color: #ffffff;
        border-radius: 999px;
        padding: 0.08rem 0.4rem;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .profile-main {
        padding: 26px;
    }

    .profile-main-head h1 {
        font-size: clamp(1.5rem, 2.2vw, 2rem);
        margin: 0 0 4px;
        color: #1b1b1b;
        font-weight: 800;
    }

    .profile-main-head p {
        margin: 0;
        color: #666666;
    }

    .profile-divider {
        margin: 18px 0 24px;
        border-top: 1px solid rgba(201, 154, 17, 0.14);
    }

    .profile-form-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) 280px;
        gap: 32px;
        align-items: start;
    }

    .profile-form {
        display: grid;
        gap: 18px;
    }

    .profile-row {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 18px;
        align-items: center;
    }

    .profile-label {
        text-align: right;
        color: #6a6a6a;
        font-weight: 500;
        font-size: 1rem;
    }

    .profile-field,
    .profile-select,
    .profile-textarea {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 2px;
        background: #ffffff;
        padding: 0.78rem 0.95rem;
        color: #222222;
        font-size: 1rem;
        outline: none;
        box-shadow: none;
    }

    .profile-field:focus,
    .profile-select:focus,
    .profile-textarea:focus {
        border-color: rgba(244, 196, 48, 0.9);
        box-shadow: 0 0 0 0.16rem rgba(244, 196, 48, 0.13);
    }

    .profile-inline-link {
        color: #1677ff;
        text-decoration: underline;
        font-weight: 500;
    }

    .profile-gender-group {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .profile-radio {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #303030;
    }

    .profile-date-group {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .profile-avatar-panel {
        text-align: center;
        padding-left: 8px;
        border-left: 1px solid rgba(201, 154, 17, 0.12);
    }

    .profile-avatar-big {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        margin: 0 auto 18px;
    }

    .profile-avatar-btn {
        display: inline-block;
        min-width: 126px;
        padding: 0.75rem 1rem;
        border-radius: 2px;
        border: 1px solid #dddddd;
        background: #ffffff;
        color: #444444;
        text-decoration: none;
        font-size: 1rem;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.02);
    }

    .profile-upload-note {
        margin-top: 18px;
        color: #8b8b8b;
        line-height: 1.45;
    }

    .profile-save-row {
        padding-top: 10px;
        padding-left: 180px;
    }

    .profile-save-btn {
        background: #f4511e;
        color: #ffffff;
        border: none;
        border-radius: 2px;
        padding: 0.9rem 1.8rem;
        font-size: 1rem;
        font-weight: 500;
        box-shadow: none;
    }

    .profile-save-btn:hover {
        background: #e64a19;
        color: #ffffff;
    }

    @media (max-width: 1199.98px) {
        .profile-shell,
        .profile-form-grid {
            grid-template-columns: 1fr;
        }

        .profile-avatar-panel {
            padding-left: 0;
            padding-top: 18px;
            border-left: 0;
            border-top: 1px solid rgba(201, 154, 17, 0.12);
        }
    }

    @media (max-width: 767.98px) {
        .profile-main,
        .profile-sidebar {
            padding: 18px;
            border-radius: 18px;
        }

        .profile-row,
        .profile-save-row {
            grid-template-columns: 1fr;
            padding-left: 0;
        }

        .profile-label {
            text-align: left;
        }

        .profile-date-group {
            grid-template-columns: 1fr;
        }

        .profile-menu-item {
            font-size: 0.95rem;
        }
    }
</style>

<main class="profile-page px-3 px-md-4 py-4 flex-grow-1">
    <div class="profile-shell">
        <aside class="profile-sidebar">
            <div class="profile-user-head">
                <img src="<?php echo htmlspecialchars($profileData['avatar']); ?>" alt="Avatar" class="profile-avatar">
                <div>
                    <div class="profile-user-name"><?php echo htmlspecialchars($profileData['username']); ?></div>
                    <a href="#" class="profile-user-edit"><i class="bi bi-pencil-fill"></i> Sửa Hồ Sơ</a>
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-menu-item">
                    <i class="bi bi-person-circle"></i>
                    <span>Tên nè</span>
                </div>
                <div class="profile-menu-item">
                    <i class="bi bi-envelope"></i>
                    <span>mail@gmail.com</span>
                </div>
                <div class="profile-menu-item">
                    <i class="bi bi-phone"></i>
                    <span>09xxxxx</span>
                </div>
            </div>
        </aside>

        <section class="profile-main">
            <div class="profile-main-head">
                <h1>Hồ Sơ Shop</h1>
            </div>

            <div class="profile-divider"></div>

            <div class="profile-form-grid">
                <div class="profile-form">
                    <div class="profile-row">
                        <div class="profile-label">Tên Shop</div>
                        <div>
                            <input type="text" class="profile-field" value="<?php echo htmlspecialchars($profileData['username']); ?>" readonly>
                        </div>
                    </div>

                    <div class="profile-row">
                        <div class="profile-label">Tên</div>
                        <div><input type="text" class="profile-field" value="<?php echo htmlspecialchars($profileData['name']); ?>"></div>
                    </div>

                    <div class="profile-row">
                        <div class="profile-label">Email</div>
                        <div>
                            <span><?php echo htmlspecialchars($profileData['email']); ?></span>
                            <a href="#" class="profile-inline-link ms-2">Thay Đổi</a>
                        </div>
                    </div>

                    <div class="profile-row">
                        <div class="profile-label">Số điện thoại</div>
                        <div><a href="#" class="profile-inline-link">Thêm</a></div>
                    </div>

                    <div class="profile-row">
                        <div class="profile-label">Giới tính <span class="text-muted">?</span></div>
                        <div class="profile-gender-group">
                            <label class="profile-radio"><input type="radio" name="gender" checked> Nam</label>
                            <label class="profile-radio"><input type="radio" name="gender"> Nữ</label>
                            <label class="profile-radio"><input type="radio" name="gender"> Khác</label>
                        </div>
                    </div>

                    <div class="profile-row">
                        <div class="profile-label">Ngày sinh <span class="text-muted">?</span></div>
                        <div class="profile-date-group">
                            <select class="profile-select">
                                <option>Ngày</option>
                                <?php for ($day = 1; $day <= 31; $day++): ?>
                                    <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                                <?php endfor; ?>
                            </select>
                            <select class="profile-select">
                                <option>Tháng</option>
                                <?php for ($month = 1; $month <= 12; $month++): ?>
                                    <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                <?php endfor; ?>
                            </select>
                            <select class="profile-select">
                                <option>Năm</option>
                                <?php for ($year = 2026; $year >= 1990; $year--): ?>
                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="profile-save-row">
                        <button type="button" class="profile-save-btn">Lưu</button>
                    </div>
                </div>

                <aside class="profile-avatar-panel">
                    <img src="<?php echo htmlspecialchars($profileData['avatar']); ?>" alt="Avatar" class="profile-avatar-big">
                    <a href="#" class="profile-avatar-btn">Chọn Ảnh</a>
                    <div class="profile-upload-note">
                        <div>Dụng lượng file tối đa 1 MB</div>
                        <div>Định dạng: JPEG, PNG</div>
                    </div>

                    <div class="profile-section mt-4 text-start">
                        <div class="profile-section-title">Thông tin shop</div>
                        <div class="profile-menu-item active justify-content-start mb-2">
                            <i class="bi bi-shop-window"></i>
                            <span><?php echo htmlspecialchars($shopData['shop_name']); ?></span>
                        </div>
                        <div class="profile-menu-item justify-content-start mb-2">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span><?php echo htmlspecialchars($shopData['address']); ?></span>
                        </div>
                        <div class="profile-menu-item justify-content-start mb-2">
                            <i class="bi bi-wallet2"></i>
                            <span><?php echo htmlspecialchars($shopData['bank']); ?></span>
                        </div>
                        <div class="profile-menu-item justify-content-start mb-2">
                            <i class="bi bi-whatsapp"></i>
                            <span><?php echo htmlspecialchars($shopData['zalo']); ?></span>
                        </div>
                        <div class="profile-menu-item justify-content-start">
                            <i class="bi bi-facebook"></i>
                            <span><?php echo htmlspecialchars($shopData['facebook']); ?></span>
                        </div>
                    </div>

                    <div class="profile-upload-note text-start">
                        <?php echo htmlspecialchars($shopData['shop_description']); ?>
                    </div>
                </aside>
            </div>
        </section>
    </div>
</main>

<?php
layout('footer');
?>