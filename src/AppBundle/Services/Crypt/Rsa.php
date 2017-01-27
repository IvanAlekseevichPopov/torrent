<?php

/**
 * Ценоанализатор
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://ценоанализатор.рф
 */

declare(strict_types = 1);

namespace AppBundle\Services\Crypt;

/**
 * Криптование с помощью RSA
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://ценоанализатор.рф
 */
class Rsa implements CryptHandlerInterface
{
    /**
     * Абсолютный путь до приватного ключа
     *
     * @var null|string
     */
    protected $privateKey;

    /**
     * Абсолютный путь до публичного ключа
     *
     * @var null|string
     */
    protected $publicKey;

    /**
     * @param string $privateKeyPath
     * @param string $publicKeyPath
     */
    public function __construct($privateKeyPath, $publicKeyPath)
    {
        $this->privateKey = $privateKeyPath;
        $this->publicKey = $publicKeyPath;
    }

    /**
     * @inheritdoc
     *
     * @param string $content
     *
     * @return string
     */
    public function encrypt($content)
    {
        if('' !== (string) $content)
        {

            $encrypted = '';

            openssl_private_encrypt($content, $encrypted, $this->loadPrivateKey());

            if('' !== (string) $encrypted)
            {
                $encrypted = unpack('H*', $encrypted);

                return array_shift($encrypted);
            }

            return $encrypted;
        }

        return '';
    }

    /**
     * @inheritdoc
     *
     * @param string $content
     *
     * @return string
     */
    public function decrypt($content)
    {
        $decrypted = '';
        $content = pack('H*', trim($content));

        openssl_public_decrypt($content, $decrypted, $this->loadPublicKey());

        return $decrypted;
    }

    /**
     * Получение ресурса приватного ключа
     *
     * @return resource
     */
    protected function loadPrivateKey()
    {
        return openssl_get_privatekey($this->loadFileContent($this->privateKey));
    }

    /**
     * Получение ресурса публичного ключа
     *
     * @return resource
     */
    protected function loadPublicKey()
    {
        return openssl_get_publickey($this->loadFileContent($this->publicKey));
    }

    /**
     * Получение контента файла ключа
     *
     * @param string $filePath Путь до файла
     *
     * @return string
     *
     * @throws \LogicException
     */
    protected function loadFileContent($filePath)
    {
        if(file_exists($filePath) && is_readable($filePath))
        {
            return file_get_contents($filePath);
        }
        else
        {
            throw new \LogicException(
                sprintf('Файл ключей `%s` не существует, или недоступен для чтения', $filePath)
            );
        }
    }
}
