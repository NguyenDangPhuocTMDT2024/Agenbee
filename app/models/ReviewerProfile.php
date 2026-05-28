<?php

class ReviewerProfile extends Database
{
    private $tableName = 'reviewer_profiles';

    public function __construct()
    {
        parent::__construct();
    }

    public function getProfileByReviewerId($reviewerId)
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE reviewer_id = :reviewer_id LIMIT 1";
        return $this->getOne($sql, ['reviewer_id' => $reviewerId]);
    }

    public function createProfile($data)
    {
        $sql = "INSERT INTO " . $this->tableName . " (reviewer_id, name, gender, age, email, address, followers, average_views, pricing, categories, tiktok, instagram, facebook, engagement_rate, created_at, updated_at) VALUES (:reviewer_id, :name, :gender, :age, :email, :address, :followers, :average_views, :pricing, :categories, :tiktok, :instagram, :facebook, :engagement_rate, :created_at, :updated_at)";
        return $this->insert($sql, $data);
    }

    public function updateProfileByReviewerId($reviewerId, $data)
    {
        $sql = "UPDATE " . $this->tableName . " SET name = :name, gender = :gender, age = :age, email = :email, address = :address, followers = :followers, average_views = :average_views, pricing = :pricing, categories = :categories, tiktok = :tiktok, instagram = :instagram, facebook = :facebook, engagement_rate = :engagement_rate, updated_at = :updated_at WHERE reviewer_id = :reviewer_id";
        $data['reviewer_id'] = $reviewerId;
        return $this->update($sql, $data);
    }
}
