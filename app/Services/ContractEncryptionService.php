<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractEncryptionService
{
    private const CIPHER = 'AES-256-CBC';

    public function generateKey(string $tenantId): string
    {
        $appKey = config('app.key');
        if (str_starts_with($appKey, 'base64:')) {
            $appKey = base64_decode(substr($appKey, 7));
        }

        return hash_hkdf('sha256', $appKey, 32, 'contract-encryption-' . $tenantId);
    }

    public function encrypt(string $data, string $tenantId): array
    {
        $key = $this->generateKey($tenantId);
        $ivLength = openssl_cipher_iv_length(self::CIPHER);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt($data, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        return [
            'encrypted' => base64_encode($iv . $encrypted),
            'key_hash' => hash('sha256', $key),
        ];
    }

    public function decrypt(string $encryptedData, string $keyHash): string
    {
        $decoded = base64_decode($encryptedData, true);
        if ($decoded === false) {
            throw new \RuntimeException('Invalid encrypted data format');
        }

        $ivLength = openssl_cipher_iv_length(self::CIPHER);
        $iv = substr($decoded, 0, $ivLength);
        $ciphertext = substr($decoded, $ivLength);

        $tenantId = TenantContext::get();
        if (!$tenantId) {
            throw new \RuntimeException('No tenant context for decryption');
        }

        $key = $this->generateKey($tenantId);
        $expectedHash = hash('sha256', $key);

        if (!hash_equals($expectedHash, $keyHash)) {
            throw new \RuntimeException('Encryption key mismatch');
        }

        $decrypted = openssl_decrypt($ciphertext, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new \RuntimeException('Decryption failed');
        }

        return $decrypted;
    }

    public function encryptFile(UploadedFile $file, string $tenantId): array
    {
        $contents = $file->get();
        return $this->encrypt($contents, $tenantId);
    }

    public function decryptFile(string $filePath, string $keyHash): string
    {
        // Try contracts disk first, fallback to default storage
        $encryptedData = Storage::disk('contracts')->get($filePath);
        if ($encryptedData === null) {
            $encryptedData = Storage::get($filePath);
        }
        if ($encryptedData === null) {
            throw new \RuntimeException('File not found');
        }

        return $this->decrypt($encryptedData, $keyHash);
    }
}
