<?php

namespace App\Repositories;

use App\Database\Connection;
use App\Interfaces\TransSafetyTalkRepositoryInterface;
use PDO;

class TransSafetyTalkRepository implements TransSafetyTalkRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::get('bpa');
    }

    public function getAll(array $filter = []): array
    {
        $sql = "
            SELECT
                KODE_STALK,
                TANGGAL,
                WAKTU,
                PEMATERI,
                TOPIK,
                LOKASI,
                NOTE,
                HASHTAG,
                FLAG_STATUS,
                LOG_ENTRY_DATE
            FROM SFT_TRANS_STK
            WHERE FLAG_STATUS = 1
            ORDER BY TANGGAL DESC, WAKTU DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO SFT_TRANS_STK (
                KODE_STALK,
                TANGGAL,
                WAKTU,
                PEMATERI,
                TOPIK,
                LOKASI,
                NOTE,
                HASHTAG,
                FLAG_STATUS,
                LOG_ENTRY_NAME,
                LOG_ENTRY_DATE,
                UUID
            ) VALUES (
                :kode,
                :tanggal,
                :waktu,
                :pemateri,
                :topik,
                :lokasi,
                :note,
                :hashtag,
                1,
                :entry_name,
                NOW(),
                :uuid
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'kode'       => $data['kode'],
            'tanggal'    => $data['tanggal'],
            'waktu'      => $data['waktu'],
            'pemateri'   => $data['pemateri'],
            'topik'      => $data['topik'],
            'lokasi'     => $data['lokasi'],
            'note'       => $data['note'] ?? null,
            'hashtag'    => $data['hashtag'] ?? null,
            'entry_name' => $data['entry_name'],
            'uuid'       => $data['uuid'],
        ]);
    }

    public function update(string $kodeStalk, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE SFT_TRANS_STK
            SET
                TANGGAL = :tanggal,
                WAKTU   = :waktu,
                PEMATERI = :pemateri,
                TOPIK   = :topik,
                LOKASI  = :lokasi,
                NOTE    = :note,
                HASHTAG = :hashtag,
                LOG_EDIT_NAME = :user,
                LOG_EDIT_DATE = NOW()
            WHERE KODE_STALK = :kode
            AND FLAG_STATUS = 1
        ");

        return $stmt->execute([
            'kode'     => $kodeStalk, // ✅ ADA
            'tanggal'  => $data['tanggal'],
            'waktu'    => $data['waktu'],
            'pemateri' => $data['pemateri'],
            'topik'    => $data['topik'],
            'lokasi'   => $data['lokasi'],
            'note'     => $data['note'],
            'hashtag'  => $data['hashtag'],
            'user'     => $data['username'],
        ]);
    }

    public function delete(string $kodeStalk): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM SFT_TRANS_STK
            WHERE KODE_STALK = :kode
        ");

        return $stmt->execute([
            'kode' => $kodeStalk
        ]);
    }


}
