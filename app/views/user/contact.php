<?php
$data = [
    'title' => 'Gói dịch vụ',
];
if (isset($user)) {
    $data['user'] = $user;
}
layout('sidebar', $data);
layout('header', $data);
?>
<main class="px-3 px-md-4 py-4 flex-grow-1">
    
</main>
<?php
layout('footer');
?>