<?php

namespace App\Services;

use App\Interfaces\SafetyTalkRepositoryInterface;
use App\Interfaces\TransSafetyTalkRepositoryInterface;
use App\Helpers\Uuid;
use Exception;

class TransSafetyTalkService
{
    private TransSafetyTalkRepositoryInterface $repo;

    public function __construct(TransSafetyTalkRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(): array
    {
        return $this->repo->getAll();
    }

    public function create(array $payload, string $username): void
    {
        // 🔐 DOUBLE GUARD (defensive programming)
        if (trim($username) === '') {
            throw new Exception('User tidak valid');
        }

        // ✅ Validasi field wajib
        $required = ['tanggal', 'waktu', 'pemateri', 'topik', 'lokasi'];

        foreach ($required as $field) {
            if (empty($payload[$field])) {
                throw new Exception("Field {$field} wajib diisi");
            }
        }

        // ✅ Generate kode safety talk
        $kode = 'STK-' . date('Ymd-His');

        $data = [
            'kode'       => $kode,
            'tanggal'    => $payload['tanggal'],
            'waktu'      => $payload['waktu'],
            'pemateri'   => strtoupper(trim($payload['pemateri'])),
            'topik'      => trim($payload['topik']),
            'lokasi'     => trim($payload['lokasi']),
            'note'       => $payload['note'] ?? null,
            'hashtag'    => $payload['hashtag'] ?? null,
            'entry_name' => $username,
            'uuid'       => Uuid::v4(),
        ];

        $this->repo->create($data);
    }

    public function update(string $kodeStalk, array $payload, string $username ): void 
    {
        if ($kodeStalk === '') {
            throw new \Exception('Kode Safety Talk wajib');
        }

        $this->repo->update($kodeStalk, [
            'tanggal'  => $payload['tanggal'],
            'waktu'    => $payload['waktu'],
            'pemateri' => strtoupper($payload['pemateri']),
            'topik'    => $payload['topik'],
            'lokasi'   => $payload['lokasi'],
            'note'     => $payload['note'] ?? null,
            'hashtag'  => $payload['hashtag'] ?? null,
            'username' => $username,
        ]);
    }

    public function delete(string $kodeStalk): void
    {
        if ($kodeStalk === '') {
            throw new \Exception('Kode Safety Talk wajib');
        }
        // 4️⃣ Hapus safety talk utama
        $this->repo->delete($kodeStalk);
    }


}
