<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenbee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo _HOST_URL_PUBLIC; ?>/css/style.css">
</head>
<body class="app-body">
    <div class="container-fluid px-0 app-shell">
        <div class="row g-0 app-content-wrap">
            <!-- Sidebar -->
            <aside class="col-12 col-lg-2 col-xl-1 sidebar sidebar-compact p-3">
                <div class="d-flex flex-column h-100">
                    <a href="<?php echo _HOST_URL ?>/home" class="sidebar-logo text-decoration-none d-flex align-items-center justify-content-center">
                        <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo.jpg" alt="Logo" class="logo-img">
                    </a>

                    <nav class="sidebar-nav mt-4 mt-lg-5">
                        <button class="nav-item-btn active" data-section="home" data-title="Trang chu" data-bs-toggle="tooltip" data-bs-placement="right" title="Trang chủ">
                            <i class="bi bi-house-door-fill"></i>
                            <span>Home</span>
                        </button>
                        <button class="nav-item-btn" data-section="package" data-title="Goi dich vu" data-bs-toggle="tooltip" data-bs-placement="right" title="Gói dịch vụ">
                            <i class="bi bi-box-seam-fill"></i>
                            <span>Package</span>
                        </button>
                        <button class="nav-item-btn" data-section="contact" data-title="Lien he doanh nghiep" data-bs-toggle="tooltip" data-bs-placement="right" title="Liên hệ">
                            <i class="bi bi-telephone-fill"></i>
                            <span>Contact</span>
                        </button>
                        <button class="nav-item-btn" data-section="order" data-title="Don hang cua ban" data-bs-toggle="tooltip" data-bs-placement="right" title="Đơn hàng">
                            <i class="bi bi-cart-check-fill"></i>
                            <span>Order</span>
                        </button>
                    </nav>

                    <a href="#" class="profile-shortcut mt-auto text-decoration-none d-flex align-items-center justify-content-center" title="Mo trang profile" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Profile">
                        <img src="<?php echo _HOST_URL_PUBLIC; ?>/img/defaultAvatar.png" alt="User avatar" class="avatar-sm">
                    </a>
                </div>
            </aside>