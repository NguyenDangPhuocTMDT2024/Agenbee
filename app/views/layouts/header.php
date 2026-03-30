<div class="col-12 col-lg-10 col-xl-11 d-flex flex-column main-area">
                <!-- Header -->
                <header class="top-header px-3 px-md-4 py-2 border-bottom">
                    <div class="d-flex align-items-center gap-2 gap-md-3 flex-wrap">
                        <div class="search-wrap flex-grow-1">
                            <i class="bi bi-search"></i>
                            <input type="text" id="globalSearch" class="form-control" placeholder="Tìm kiếm theo từ khóa...">
                        </div>
                        <button class="btn btn-outline-secondary icon-only-btn" id="cartBtn" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Thông báo">
                            <i class="bi bi-bell-fill"></i>
                        </button>
                        <button class="btn btn-outline-secondary icon-only-btn" id="cartBtn" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Giỏ hàng">
                            <i class="bi bi-cart3"></i>
                        </button>
                        <div class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle user-menu-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img
                                src="<?php echo _HOST_URL_PUBLIC; ?>/img/defaultAvatar.png"
                                class="avatar-sm rounded-circle shadow"
                                alt="User Image"
                                />
                                <span class="d-none d-md-inline">Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                                <li class="user-dropdown-header">
                                    <img
                                        src="<?php echo _HOST_URL_PUBLIC; ?>/img/defaultAvatar.png"
                                        class="avatar-sm shadow"
                                        alt="User Image"
                                    />
                                    <div>
                                        <p class="user-name mb-0">Admin</p>
                                        <small class="user-role">Administrator</small>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="px-2 pb-2">
                                    <a href="<?php echo _HOST_URL ?>/profile" class="dropdown-action-btn">Profile</a>
                                </li>
                                <li class="px-2 pb-2">
                                    <a href="<?php echo _HOST_URL ?>/logout" class="dropdown-action-btn signout">Sign out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>