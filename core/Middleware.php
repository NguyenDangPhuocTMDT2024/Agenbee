<?php

class Middleware {
    // Middleware để kiểm tra đăng nhập
    public static function authCheck() {
        if (!getSession('token_login')) {
            setSessionFlash('msg', 'Bạn cần đăng nhập để truy cập trang này!');
            setSessionFlash('msg_type', 'warning');
            redirect('/login');
        }
    }

    // Middleware để kiểm tra quyền admin
    public static function adminCheck() {
        self::authCheck(); // Đầu tiên check login
        // Giả sử có session user_role
        if (getSession('user_role') !== 'admin') {
            setSessionFlash('msg', 'Bạn không có quyền truy cập trang này!');
            setSessionFlash('msg_type', 'danger');
            redirect('/home');
        }
    }
}

