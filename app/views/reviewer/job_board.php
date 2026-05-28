<?php
$data = [
    'title' => 'Job Board',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('reviewer-header', $data);
layout('reviewer-sidebar', $data);

$searchValue = isset($search) ? $search : '';
$selectedCategory = isset($selectedCategory) ? $selectedCategory : '';
$categories = $categories ?? [];
$jobs = $jobs ?? [];
?>
<style>
    .reviewer-page {
        min-height: 100vh;
    }
    .reviewer-search-bar {
        border-radius: 18px;
        border: 1px solid #dcdcdc;
        padding: 1rem;
        background: #ffffff;
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.04);
    }
    .reviewer-filter-btn {
        border-radius: 999px;
        border: 1px solid rgba(17, 17, 17, 0.12);
        color: #333;
        background: #fff;
        white-space: nowrap;
        transition: all .2s ease;
    }
    .reviewer-filter-btn.active,
    .reviewer-filter-btn:hover {
        background: #f4c430;
        color: #111;
        border-color: #f4c430;
    }
    .job-card {
        border-radius: 22px;
        border: 1px solid rgba(17, 17, 17, 0.08);
        background: linear-gradient(180deg, #ffffff 0%, #fffdf8 100%);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .job-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 34px rgba(17, 17, 17, 0.08);
    }
    .job-badge {
        background: rgba(244, 196, 48, 0.16);
        color: #9b7500;
        font-weight: 700;
        border-radius: 999px;
        padding: 0.35rem 0.9rem;
    }
    .job-card-title {
        min-height: 3.8rem;
    }
    .job-card-footer a {
        border-radius: 999px;
    }
</style>
<main class="reviewer-page flex-grow-1 px-3 px-md-4 py-4">
    <section class="container-fluid px-0">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <p class="text-uppercase text-secondary mb-1">KOC Reviewer</p>
                <h1 class="h2 mb-0">Job Board</h1>
                <p class="text-muted">Tìm kiếm cơ hội kiếm tiền qua chiến dịch mới và ứng tuyển nhanh.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?php echo _HOST_URL ?>/reviewer/job-board" class="btn btn-outline-secondary">Làm mới</a>
            </div>
        </div>

        <div class="reviewer-search-bar mb-4">
            <form method="get" action="<?php echo _HOST_URL ?>/reviewer/job-board">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-6">
                        <label class="form-label mb-2">Tìm kiếm theo chiến dịch / sản phẩm</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Nhập tên chiến dịch hoặc tên sản phẩm" value="<?php echo htmlspecialchars($searchValue); ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-2">Ngành hàng</label>
                        <select name="category" class="form-select">
                            <option value="">Tất cả ngành hàng</option>
                            <?php foreach ($categories as $categoryItem): ?>
                                <option value="<?php echo htmlspecialchars($categoryItem); ?>" <?php echo ($selectedCategory === $categoryItem) ? 'selected' : ''; ?>><?php echo htmlspecialchars($categoryItem); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row g-4">
            <?php if (empty($jobs)): ?>
                <div class="col-12">
                    <div class="alert alert-warning">Không tìm thấy công việc phù hợp với bộ lọc hiện tại.</div>
                </div>
            <?php endif; ?>
            <?php foreach ($jobs as $job): ?>
                <div class="col-12 col-lg-6">
                    <article class="card job-card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                                <div>
                                    <h2 class="h5 job-card-title mb-1"><?php echo htmlspecialchars($job['campaign_name']); ?></h2>
                                    <p class="mb-1 text-secondary"><?php echo htmlspecialchars($job['product_name']); ?></p>
                                </div>
                                <span class="job-badge"><?php echo htmlspecialchars($job['category']); ?></span>
                            </div>

                            <div class="d-flex gap-3 flex-wrap">
                                <div class="badge bg-light text-dark py-2 px-3">
                                    <i class="bi bi-shop me-1"></i> <?php echo htmlspecialchars($job['shop_name']); ?>
                                </div>
                                <div class="badge bg-light text-dark py-2 px-3">
                                    <i class="bi bi-star-fill text-warning"></i> <?php echo htmlspecialchars($job['rating']); ?>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <div>
                                    <span class="fw-semibold fs-5"><?php echo number_format($job['fee']); ?> VNĐ</span>
                                </div>
                                <div class="text-secondary"><?php echo htmlspecialchars($job['earn_type']); ?></div>
                            </div>

                            <p class="mb-0 text-muted"><?php echo htmlspecialchars($job['brief']); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex align-items-center justify-content-between gap-2 flex-wrap pt-0 pb-3">
                            <a href="<?php echo _HOST_URL ?>/reviewer/job-detail?id=<?php echo urlencode($job['id']); ?>" class="btn btn-outline-primary">Xem chi tiết</a>
                            <a href="<?php echo _HOST_URL ?>/reviewer/job-detail?id=<?php echo urlencode($job['id']); ?>" class="btn btn-primary">Ứng tuyển ngay</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php layout('reviewer-footer'); ?>
