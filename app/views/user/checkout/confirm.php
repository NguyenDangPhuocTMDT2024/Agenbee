<?php
$data = [
    'title' => 'Giỏ hàng',
];
if (isset($user)) {
    $data['user'] = $user;
}
if (isset($cartItemCount)) {
    $data['cartItemCount'] = $cartItemCount;
}
layout('sidebar', $data);
layout('header', $data);

$msg = getSessionFlash('msg');
$msgType = getSessionFlash('msg_type');
?>



<?php 
layout('footer');
?>