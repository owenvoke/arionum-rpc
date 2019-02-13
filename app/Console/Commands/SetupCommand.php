<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetupCommand extends Command
{
    public const KEYPAIR_ARGUMENTS = [
        'private_key_bits' => 4086,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
        'digest_alg' => 'sha512',
    ];

    protected $signature = 'rpc:setup';
    protected $description = 'The cron task for setting up the RPC keypair';

    public function handle(): void
    {
        $wantsEncrypt = $this->confirm('Would you like to encrypt the wallet?');

        $password = $wantsEncrypt ? $this->setEncryptionDetails() : null;

        $this->generateKeyPair($password);
    }

    private function generateKeyPair(?string $password): void
    {
        $keyPair = openssl_pkey_new(self::KEYPAIR_ARGUMENTS);

        openssl_pkey_export($keyPair, $privateKey);
        $publicKeyDetails = openssl_pkey_get_details($keyPair);
        $publicKey = $publicKeyDetails['key'];

        $this->output->note('We suggest to backup your private key: ');
        $this->output->write($privateKey);

        if ($password) {
            $cipher = 'aes-128-cbc';

            if (in_array($cipher, openssl_get_cipher_methods(), true)) {
                $key = hash('sha256', env('APP_KEY').$password);
                $this->output->write('Final Key: '.$key);
                $ivLength = openssl_cipher_iv_length($cipher);

                try {
                    $iv = random_bytes($ivLength);
                } catch (\Exception $exception) {
                    $this->error('IV generation failed');
                    return;
                }

                $privateKey = openssl_encrypt($privateKey, $cipher, $key, $options = 0, $iv);
                DB::table('wallet_configurations')->where('id', 'iv')->update(['value' => base64_encode($iv)]);
            }
        }

        DB::table('wallet_configurations')->where('id', 'public_key')->update(['value' => $publicKey]);
        DB::table('wallet_configurations')->where('id', 'private_key')->update(['value' => $privateKey]);

        $this->output->success('RPC configuration finished');
    }

    private function setEncryptionDetails()
    {
        return $this->secret('Please enter a password (min 6 characters)');
    }
}
