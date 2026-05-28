<?php
$data = [
    'title' => 'Chi tiết Job',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('reviewer-header', $data);
layout('reviewer-sidebar', $data);

$job = $job ?? [];
$msg = $msg ?? '';
$msgType = $msgType ?? 'success';
?>
<style>
    .reviewer-detail {
        min-height: 100vh;
    }
    .job-summary-card {
        border-radius: 24px;
        border: 1px solid rgba(17, 17, 17, 0.08);
        background: #ffffff;
        box-shadow: 0 18px 30px rgba(17, 17, 17, 0.05);
    }
    .job-info-label {
        font-weight: 700;
        color: #4e4e4e;
    }
    .apply-card {
        border-radius: 24px;
        border: 1px solid rgba(244, 196, 48, 0.25);
        background: linear-gradient(180deg, #fffef7 0%, #fff7df 100%);
    }
    .tag-pill {
        display: inline-flex;
        gap: 0.35rem;
        align-items: center;
        border-radius: 999px;
        background: rgba(244, 196, 48, 0.2);
        color: #7a5d00;
        padding: 0.4rem 0.85rem;
        margin-right: .6rem;
        margin-bottom: .6rem;
    }
</style>
<main class="reviewer-detail flex-grow-1 px-3 px-md-4 py-4">
    <section class="container-fluid px-0">
        <div class="mb-4">
            <a href="<?php echo _HOST_URL ?>/reviewer/job-board" class="text-decoration-none text-secondary"><i class="bi bi-arrow-left"></i> Quay lại Job Board</a>
        </div>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-<?php echo htmlspecialchars($msgType); ?>"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-12 col-xl-7">
                <article class="job-summary-card p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mb-4">
                        <div>
                            <p class="text-uppercase text-secondary mb-1"><?php echo htmlspecialchars($job['category']); ?></p>
                            <h1 class="h3 mb-2"><?php echo htmlspecialchars($job['campaign_name']); ?></h1>
                            <p class="mb-2 text-muted"><?php echo htmlspecialchars($job['product_name']); ?></p>
                        </div>
                        <div class="text-end">
                            <div class="fs-5 fw-semibold text-dark mb-2"><?php echo number_format($job['fee']); ?> VNĐ</div>
                            <div class="badge bg-warning text-dark py-2 px-3"><?php echo htmlspecialchars($job['earn_type']); ?></div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <p class="job-info-label mb-1">Shop</p>
                            <p class="mb-0"><?php echo htmlspecialchars($job['shop_name']); ?></p>
                        </div>
                        <div class="col-3">
                            <p class="job-info-label mb-1">Đánh giá</p>
                            <p class="mb-0"><?php echo htmlspecialchars($job['rating']); ?> <i class="bi bi-star-fill text-warning"></i></p>
                        </div>
                        <div class="col-3">
                            <p class="job-info-label mb-1">Deadline</p>
                            <p class="mb-0"><?php echo htmlspecialchars($job['deadline']); ?></p>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="mb-4">
                        <h2 class="h5 mb-3">Brief chiến dịch</h2>
                        <p class="mb-0 text-secondary"><?php echo htmlspecialchars($job['brief']); ?></p>
                    </div>

                    <div class="mb-4">
                        <h2 class="h5 mb-3">Yêu cầu công việc</h2>
                        <ul class="mb-0">
                            <?php foreach ($job['requirements'] as $requirement): ?>
                                <li><?php echo htmlspecialchars($requirement); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div>
                        <h2 class="h5 mb-3">Thông số sản phẩm</h2>
                        <p class="mb-0 text-secondary"><?php echo htmlspecialchars($job['product_info']); ?></p>
                    </div>
                </article>
            </div>

            <div class="col-12 col-xl-5">
                <article class="apply-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <p class="text-secondary mb-2">Ứng tuyển nhanh</p>
                        <h2 class="h5 mb-4">Gửi thông tin của bạn</h2>
                        <form method="post" action="<?php echo _HOST_URL ?>/reviewer/job-detail?id=<?php echo urlencode($job['id']); ?>">
                            <div class="mb-3">
                                <label class="form-label">Link kênh TikTok</label>
                                <input type="url" name="channel_link" class="form-control" placeholder="https://www.tiktok.com/@username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số follower hiện tại</label>
                                <input type="text" name="followers" class="form-control" placeholder="Ví dụ: 25.000" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gợi ý / Lý do ứng tuyển</label>
                                <textarea name="note" class="form-control" rows="4" placeholder="Nêu ngắn gọn vì sao bạn phù hợp"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>
                    <div class="mt-4">
                        <p class="text-secondary mb-2">Ưu điểm khi ứng tuyển</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="tag-pill">Xử lý nhanh</span>
                            <span class="tag-pill">Quy trình rõ ràng</span>
                            <span class="tag-pill">Hỗ trợ từ Admin</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</main>
<?php layout('reviewer-footer'); ?>
