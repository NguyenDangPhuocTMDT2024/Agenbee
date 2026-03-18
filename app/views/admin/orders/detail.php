<?php
$data = [
    'title' => 'Orders',
    'userInfo' => $userInfo
];
layout('admin-header', $data);
layout('admin-sidebar');

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
?>

<script>

</script>
<?php
layout('admin-footer');
?>