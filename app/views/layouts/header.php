<div class="col-12 col-lg-10 col-xl-11 d-flex flex-column main-area">
                <!-- Header -->
                <header class="top-header px-3 px-md-4 py-2 border-bottom">
                    <div class="d-flex align-items-center gap-2 gap-md-3 flex-wrap">
                        <div class="search-wrap flex-grow-1">
                            <i class="bi bi-search"></i>
                            <input type="text" id="globalSearch" class="form-control" placeholder="Tìm kiếm theo từ khóa...">
                        </div>
                        <?php if(isLogin()): ?>
                        <?php
                            $cartCount = isset($cartItemCount) ? (int) $cartItemCount : 0;
                            $cartCountDisplay = $cartCount > 9 ? '9+' : $cartCount;
                        ?>
                        <button class="btn btn-outline-secondary icon-only-btn" id="notifyBtn" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Thông báo">
                            <i class="bi bi-bell-fill"></i>
                        </button>
                        <a href="<?php echo _HOST_URL; ?>/cart" class="btn btn-outline-secondary icon-only-btn cart-icon-btn" id="cartBtn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Giỏ hàng">
                            <span class="cart-icon-wrap">
                                <i class="bi bi-cart3"></i>
                                <?php if($cartCount > 0): ?>
                                <span class="cart-count-badge" style="position:absolute; top:-8px; right:-8px; transform:translate(45%,-45%); background:#f4c430; z-index:2; border-radius:50%; width:20px; height:20px; padding:0; display:inline-flex; align-items:center; justify-content:center; line-height:1;"><?php echo $cartCountDisplay; ?></span>
                                <?php endif; ?>
                            </span>
                        </a>
                        <?php endif; ?>
                        <div class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle user-menu-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php
                                    if(isLogin() && isset($user)) {
                                        if(!empty($user['avatar'])) {
                                            echo '<img src="' . _HOST_URL_PUBLIC . '/' . $user['avatar'] . '" alt="User Image" class="avatar-sm shadow">';
                                        } else {
                                            echo '<img src="' . _HOST_URL_PUBLIC . '/img/defaultAvatar.png" alt="User Image" class="avatar-sm shadow">';
                                        }
                                    } else {
                                        echo '<img src="' . _HOST_URL_PUBLIC . '/img/defaultAvatar.png" alt="User Image" class="avatar-sm shadow">';
                                    }
                                ?>
                                <span class="d-none d-md-inline">
                                    <?php
                                        if(isLogin() && isset($user)) {
                                            echo $user['name'];
                                        } else {
                                            echo 'Guest';
                                        }
                                    ?>
                                </span>
                            </a>
                            <?php if(isLogin()): ?>
                            <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                                <li class="user-dropdown-header">
                                    <?php
                                    if(isLogin() && isset($user)) {
                                        if(!empty($user['avatar'])) {
                                            echo '<img src="' . _HOST_URL_PUBLIC . '/' . $user['avatar'] . '" alt="User Image" class="avatar-sm shadow">';
                                        } else {
                                            echo '<img src="' . _HOST_URL_PUBLIC . '/img/defaultAvatar.png" alt="User Image" class="avatar-sm shadow">';
                                        }
                                    } else {
                                        echo '<img src="' . _HOST_URL_PUBLIC . '/img/defaultAvatar.png" alt="User Image" class="avatar-sm shadow">';
                                    }
                                    ?>
                                    <div>
                                        <p class="user-name mb-0">
                                            <?php
                                            if(isLogin() && isset($user)) {
                                                echo $user['name'];
                                            } else {
                                                echo 'Guest';
                                            }
                                            ?>
                                        </p>
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
                            <?php endif; ?>
                        </div>
                        <?php if(!isLogin()): ?>
                            <a href="<?php echo _HOST_URL; ?>/login" class="btn btn-outline-danger icon-only-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Đăng nhập" aria-label="Đăng nhập">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </header>