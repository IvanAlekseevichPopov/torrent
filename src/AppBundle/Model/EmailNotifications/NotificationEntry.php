<?php


declare(strict_types = 1);

namespace AppBundle\Model\EmailNotifications;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Сущность письма
 *
 * @author Попов Иван
 */
class NotificationEntry
{
    /**
     * Email отправителя
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @var string
     */
    protected $fromEmail;

    /**
     * Имя отправителя
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $fromName;

    /**
     * Email получателей
     *
     * @var array
     */
    protected $recipientList = [];

    /**
     * Кодировка сообщения
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     * Заголовок сообщения
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $messageSubject;

    /**
     * Тело сообщения
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $messageBody;

    /**
     * Конвертация в объект сообщения Swift_Message
     *
     * @return \Swift_Message
     */
    public function toMessage(): \Swift_Message
    {
        $messageInstance = \Swift_Message::newInstance();

        $messageInstance
            ->setSubject($this->getMessageSubject())
            ->setFrom($this->getFromEmail(), $this->getFromName())
            ->setTo($this->getRecipientList())
            ->setCharset($this->getCharset())
            ->setBody($this->getMessageBody())
            ->setContentType('text/html');

        return $messageInstance;
    }

    /**
     * Получение сообщения в виде строки
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->toMessage();
    }

    /**
     * Валидация списка получателей
     *
     * @Assert\Callback
     *
     * @param ExecutionContextInterface $context
     *
     * @return $this
     */
    public function validateRecipientList(ExecutionContextInterface $context)
    {
        $list = $this->getRecipientList();

        if(0 === count($list))
        {
            $context->buildViolation('Recipient list is empty')->atPath('recipientList')->addViolation();

            return $this;
        }

        foreach($this->getRecipientList() as $email)
        {
            if(false === filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $context->buildViolation(sprintf('Invalid email %s', $email))->atPath('recipientList')->addViolation();

                return $this;
            }
        }
    }

    /**
     * Добавление получателя
     *
     * @param string $toEmail
     *
     * @return $this
     */
    public function addRecipient(string $toEmail)
    {
        $this->recipientList[] = $toEmail;

        return $this;
    }

    /**
     * Сеттер email получателя
     *
     * @param string $fromEmail
     *
     * @return $this
     */
    public function setFromEmail(string $fromEmail)
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * Сеттер имени отправителя
     *
     * @param string $fromName
     *
     * @return $this
     */
    public function setFromName(string $fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Сеттер получателей
     *
     * @param array $recipientList
     *
     * @return $this
     */
    public function setRecipientList(array $recipientList)
    {
        $this->recipientList = $recipientList;

        return $this;
    }

    /**
     * Сеттер кодировки
     *
     * @param string $charset
     *
     * @return $this
     */
    public function setCharset(string $charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Сеттер темы сообщения
     *
     * @param string $messageSubject
     *
     * @return $this
     */
    public function setMessageSubject(string $messageSubject)
    {
        $this->messageSubject = $messageSubject;

        return $this;
    }

    /**
     * Сеттер тела сообщения
     *
     * @param string $messageBody
     *
     * @return $this
     */
    public function setMessageBody(string $messageBody)
    {
        $this->messageBody = $messageBody;

        return $this;
    }

    /**
     * Геттер email отправителя
     *
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * Геттер имени отправителя
     *
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * Геттер email получателей
     *
     * @return array
     */
    public function getRecipientList(): array
    {
        return array_unique($this->recipientList);
    }

    /**
     * Геттер кодировки
     *
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * Геттер темы сообщения
     *
     * @return string
     */
    public function getMessageSubject(): string
    {
        return $this->messageSubject;
    }

    /**
     * Геттер тела сообщения
     *
     * @return string
     */
    public function getMessageBody(): string
    {
        return $this->messageBody;
    }
}
