<?php
$data = [
    'title' => 'Workspace',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
layout('reviewer-header', $data);
layout('reviewer-sidebar', $data);
?>

<main class="reviewer-page flex-grow-1 px-3 px-md-4 py-4">
    <section class="container-fluid px-0">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <p class="text-uppercase text-secondary mb-1">Workspace</p>
                <h1 class="h2 mb-0">Quản lý tiến độ</h1>
                <p class="text-muted">Theo dõi task đang làm và lịch sử job đã nhận.</p>
            </div>
        </div>

        <?php if (!empty($msg)): ?>
            <div class="mb-3"><?php echo showMsg($msg, $msgType); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="btn-group" role="group" aria-label="Workspace tabs">
                    <button type="button" class="btn btn-outline-primary active" data-tab="active-jobs">Task đang làm</button>
                    <button type="button" class="btn btn-outline-primary" data-tab="history-jobs">Task đã làm</button>
                </div>
            </div>
        </div>

        <div id="active-jobs" class="workspace-tab-content">
            <div class="row gy-4">
                <?php if (!empty($workspace['active'])): ?>
                    <?php foreach ($workspace['active'] as $task): ?>
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                        <div>
                                            <h5 class="mb-1"><?php echo htmlspecialchars($task['job_name']); ?></h5>
                                            <p class="mb-2 text-muted"><?php echo htmlspecialchars($task['description']); ?></p>
                                            <span class="badge bg-info text-dark me-2"><?php echo htmlspecialchars($task['status']); ?></span>
                                            <span class="badge bg-secondary">Deadline: <span class="deadline-countdown" data-deadline="<?php echo htmlspecialchars($task['deadline']); ?>"></span></span>
                                        </div>
                                        <div class="text-end">
                                            <p class="mb-1 text-muted">Giá nhận: <strong><?php echo number_format((float)$task['fee'], 0, ',', '.'); ?> VND</strong></p>
                                            <button type="button" class="btn btn-outline-danger btn-sm me-2 btn-cancel-task" data-job-id="<?php echo htmlspecialchars($task['id']); ?>">Hủy Job</button>
                                            <button type="button" class="btn btn-primary btn-sm btn-toggle-submit" data-job-id="<?php echo htmlspecialchars($task['id']); ?>">Nộp sản phẩm</button>
                                        </div>
                                    </div>

                                    <div class="task-submit-form mt-4 d-none" id="submit-<?php echo htmlspecialchars($task['id']); ?>">
                                        <form method="post" action="<?php echo _HOST_URL; ?>/reviewer/workspace" enctype="multipart/form-data">
                                            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                                            <input type="hidden" name="action" value="submit">
                                            <div class="mb-3">
                                                <label class="form-label">Link Video TikTok đã lên sóng</label>
                                                <input type="url" name="video_link" class="form-control" placeholder="https://www.tiktok.com/@..." required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Upload ảnh chụp màn hình tương tác</label>
                                                <input type="file" name="screenshot" class="form-control" accept="image/*" required>
                                            </div>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-secondary">Không có task đang làm.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="history-jobs" class="workspace-tab-content d-none">
            <div class="row gy-4">
                <?php if (!empty($workspace['history'])): ?>
                    <?php foreach ($workspace['history'] as $task): ?>
                        <div class="col-12">
                            <div class="card shadow-sm border-start border-4 border-success">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                        <div>
                                            <h5 class="mb-1"><?php echo htmlspecialchars($task['job_name']); ?></h5>
                                            <p class="mb-1 text-muted">Hoàn thành: <?php echo htmlspecialchars($task['completed_at']); ?></p>
                                            <p class="mb-1">Đánh giá Admin: <strong><?php echo htmlspecialchars($task['review_note']); ?></strong></p>
                                        </div>
                                        <div class="text-end">
                                            <p class="mb-1 text-success">Đã nhận: <strong><?php echo number_format((float)$task['paid_amount'], 0, ',', '.'); ?> VND</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-secondary">Không có lịch sử task.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<form id="workspace-action-form" method="post" action="<?php echo _HOST_URL; ?>/reviewer/workspace" style="display:none;">
    <input type="hidden" name="job_id" value="">
    <input type="hidden" name="action" value="cancel">
</form>

<?php layout('reviewer-footer', $data); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[data-tab]');
    const tabPanels = document.querySelectorAll('.workspace-tab-content');
    const submitButtons = document.querySelectorAll('.btn-toggle-submit');
    const cancelButtons = document.querySelectorAll('.btn-cancel-task');
    const actionForm = document.getElementById('workspace-action-form');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            const target = button.getAttribute('data-tab');
            tabPanels.forEach(panel => {
                panel.classList.toggle('d-none', panel.id !== target);
            });
        });
    });

    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            const jobId = button.getAttribute('data-job-id');
            const form = document.getElementById('submit-' + jobId);
            if (form) {
                form.classList.toggle('d-none');
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const jobId = button.getAttribute('data-job-id');
            if (!jobId) return;
            if (!confirm('Bạn có chắc chắn muốn gửi yêu cầu hủy job này?')) {
                return;
            }
            actionForm.querySelector('[name="job_id"]').value = jobId;
            actionForm.submit();
        });
    });
});
</script>
