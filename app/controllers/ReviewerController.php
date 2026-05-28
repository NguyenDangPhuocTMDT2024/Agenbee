<?php

class ReviewerController extends Controller
{
    private $userModel;
    private $cartModel;
    private $reviewerProfileModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->reviewerProfileModel = new ReviewerProfile();

        if (!isLoginStrict($this->userModel)) {
            setSessionFlash('msg', 'Vui lòng đăng nhập để truy cập Reviewer.');
            setSessionFlash('msg_type', 'danger');
            redirect('/login');
        }

        $userId = getSession('user_id');
        $userRole = getSession('user_role');

        if ($userRole !== 'reviewer') {
            setSessionFlash('msg', 'Bạn không có quyền truy cập trang này');
            setSessionFlash('msg_type', 'danger');
            redirect('/home');
        }

        $this->cartModel = new Cart($userId);
    }

    public function jobBoard()
    {
        $filters = filterData('get');
        $search = isset($filters['search']) ? trim($filters['search']) : '';
        $category = isset($filters['category']) ? trim($filters['category']) : '';

        $jobList = $this->getSampleJobs();
        $categories = $this->getJobCategories();

        if (!empty($search)) {
            $jobList = array_filter($jobList, function ($job) use ($search) {
                return stripos($job['campaign_name'], $search) !== false
                    || stripos($job['product_name'], $search) !== false
                    || stripos($job['shop_name'], $search) !== false;
            });
        }

        if (!empty($category)) {
            $jobList = array_filter($jobList, function ($job) use ($category) {
                return $job['category'] === $category;
            });
        }

        $data = [
            'title' => 'Job Board',
            'search' => $search,
            'selectedCategory' => $category,
            'categories' => $categories,
            'jobs' => array_values($jobList),
        ];

        if (isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data['user'] = $this->userModel->getUserById($id);
            $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);
        }

        $this->renderView('reviewer/job_board', $data);
    }

    public function jobDetail()
    {
        $filters = filterData('get');
        $jobId = isset($filters['id']) ? trim($filters['id']) : '';
        $job = null;

        foreach ($this->getSampleJobs() as $item) {
            if ($item['id'] === $jobId) {
                $job = $item;
                break;
            }
        }

        if (empty($job)) {
            setSessionFlash('msg', 'Không tìm thấy công việc này.');
            setSessionFlash('msg_type', 'danger');
            redirect('/reviewer/job-board');
        }

        $msg = '';
        $msgType = 'success';
        if (isPost()) {
            $post = filterData('post');
            $channelLink = isset($post['channel_link']) ? trim($post['channel_link']) : '';
            $followers = isset($post['followers']) ? trim($post['followers']) : '';

            if (empty($channelLink) || empty($followers)) {
                setSessionFlash('msg', 'Vui lòng điền đầy đủ thông tin để ứng tuyển.');
                setSessionFlash('msg_type', 'danger');
            } else {
                setSessionFlash('msg', 'Ứng tuyển thành công! Ticket đang chờ duyệt bởi Admin.');
                setSessionFlash('msg_type', 'success');
            }

            redirect('/reviewer/job-detail?id=' . urlencode($jobId));
        }

        if (isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data['user'] = $this->userModel->getUserById($id);
            $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);
        }

        $data = [
            'title' => 'Chi tiết Job',
            'job' => $job,
            'msg' => getSessionFlash('msg'),
            'msgType' => getSessionFlash('msg_type') ?: 'success',
        ];

        if (isset($this->userModel) && isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data['user'] = $this->userModel->getUserById($id);
            $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);
        }

        $this->renderView('reviewer/job_detail', $data);
    }

    public function workspace()
    {
        $reviewerId = getSession('user_id');
        if (!isset($_SESSION['reviewer_workspace'][$reviewerId])) {
            $_SESSION['reviewer_workspace'][$reviewerId] = [
                'active' => [
                    [
                        'id' => 'task-201',
                        'job_name' => 'Review Serum Dưỡng Da Mịn Màng',
                        'deadline' => '2026-06-10 23:59:59',
                        'status' => 'Đang quay',
                        'fee' => '500000',
                        'description' => 'Quay video review serum 7 ngày, nêu rõ chất lượng và cảm nhận khi sử dụng.',
                    ],
                    [
                        'id' => 'task-202',
                        'job_name' => 'Review Smartwatch X7',
                        'deadline' => '2026-06-14 20:00:00',
                        'status' => 'Chờ duyệt minh chứng',
                        'fee' => '750000',
                        'description' => 'Gửi video giới thiệu smartwatch, nhấn mạnh pin 7 ngày, GPS và đo nhịp tim.',
                    ],
                ],
                'history' => [
                    [
                        'id' => 'task-101',
                        'job_name' => 'Review Bộ Nồi Inox 5 Món',
                        'paid_amount' => '450000',
                        'review_note' => 'Video chất lượng tốt, đúng hạn - 5 sao',
                        'completed_at' => '2026-05-25',
                    ],
                    [
                        'id' => 'task-102',
                        'job_name' => 'Review Đồng Hồ Thông Minh',
                        'paid_amount' => '750000',
                        'review_note' => 'Nội dung rõ ràng, sáng tạo - 5 sao',
                        'completed_at' => '2026-05-15',
                    ],
                ],
            ];
        }

        if (isPost()) {
            $post = filterData('post');
            $jobId = trim($post['job_id'] ?? '');
            $action = trim($post['action'] ?? '');

            if ($action === 'cancel' && $jobId !== '') {
                foreach ($_SESSION['reviewer_workspace'][$reviewerId]['active'] as &$task) {
                    if ($task['id'] === $jobId) {
                        $task['status'] = 'Chờ xác nhận hủy';
                        break;
                    }
                }
                unset($task);
                setSessionFlash('msg', 'Vui lòng liên hệ Admin qua Zalo để xác nhận hủy và hoàn trả sản phẩm mẫu.');
                setSessionFlash('msg_type', 'warning');
            }

            if ($action === 'submit' && $jobId !== '') {
                $videoLink = trim($post['video_link'] ?? '');
                $screenshot = isset($_FILES['screenshot']) ? $_FILES['screenshot'] : null;

                foreach ($_SESSION['reviewer_workspace'][$reviewerId]['active'] as &$task) {
                    if ($task['id'] === $jobId) {
                        $task['status'] = 'Chờ duyệt minh chứng';
                        $task['submission'] = [
                            'video_link' => $videoLink,
                            'uploaded' => !empty($screenshot['name']),
                        ];
                        break;
                    }
                }
                unset($task);

                setSessionFlash('msg', 'Nộp sản phẩm thành công. Job được chuyển sang trạng thái chờ duyệt.');
                setSessionFlash('msg_type', 'success');
            }

            redirect('/reviewer/workspace');
        }

        $workspaceData = $_SESSION['reviewer_workspace'][$reviewerId];
        $data = [
            'title' => 'Workspace',
            'workspace' => $workspaceData,
            'msg' => getSessionFlash('msg'),
            'msgType' => getSessionFlash('msg_type') ?: 'success',
        ];

        $id = getSession('user_id');
        $data['user'] = $this->userModel->getUserById($id);
        $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);

        $this->renderView('reviewer/reviewer-workspace', $data);
    }

    public function showProfile()
    {
        $reviewerId = getSession('user_id');
        $reviewerProfile = $this->reviewerProfileModel->getProfileByReviewerId($reviewerId);

        $data = [
            'title' => 'Profile',
            'reviewerProfile' => $reviewerProfile ?: [],
            'errors' => getSessionFlash('errors') ?: [],
            'oldData' => getSessionFlash('old_data') ?: [],
            'msg' => getSessionFlash('msg'),
            'msgType' => getSessionFlash('msg_type') ?: 'success',
        ];

        $id = getSession('user_id');
        $data['user'] = $this->userModel->getUserById($id);
        $data['cartItemCount'] = $this->cartModel->countItemsInCart($id);

        $this->renderView('reviewer/profile', $data);
    }

    public function updateProfile()
    {
        if (isPost()) {
            $post = filterData('post');
            $errors = [];

            $name = trim($post['name'] ?? '');
            $gender = trim($post['gender'] ?? '');
            $age = trim($post['age'] ?? '');
            $email = trim($post['email'] ?? '');
            $address = trim($post['address'] ?? '');
            $followers = trim($post['followers'] ?? '');
            $averageViews = trim($post['average_views'] ?? '');
            $pricing = trim($post['pricing'] ?? '');
            $categories = isset($post['categories']) && is_array($post['categories']) ? $post['categories'] : [];
            $tiktok = trim($post['tiktok'] ?? '');
            $instagram = trim($post['instagram'] ?? '');
            $facebook = trim($post['facebook'] ?? '');
            $engagementRate = trim($post['engagement_rate'] ?? '');

            if (empty($name)) {
                $errors['name'] = 'Tên không được để trống';
            }
            if (empty($email) || validateEmail($email) !== true) {
                $errors['email'] = validateEmail($email);
            }
            if (empty($gender) || !in_array($gender, ['male', 'female', 'other'], true)) {
                $errors['gender'] = 'Vui lòng chọn giới tính hợp lệ';
            }
            if (empty($age) || !ctype_digit($age) || (int)$age < 13) {
                $errors['age'] = 'Tuổi phải là số nguyên lớn hơn hoặc bằng 13';
            }
            if ($followers === '' || !ctype_digit($followers) || (int)$followers < 0) {
                $errors['followers'] = 'Followers phải là số nguyên không âm';
            }
            if ($engagementRate === '' || !is_numeric(str_replace([',', ' '], ['', ''], $engagementRate)) || (float)str_replace([',', ' '], ['', ''], $engagementRate) < 0) {
                $errors['engagement_rate'] = 'Tỷ lệ tương tác phải là số không âm';
            }
            if ($pricing === '' || !is_numeric(str_replace([',', ' '], ['', ''], $pricing)) || (float)str_replace([',', ' '], ['', ''], $pricing) < 0) {
                $errors['pricing'] = 'Giá phải là số hợp lệ lớn hơn hoặc bằng 0';
            }

            if (empty($errors)) {
                $reviewerId = getSession('user_id');
                $profileData = [
                    'name' => $name,
                    'gender' => $gender,
                    'age' => (int)$age,
                    'email' => $email,
                    'address' => $address,
                    'followers' => (int)$followers,
                    'average_views' => (int)$averageViews,
                    'pricing' => number_format((float)str_replace([',', ' '], ['', ''], $pricing), 2, '.', ''),
                    'categories' => implode(',', array_map('trim', $categories)),
                    'tiktok' => $tiktok,
                    'instagram' => $instagram,
                    'facebook' => $facebook,
                    'engagement_rate' => (float)str_replace([',', ' '], ['', ''], $engagementRate),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $existing = $this->reviewerProfileModel->getProfileByReviewerId($reviewerId);
                $saveStatus = false;
                if (!empty($existing)) {
                    $saveStatus = $this->reviewerProfileModel->updateProfileByReviewerId($reviewerId, $profileData);
                } else {
                    $profileData['reviewer_id'] = $reviewerId;
                    $saveStatus = $this->reviewerProfileModel->createProfile($profileData);
                }

                if ($saveStatus) {
                    setSessionFlash('msg', 'Hồ sơ reviewer đã được lưu thành công.');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Lưu hồ sơ reviewer thất bại. Vui lòng thử lại.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng sửa lỗi trước khi lưu.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('errors', $errors);
                setSessionFlash('old_data', $post);
            }
        }

        redirect('/reviewer/profile');
    }

    private function getSampleJobs()
    {
        return [
            [
                'id' => 'job-101',
                'campaign_name' => 'Săn Sale 11.11',
                'product_name' => 'Serum Dưỡng Da Mịn Màng',
                'shop_name' => 'Glow Beauty',
                'rating' => '4.8',
                'category' => 'Mỹ phẩm',
                'fee' => '500000',
                'earn_type' => 'Nhận sản phẩm + 10% Affiliate',
                'brief' => 'Tạo video review tự nhiên, trải nghiệm quy trình sử dụng serum 7 ngày và đánh giá hiệu quả dưỡng ẩm.',
                'requirements' => ['Video 30-60 giây', 'Caption kèm hashtag #GlowBeauty', 'Mention ưu đãi miễn phí ship'],
                'deadline' => '2026-06-15',
                'product_info' => 'Serum chống oxi hóa, dưỡng ẩm sâu, phù hợp da nhạy cảm.',
            ],
            [
                'id' => 'job-102',
                'campaign_name' => 'Launch Đồng Hồ Thông Minh',
                'product_name' => 'Smartwatch X7',
                'shop_name' => 'TechStyle',
                'rating' => '4.6',
                'category' => 'Công nghệ',
                'fee' => '750000',
                'earn_type' => 'Cộng tác + Affiliate 12%',
                'brief' => 'Giới thiệu các tính năng theo dõi sức khỏe và thiết kế thời trang của Smartwatch X7.',
                'requirements' => ['Video tối thiểu 45 giây', 'Nội dung đánh giá chính xác', 'Chèn link affiliate'],
                'deadline' => '2026-06-20',
                'product_info' => 'Màn hình AMOLED, pin 7 ngày, đo nhịp tim, GPS tích hợp.',
            ],
            [
                'id' => 'job-103',
                'campaign_name' => 'Tết Sáng Bừng Nhà Mới',
                'product_name' => 'Bộ nồi inox 5 món',
                'shop_name' => 'HomePro',
                'rating' => '4.9',
                'category' => 'Gia dụng',
                'fee' => '450000',
                'earn_type' => 'Nhận sản phẩm + 8% Affiliate',
                'brief' => 'Gợi ý cách nấu ngon với bộ nồi inox, nhấn mạnh chất lượng và tính thẩm mỹ.',
                'requirements' => ['Video lifestyle 45-60 giây', 'Tag shop HomePro', 'Đưa ra 2 ưu điểm sản phẩm'],
                'deadline' => '2026-06-25',
                'product_info' => 'Bộ nồi 5 đáy, an toàn cho mọi loại bếp, dễ dàng vệ sinh.',
            ],
        ];
    }

    private function getJobCategories()
    {
        return ['Mỹ phẩm', 'Công nghệ', 'Gia dụng'];
    }
}
