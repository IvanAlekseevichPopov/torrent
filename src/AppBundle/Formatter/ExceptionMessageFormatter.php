<?php

declare(strict_types = 1);

namespace AppBundle\Formatter;

class ExceptionMessageFormatter
{
    /**
     * Форматирование сообщения исключения
     *
     * @param \Exception $e
     *
     * @return string
     */
    public static function exceptionToString(\Exception $e)
    {
        $previousText = '';

        if($previous = $e->getPrevious())
        {
            do
            {
                $previousText .= sprintf(
                    ', %s(code: %s %s at %s:%s', get_class($previous), $previous->getCode(), $previous->getMessage(),
                    $previous->getFile(), $previous->getLine()
                );
            }
            while($previous = $previous->getPrevious());
        }

        $str = sprintf(
            '[object] (%s(code: %s): %s at %s:%s%s)', get_class($e), $e->getCode(), $e->getMessage(), $e->getFile(),
            $e->getLine(), $previousText
        );

        return trim($str);
    }
}
