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

$avatarPath = _HOST_URL_PUBLIC . '/img/defaultAvatar.png';
if (!empty($user['avatar'])) {
    $avatarPath = _HOST_URL_PUBLIC . '/' . ltrim($user['avatar'], '/');
}

$displayRole = 'Guest';
if (!empty($user['role'])) {
    if ($user['role'] === 'user') {
        $displayRole = 'Chủ shop';
    } elseif ($user['role'] === 'admin') {
        $displayRole = 'Admin';
    } else {
        $displayRole = (string) $user['role'];
    }
}

$displayCreatedAt = '---';
if (!empty($user['created_at'])) {
    $ts = strtotime((string) $user['created_at']);
    $displayCreatedAt = $ts ? date('d/m/Y H:i', $ts) : (string) $user['created_at'];
}

$displayPassword = !empty($user['password']) ? '************' : '---';
$displayOrderCount = isset($orderCount) ? (int) $orderCount : 0;
?>

<style>
    .profile-page {
        background:
            radial-gradient(circle at top left, rgba(244, 196, 48, 0.14), transparent 28%),
            linear-gradient(180deg, #fffdf8 0%, #fff7e4 100%);
        border-radius: 28px;
    }

    .profile-card {
        border-radius: 24px;
        border: 1px solid rgba(201, 154, 17, 0.14);
        background: linear-gradient(180deg, #ffffff 0%, #fff8ea 100%);
        box-shadow: 0 16px 34px rgba(31, 24, 9, 0.08);
        padding: 22px;
    }

    .profile-head {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
    }

    .profile-avatar {
        width: 74px;
        height: 74px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(244, 196, 48, 0.5);
    }

    .profile-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 800;
        color: #1b1b1b;
    }

    .profile-subtitle {
        margin: 2px 0 0;
        color: #6d6d6d;
        font-size: 0.95rem;
    }

    .profile-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .profile-info-item {
        border-radius: 14px;
        border: 1px solid rgba(201, 154, 17, 0.12);
        background: rgba(255, 249, 234, 0.72);
        padding: 11px 12px;
    }

    .profile-info-key {
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.72rem;
        font-weight: 700;
        color: #9b7500;
        margin-bottom: 5px;
    }

    .profile-info-value {
        color: #1f1f1f;
        font-size: 0.96rem;
        font-weight: 600;
        word-break: break-word;
    }

    .profile-stats {
        margin-top: 14px;
        border-radius: 14px;
        border: 1px solid rgba(201, 154, 17, 0.12);
        background: rgba(244, 196, 48, 0.12);
        padding: 12px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .profile-stats-label {
        color: #5f4a14;
        font-weight: 700;
    }

    .profile-stats-value {
        color: #1b1b1b;
        font-size: 1.25rem;
        font-weight: 800;
    }

    @media (max-width: 767.98px) {
        .profile-card {
            padding: 16px;
            border-radius: 18px;
        }

        .profile-head {
            align-items: flex-start;
        }

        .profile-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="profile-page px-3 px-md-4 py-4 flex-grow-1">
    <?php if (!empty($msg)): ?>
        <div class="mb-3"><?php echo showMsg($msg, $msgType); ?></div>
    <?php endif; ?>

    <section class="profile-card">
        <div class="d-flex flex-row gap-3 mb-4 align-items-center justify-content-between flex-wrap">
            <div class="profile-head">
                <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="Avatar" class="profile-avatar">
                <div>
                    <h1 class="profile-title"><?php echo htmlspecialchars($user['name'] ?? 'User'); ?></h1>
                    <p class="profile-subtitle">Thông tin tài khoản người dùng</p>
                    <span class="profile-info-value"><?php echo htmlspecialchars($displayRole); ?></span>
                </div>
            </div>
            <div>
                <span class="profile-info-key">Ngày tạo</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($displayCreatedAt); ?></span>
            </div>
        </div>

        <div class="profile-info-grid">
            <div class="profile-info-item">
                <span class="profile-info-key">Họ tên</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user['name'] ?? '---'); ?></span>
            </div>
            <div class="profile-info-item">
                <span class="profile-info-key">Số điện thoại</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user['phone'] ?? '---'); ?></span>
            </div>
            <div class="profile-info-item">
                <span class="profile-info-key">Email</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($user['email'] ?? '---'); ?></span>
            </div>
            <div class="profile-info-item">
                <span class="profile-info-key">Mật khẩu</span>
                <span class="profile-info-value"><?php echo htmlspecialchars($displayPassword); ?></span>
            </div>
        </div>

        <div class="profile-stats">
            <span class="profile-stats-label">Tổng số đơn hàng</span>
            <span class="profile-stats-value"><?php echo $displayOrderCount; ?></span>
        </div>
    </section>
</main>

<?php
layout('footer');
?>