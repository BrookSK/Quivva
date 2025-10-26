<?php
namespace App\models;

use App\core\Model;

class Lead extends Model
{
    protected string $table = 'leads';

    public function findByPhone(int $companyId, string $phone): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE company_id = :cid AND phone = :phone LIMIT 1");
        $stmt->execute(['cid' => $companyId, 'phone' => $phone]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function byStatus(int $companyId, string $status): array
    {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE company_id = :cid AND chat_status = :st ORDER BY created_at DESC");
        $stmt->execute(['cid' => $companyId, 'st' => $status]);
        return $stmt->fetchAll();
    }
}
