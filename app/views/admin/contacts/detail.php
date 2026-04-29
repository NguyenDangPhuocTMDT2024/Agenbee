<?php
$data = [
    'title' => 'Chi tiết liên hệ',
    'userInfo' => $userInfo,
    'activeMenu' => 'Contacts'
];
layout('admin-header', $data);
layout('admin-sidebar', $data);


?>
<main class="admin-main mt-3 mb-3 ms-3 me-3 flex-grow-1 bg-white">
    <div class="container">
        <h1 class="mb-4">Chi tiết liên hệ</h1>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><strong>Tên liên hệ:</strong> <?php echo $contactInfo['name']; ?></h5>
                <br>
                <br>
                <p class="card-text"><strong>Số điện thoại:</strong> <?php echo $contactInfo['phone']; ?></p>
                <p class="card-text"><strong>Trạng thái cửa hàng:</strong> <?php echo $contactInfo['shop_status']; ?></p>
                <p class="card-text"><strong>Khoảng ngân sách:</strong> <?php echo $contactInfo['budget_range']; ?></p>
                <p class="card-text"><strong>Trạng thái:</strong> <?php echo $contactInfo['status']; ?></p>
                <p class="card-text"><strong>Ngày tạo:</strong> <?php echo $contactInfo['created_at']; ?></p>
                <p class="card-text"><strong>Lời nhắn:</strong><br><?php echo nl2br($contactInfo['message']); ?></p>
                <a href="<?php echo _HOST_URL . '/admin/contact'; ?>" class="btn btn-secondary">Quay lại danh sách</a>
            </div>
        </div>
    </div>
</main>