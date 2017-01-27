<?php

declare(strict_types = 1);

namespace AppBundle\Model\EmailNotifications;

use AppBundle\Formatter\ExceptionMessageFormatter;
use Psr\Log\
{
    LoggerInterface, LoggerAwareTrait
};
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Враппер по отправке сообщений на почту
 *
 * @author Попов Иван
 */
class EmailNotificationsWrapper
{
    use LoggerAwareTrait;

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var string */
    protected $fromEmail;

    /** @var string */
    protected $fromName;

    /** @var string */
    protected $charset;

    /**
     * @param \Swift_Mailer      $mailer
     * @param ValidatorInterface $validator
     * @param string             $fromEmail
     * @param string             $fromName
     * @param string             $charset
     * @param LoggerInterface    $logger
     */
    public function __construct(
        \Swift_Mailer $mailer, ValidatorInterface $validator, string $fromEmail, string $fromName = '',
        string $charset, LoggerInterface $logger
    )
    {
        $this->mailer = $mailer;
        $this->validator = $validator;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->charset = $charset;

        $this->setLogger($logger);
    }

    /**
     * Отправка сообщения
     *
     * @param string $toEmail
     * @param string $messageSubject
     * @param string $messageBody
     *
     * @return boolean
     */
    public function execute(string $toEmail, string $messageSubject, string $messageBody): bool
    {
        try
        {
            $messageEntry = $this->createMessageEntry($toEmail, $messageSubject, $messageBody);

            $this->validateMessageEntry($messageEntry);

            $result = (boolean) $this->mailer->send($messageEntry->toMessage());

            if(true === $result)
            {
                return $result;
            }

            throw new \RuntimeException(sprintf('Ошибка отправки сообщения.%s', PHP_EOL . (string) $messageEntry));
        }
        catch(\Exception $e)
        {
            $this->logger->critical(ExceptionMessageFormatter::exceptionToString($e));
        }

        return false;
    }

    /**
     * Валидация сущности оповещения
     *
     * @param NotificationEntry $entry
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function validateMessageEntry(NotificationEntry $entry)
    {
        $violationList = $this->validator->validate($entry);

        if(0 !== count($violationList))
        {
            throw new \RuntimeException((string) $violationList->get(0)->getMessage());
        }
    }

    /**
     * Создание ValueObject сообщения
     *
     * @param string $toEmail
     * @param string $messageSubject
     * @param string $messageBody
     *
     * @return NotificationEntry
     */
    protected function createMessageEntry(string $toEmail, string $messageSubject, string $messageBody
    ): NotificationEntry
    {
        return (new NotificationEntry)
            ->setCharset($this->charset)
            ->setFromName($this->fromName)
            ->setFromEmail($this->fromEmail)
            ->setMessageSubject($messageSubject)
            ->setMessageBody($messageBody)
            ->addRecipient($toEmail);
    }
}
