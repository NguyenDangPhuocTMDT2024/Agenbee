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

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 170px;
        padding: 0.78rem 1.1rem;
        border-radius: 12px;
        border: 1px solid rgba(201, 154, 17, 0.35);
        background: linear-gradient(135deg, #fff9ea 0%, #ffefbf 100%);
        color: #5f4700;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 8px 18px rgba(201, 154, 17, 0.12);
    }

    .profile-avatar-btn:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #ffefbf 0%, #ffe38a 100%);
        border-color: rgba(201, 154, 17, 0.5);
        color: #3f2f00;
    }

    .profile-avatar-btn:focus-visible {
        outline: 0;
        box-shadow: 0 0 0 0.18rem rgba(244, 196, 48, 0.28), 0 8px 18px rgba(201, 154, 17, 0.12);
    }

    .profile-avatar-upload {
        display: grid;
        justify-items: center;
        gap: 10px;
    }

    .profile-avatar-input {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        pointer-events: none;
    }

    .profile-upload-filename {
        margin: 0;
        font-size: 0.88rem;
        color: #7d7d7d;
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
        background: rgba(201, 154, 17, 0.35);
        color: #5f4700;
        border: none;
        border-radius: 2px;
        padding: 0.9rem 1.8rem;
        font-size: 1rem;
        font-weight: 500;
        box-shadow: none;
    }

    .profile-save-btn:hover {
        background: rgba(201, 154, 17, 0.5);
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
    <?php if ($msg)
        echo showMsg($msg, $msgType);
    ?>
    <div class="profile-shell">
        <aside class="profile-sidebar">
            <div class="profile-user-head">
                <img src="<?php echo (!empty($user['avatar']) ? _HOST_URL_PUBLIC . $user['avatar'] : _HOST_URL_PUBLIC . 'img/defaultAvatar.png') ?>" alt="Avatar" class="profile-avatar">
                <div>
                    <div class="profile-user-name"><?php echo !empty($user['name']) ? $user['name'] : 'User' ?></div>
                </div>
            </div>

            <div class="profile-section profile-menu">
                <div class="profile-menu-item">
                    <i class="bi bi-person-circle"></i>
                    <span>
                        <?php
                        if (!empty($user['role'])) {
                            if ($user['role'] == 'user') {
                                echo 'Chủ shop';
                            } else {
                                echo 'Guest';
                            }
                        } else {
                            echo 'Guest';
                        }
                        ?></span>
                </div>
                <div class="profile-menu-item">
                    <i class="bi bi-envelope"></i>
                    <span><?php echo !empty($user['email']) ? $user['email'] : 'mail@gmail.com' ?></span>
                </div>
                <div class="profile-menu-item">
                    <i class="bi bi-phone"></i>
                    <span><?php echo !empty($user['phone']) ? $user['phone'] : '09xxxxx' ?></span>
                </div>
                <!-- <div class="profile-menu-item">
                    <i class="bi bi-shield-lock-fill"></i> 
                    <button type="button" onclick="showChangePass()" class="btn btn-outline-warning">Đổi mật khẩu</button>
                </div> -->
            </div>
        </aside>

        <section class="profile-main">
            <div class="profile-main-head">
                <h1 class="profile-section-title">Hồ Sơ Shop</h1>
            </div>

            <div class="profile-divider"></div>

            <form method="POST" action="" enctype="multipart/form-data" class="profile-form-grid">
                <div class="profile-form">

                    <input type="hidden" name="user_id" value="<?php echo !empty($user['id']) ? $user['id'] : '' ?>">

                    <div class="profile-row">
                        <div class="profile-label">Tên Shop</div>
                        <div>
                            <input type="text" name="shop_name" class="profile-field" <?php echo !empty($shopInfo['shop_name']) ? 'value="' . $shopInfo['shop_name'] . '"' : 'placeholder="Chưa có thông tin"' ?>>
                        </div>
                        <?php
                        if (!empty($shopInfo['shop_name'])) {
                            echo showErrors($errors, 'shop_name');
                        }
                        ?>
                    </div>

                    <div class="profile-row">
                        <div class="profile-label">Địa chỉ</div>
                        <div><input type="text" name="address" class="profile-field" <?php echo !empty($shopInfo['address']) ? 'value="' . $shopInfo['address'] . '"' : 'placeholder="Chưa có thông tin"' ?>></div>
                        <?php
                        if (!empty($shopInfo['address'])) {
                            echo showErrors($errors, 'address');
                        }
                        ?>
                    </div>

                    <div class="profile-row">
                        <div class="profile-label">Ngành hàng</div>
                        <div><input type="text" name="major" class="profile-field" <?php echo !empty($shopInfo['major']) ? 'value="' . $shopInfo['major'] . '"' : 'placeholder="Chưa có thông tin"' ?>></div>
                        <?php
                        if (!empty($shopInfo['major'])) {
                            echo showErrors($errors, 'major');
                        }
                        ?>
                    </div>

                    <div class="profile-row">
                        <div class="profile-label">Mô tả</div>
                        <div><textarea name="shop_description" class="profile-field" rows="4" placeholder="Chưa có thông tin"><?php echo !empty($shopInfo['shop_description']) ? $shopInfo['shop_description'] : '' ?></textarea></div>
                    </div>
                </div>

                <aside class="profile-avatar-panel">
                    <div class="profile-section-title">Logo</div>
                    <img src="<?php echo !empty($shopInfo['logo']) ? _HOST_URL_PUBLIC . $shopInfo['logo'] : _HOST_URL_PUBLIC . '/img/defaultAvatar.png' ?>" alt="Logo" class="profile-avatar-big">
                    <div class="profile-avatar-upload">
                        <input type="file" class="profile-avatar-input" name="logo" id="profileLogoInput" accept="image/*">
                        <label for="profileLogoInput" class="profile-avatar-btn">
                            <i class="bi bi-upload"></i>
                            Chọn ảnh mới
                        </label>
                        <p class="profile-upload-filename"><?php echo !empty($shopInfo['logo']) ? 'Chọn ảnh' : 'Chưa có logo' ?></p>
                        <?php
                        if (!empty($shopInfo['logo'])) {
                            echo showErrors($errors, 'logo');
                        }
                        ?>
                    </div>
                </aside>
                <div class="profile-save-row">
                    <button type="submit" class="profile-save-btn">Lưu</button>
                </div>
            </form>
        </section>
    </div>
</main>
<!-- <script>
    function showChangePass() {
        
    }
</script> -->
<?php
layout('footer');
?>