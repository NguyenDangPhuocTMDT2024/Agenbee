<?php
$data = [
    'title' => 'Liên hệ',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$currentName = isset($user['name']) ? $user['name'] : '';
$currentPhone = isset($user['phone']) ? $user['phone'] : '';

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
$errors = getSessionFlash('errors');
?>
<style>
    .contact-page {
        background:
            radial-gradient(circle at top left, rgba(244, 196, 48, 0.14), transparent 28%),
            linear-gradient(180deg, #fffdf8 0%, #fff7e4 100%);
        border-radius: 28px;
        padding-bottom: 28px;
    }

    .contact-hero {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-items: flex-start;
        padding: 24px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(17, 17, 17, 0.95) 0%, rgba(37, 29, 14, 0.96) 100%);
        color: #fff8de;
        box-shadow: 0 24px 60px rgba(17, 17, 17, 0.14);
        position: relative;
        overflow: hidden;
    }

    .contact-hero::after {
        content: "";
        position: absolute;
        inset: auto -120px -140px auto;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(244, 196, 48, 0.28) 0%, rgba(244, 196, 48, 0) 68%);
        pointer-events: none;
    }

    .contact-kicker,
    .contact-panel-label {
        text-transform: uppercase;
        letter-spacing: 0.16em;
        font-size: 0.75rem;
        font-weight: 700;
        color: #f4c430;
    }

    .contact-title {
        font-size: clamp(1.9rem, 3vw, 3.1rem);
        line-height: 1.08;
        font-weight: 800;
        max-width: 15ch;
        margin: 0;
    }

    .contact-subtitle {
        max-width: 62ch;
        color: rgba(255, 248, 222, 0.82);
        line-height: 1.7;
    }

    .contact-hero-note {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(6px);
        min-width: 250px;
    }

    .contact-hero-note i {
        font-size: 1.5rem;
        color: #ffd451;
    }

    .contact-hero-note strong,
    .contact-hero-note span {
        display: block;
    }

    .contact-hero-note span {
        color: rgba(255, 248, 222, 0.78);
        font-size: 0.92rem;
    }

    .contact-layout {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 24px;
    }

    .contact-form-panel,
    .contact-info-panel {
        padding: 24px;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #fff8ea 100%);
        border: 1px solid rgba(201, 154, 17, 0.14);
        box-shadow: 0 16px 36px rgba(31, 24, 9, 0.08);
    }

    .contact-panel-head {
        margin-bottom: 18px;
    }

    .contact-panel-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: #1b1b1b;
    }

    .contact-label {
        font-weight: 700;
        color: #3f3f3f;
        margin-bottom: 0.45rem;
    }

    .contact-input {
        border-radius: 14px;
        border: 1px solid rgba(201, 154, 17, 0.18);
        padding: 0.85rem 1rem;
        background: #fffef8;
        box-shadow: none;
    }

    .contact-input:focus {
        border-color: rgba(244, 196, 48, 0.9);
        box-shadow: 0 0 0 0.2rem rgba(244, 196, 48, 0.14);
        background: #ffffff;
    }

    .contact-textarea {
        resize: vertical;
        min-height: 160px;
    }

    .btn-contact-primary {
        background: linear-gradient(135deg, #f4c430 0%, #e7b20f 100%);
        color: #111111;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 999px;
        font-weight: 700;
        padding: 0.9rem 1.4rem;
    }

    .btn-contact-primary:hover {
        background: linear-gradient(135deg, #ffd451 0%, #f1c228 100%);
        color: #111111;
    }

    .contact-form-hint {
        color: #6f6f6f;
        font-size: 0.94rem;
    }

    .contact-channel-list {
        display: grid;
        gap: 12px;
    }

    .contact-channel-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #fff9ef 100%);
        border: 1px solid rgba(201, 154, 17, 0.14);
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
    }

    .contact-channel-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(31, 24, 9, 0.08);
        color: inherit;
    }

    .contact-channel-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(17, 17, 17, 0.92);
        color: #ffd451;
        flex: 0 0 46px;
    }

    .contact-channel-name {
        display: block;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #9b7500;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .contact-channel-card strong {
        font-size: 1rem;
        color: #1b1b1b;
        word-break: break-word;
    }

    .contact-quick-box {
        margin-top: 16px;
        padding: 16px;
        border-radius: 18px;
        background: rgba(244, 196, 48, 0.1);
        border: 1px solid rgba(201, 154, 17, 0.14);
        color: #4a4a4a;
        line-height: 1.65;
    }

    @media (max-width: 991.98px) {

        .contact-layout,
        .contact-hero {
            grid-template-columns: 1fr;
        }

        .contact-hero {
            display: grid;
        }
    }

    @media (max-width: 575.98px) {
        .contact-page {
            border-radius: 18px;
        }

        .contact-hero,
        .contact-form-panel,
        .contact-info-panel {
            padding: 18px;
            border-radius: 18px;
        }

        .contact-title {
            font-size: 1.6rem;
        }
    }
</style>
<main class="contact-page px-3 px-md-4 py-4 flex-grow-1">
    <section class="contact-hero mb-4">
        <div>
            <p class="contact-kicker mb-2">Liên hệ Agenbee</p>
            <h1 class="contact-title mb-2">Gửi thông tin, nhận tư vấn setup shop đúng giai đoạn.</h1>
        </div>
        <div class="contact-hero-note">
            <i class="bi bi-chat-square-dots-fill"></i>
            <div>
                <strong>Tư vấn nhanh</strong>
                <span>Phản hồi trong khung giờ làm việc</span>
            </div>
        </div>
    </section>
    <?php
    if ($msg) {
        echo showMsg($msg, $msgType);
    }
    ?>
    <section class="contact-layout">
        <div class="contact-form-panel">
            <div class="contact-panel-head">
                <p class="contact-panel-label mb-1">Form liên hệ</p>
                <h2 class="contact-panel-title mb-0">Thông tin khách hàng</h2>
            </div>

            <form method="post" action="" enctype="multipart/form-data" class="contact-form">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label contact-label">Họ tên</label>
                        <input type="text" name="name" class="form-control contact-input" value="<?php echo htmlspecialchars($currentName); ?>" placeholder="Nhập họ tên của bạn">
                        <?php
                        if (!empty($errors)) {
                            echo showErrors($errors, 'name');
                        }
                        ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label contact-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control contact-input" value="<?php echo htmlspecialchars($currentPhone); ?>" placeholder="Nhập số điện thoại">
                        <?php
                        if (!empty($errors)) {
                            echo showErrors($errors, 'phone');
                        }
                        ?>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label contact-label">Bạn đã có shop chưa?</label>
                        <select name="shop_status" class="form-select contact-input">
                            <option value="">Chọn tình trạng shop</option>
                            <option value="chưa có">Chưa có shop</option>
                            <option value="chưa bán tốt">Đã có nhưng chưa bán tốt</option>
                            <option value="muốn mở rộng">Đang bán ổn muốn mở rộng</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label contact-label">Ngân sách dự kiến</label>
                        <select name="budget_range" class="form-select contact-input">
                            <option value="">Chọn ngân sách</option>
                            <option value="under_1m">Dưới 1 triệu</option>
                            <option value="1m-3m">1 - 3 triệu</option>
                            <option value="over_3m">Trên 3 triệu</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label contact-label">Nội dung cần tư vấn</label>
                        <textarea name="message" rows="6" class="form-control contact-input contact-textarea" placeholder="Mô tả nhu cầu, vấn đề hoặc mục tiêu của bạn"></textarea>
                    </div>
                    <div class="col-12 d-flex flex-wrap gap-2 align-items-center">
                        <button type="submit" class="btn btn-contact-primary">Gửi liên hệ</button>
                        <span class="contact-form-hint">Agenbee sẽ phản hồi qua số điện thoại hoặc kênh bạn cung cấp.</span>
                    </div>
                </div>
            </form>
        </div>

        <aside class="contact-info-panel">
            <div class="contact-panel-head">
                <p class="contact-panel-label mb-1">Liên hệ trực tiếp</p>
                <h2 class="contact-panel-title mb-0">Kết nối ngay với Agenbee</h2>
            </div>

            <div class="contact-channel-list">
                <a href="#" class="contact-channel-card">
                    <span class="contact-channel-icon"><i class="bi bi-envelope-fill"></i></span>
                    <div>
                        <span class="contact-channel-name">Email</span>
                        <strong><?php echo _HOST_MAIL; ?></strong>
                    </div>
                </a>

                <a href="https://www.facebook.com/profile.php?id=61575480643824&locale=vi_VN" target="_blank" rel="noopener" class="contact-channel-card">
                    <span class="contact-channel-icon"><i class="bi bi-facebook"></i></span>
                    <div>
                        <span class="contact-channel-name">Facebook</span>
                        <strong>Agenbee</strong>
                    </div>
                </a>

                <a href="#" target="_blank" rel="noopener" class="contact-channel-card">
                    <span class="contact-channel-icon"><i class="bi bi-chat-dots-fill"></i></span>
                    <div>
                        <span class="contact-channel-name">Zalo</span>
                        <strong><?php echo _PHONE; ?></strong>
                    </div>
                </a>

                <a href="#" target="_blank" rel="noopener" class="contact-channel-card">
                    <span class="contact-channel-icon"><i class="bi bi-music-note-beamed"></i></span>
                    <div>
                        <span class="contact-channel-name">TikTok</span>
                        <strong>@agenbee</strong>
                    </div>
                </a>
            </div>

            <div class="contact-quick-box">
                <p class="mb-2"><strong>Ghi chú</strong></p>
                <p class="mb-0">Bạn chỉ cần chọn đúng tình trạng shop và ngân sách dự kiến. Phần tư vấn sẽ được Agenbee cá nhân hóa theo nhu cầu của bạn.</p>
            </div>
        </aside>
    </section>
</main>
<?php
layout('footer');
?>