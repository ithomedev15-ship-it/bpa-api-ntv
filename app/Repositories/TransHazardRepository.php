<?php

namespace App\Repositories;

use PDO;
use App\Database\Connection;
use App\Interfaces\TransHazardRepositoryInterface;

class TransHazardRepository implements TransHazardRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get('bpa');
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT
                KODE_HAZ,
                TANGGAL,
                WAKTU,
                KODE_HAZ_KATEGORI,
                KODE_PROGRESS,
                PIC,
                DESKRIPSI,
                DEADLINE,
                FLAG_STATUS
            FROM SFT_TRANS_HAZ
            ORDER BY TANGGAL DESC, WAKTU DESC
        ");

        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO SFT_TRANS_HAZ (
                KODE_HAZ,
                TANGGAL,
                WAKTU,
                KODE_HAZ_KATEGORI,
                KODE_PROGRESS,
                PIC,
                DESKRIPSI,
                DEADLINE,
                FLAG_STATUS,
                LOG_ENTRY_NAME,
                LOG_ENTRY_DATE
            ) VALUES (
                :kode_haz,
                :tanggal,
                :waktu,
                :kode_haz_kategori,
                :kode_progress,
                :pic,
                :deskripsi,
                :deadline,
                :flag_status,
                :log_entry_name,
                NOW()
            )
        ");

        return $stmt->execute([
            'kode_haz'          => $data['kode_haz'],
            'tanggal'           => $data['tanggal'],
            'waktu'             => $data['waktu'],
            'kode_haz_kategori' => $data['kode_haz_kategori'],
            'kode_progress'     => $data['kode_progress'],
            'pic'               => $data['pic'],
            'deskripsi'         => $data['deskripsi'],
            'deadline'          => $data['deadline'],
            'flag_status'       => $data['flag_status'],
            'log_entry_name'    => $data['log_entry_name'],
        ]);
    }

    public function begin(): void
    {
        $this->db->beginTransaction();
    }

    public function commit(): void
    {
        $this->db->commit();
    }

    public function rollback(): void
    {
        $this->db->rollBack();
    }

    public function findByKode(string $kodeHaz): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM SFT_TRANS_HAZ
            WHERE KODE_HAZ = :kode
            LIMIT 1
        ");
        $stmt->execute(['kode' => $kodeHaz]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function update(string $kodeHaz, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE SFT_TRANS_HAZ
            SET
                KODE_HAZ_KATEGORI = :kategori,
                KODE_PROGRESS     = :progress,
                PIC               = :pic,
                DESKRIPSI         = :deskripsi,
                DEADLINE          = :deadline,
                LOG_EDIT_NAME   = :user,
                LOG_EDIT_DATE   = NOW()
            WHERE KODE_HAZ = :kode
        ");

        return $stmt->execute([
            'kategori' => $data['kode_haz_kategori'],
            'progress' => $data['kode_progress'],
            'pic'      => $data['pic'],
            'deskripsi'=> $data['deskripsi'],
            'deadline' => $data['deadline'],
            'user'     => $data['user'],
            'kode'     => $kodeHaz
        ]);
    }

}