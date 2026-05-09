<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Agenbee'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/css/style.css?v=<?php echo filemtime(ROOT_PATH . '/public/css/style.css'); ?>">
</head>
<body class="app-body">
    <div class="container-fluid px-0 app-shell">
        <div class="row g-0 app-content-wrap">
            <!-- Sidebar -->
            <?php
            $currentTitle = isset($title) ? trim((string) $title) : '';
            $hasTitleKeyword = function (array $keywords) use ($currentTitle) {
                if ($currentTitle === '') {
                    return false;
                }

                foreach ($keywords as $keyword) {
                    if ($keyword !== '' && strpos($currentTitle, $keyword) !== false) {
                        return true;
                    }
                }

                return false;
            };
            ?>
            <aside class="col-12 col-lg-2 col-xl-1 sidebar sidebar-compact p-3">
                <div class="d-flex flex-column h-100">
                    <div class="sidebar-mobile-bar d-flex d-lg-none align-items-center justify-content-between gap-2 mb-2">
                        <a href="<?php echo _HOST_URL ?>/home" class="sidebar-mobile-logo text-decoration-none d-flex align-items-center">
                            <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo.jpg" alt="Logo" class="logo-img">
                        </a>
                        <div class="dropdown">
                            <button class="btn sidebar-mobile-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Danh mục điều hướng">
                                <i class="bi bi-grid-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end sidebar-mobile-menu">
                                <li>
                                    <a href="<?php echo _HOST_URL ?>/home" class="dropdown-item <?php echo $hasTitleKeyword(['Trang chủ']) ? 'active' : '' ?>">
                                        <i class="bi bi-house-door-fill"></i>
                                        <span>Home</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo _HOST_URL ?>/package" class="dropdown-item <?php echo $hasTitleKeyword(['Gói dịch vụ', 'gói dịch vụ', 'Chi tiết gói dịch vụ']) ? 'active' : '' ?>">
                                        <i class="bi bi-box-seam-fill"></i>
                                        <span>Package</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo _HOST_URL ?>/contact" class="dropdown-item <?php echo $hasTitleKeyword(['Liên hệ']) ? 'active' : '' ?>">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span>Contact</span>
                                    </a>
                                </li>
                                <?php if(isLogin()): ?>
                                <li>
                                    <a href="<?php echo _HOST_URL ?>/order" class="dropdown-item <?php echo $hasTitleKeyword(['Đơn hàng', 'Giỏ hàng']) ? 'active' : '' ?>">
                                        <i class="bi bi-cart-check-fill"></i>
                                        <span>Order</span>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <a href="<?php echo _HOST_URL ?>/home" class="sidebar-logo text-decoration-none d-none d-lg-flex align-items-center justify-content-center">
                        <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo.jpg" alt="Logo" class="logo-img">
                    </a>

                    <nav class="sidebar-nav mt-4 mt-lg-5 d-none d-lg-flex flex-column align-items-center align-items-lg-start gap-2">
                        <a href="<?php echo _HOST_URL ?>/home" class="nav-item-btn <?php echo $hasTitleKeyword(['Trang chủ']) ? 'active' : '' ?>" data-section="home" data-title="Trang chu" data-bs-toggle="tooltip" data-bs-placement="right" title="Trang chủ">
                            <i class="bi bi-house-door-fill"></i>
                            <span>Home</span>
                        </a>
                        <a href="<?php echo _HOST_URL ?>/package" class="nav-item-btn <?php echo $hasTitleKeyword(['Gói dịch vụ', 'gói dịch vụ', 'Chi tiết gói dịch vụ']) ? 'active' : '' ?>" data-section="package" data-title="Goi dich vu" data-bs-toggle="tooltip" data-bs-placement="right" title="Gói dịch vụ">
                            <i class="bi bi-box-seam-fill"></i>
                            <span>Package</span>
                        </a>
                        <a href="<?php echo _HOST_URL ?>/contact" class="nav-item-btn <?php echo $hasTitleKeyword(['Liên hệ']) ? 'active' : '' ?>" data-section="contact" data-title="Lien he doanh nghiep" data-bs-toggle="tooltip" data-bs-placement="right" title="Liên hệ">
                            <i class="bi bi-telephone-fill"></i>
                            <span>Contact</span>
                        </a>
                        <?php if(isLogin()): ?>
                        <a href="<?php echo _HOST_URL ?>/order" class="nav-item-btn <?php echo $hasTitleKeyword(['Đơn hàng', 'Giỏ hàng']) ? 'active' : '' ?>" data-section="order" data-title="Don hang cua ban" data-bs-toggle="tooltip" data-bs-placement="right" title="Đơn hàng">
                            <i class="bi bi-cart-check-fill"></i>
                            <span>Order</span>
                        </a>
                        <?php endif; ?>
                    </nav>
                    <?php if(isLogin()): ?>
                    <a href="<?php echo _HOST_URL ?>/profile" class="profile-shortcut mt-auto text-decoration-none d-none d-lg-flex align-items-center justify-content-center" title="Mo trang profile" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Profile">
                        <?php
                        if(isLogin() && isset($user)) {
                            if(!empty($user['avatar'])) {
                                echo '<img src="' . _HOST_URL_PUBLIC . '/' . $user['avatar'] . '" alt="User avatar" class="avatar-sm">';
                            } else {
                                echo '<img src="' . _HOST_URL_PUBLIC . '/img/defaultAvatar.png" alt="User avatar" class="avatar-sm">';
                            }
                        } else {
                            echo '<img src="' . _HOST_URL_PUBLIC . '/img/defaultAvatar.png" alt="User avatar" class="avatar-sm">';
                        }
                        ?>
                    </a>
                    <?php endif; ?>
                </div>
            </aside>