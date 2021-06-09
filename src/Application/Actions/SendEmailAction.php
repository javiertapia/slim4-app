<?php
namespace App\Application\Actions;

use App\Application\Factories\MailerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SendEmailAction
{
    private MailerFactory $mailerFactory;

    private const SUBJECT = 'Mensaje de prueba';

    public function __construct(MailerFactory $mailerFactory)
    {
        $this->mailerFactory = $mailerFactory;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response
    ): ResponseInterface
    {
        try {
            $mailer = $this->mailerFactory->createMailer();
            $mailer->Subject = self::SUBJECT;
            $mailer->addAddress('javier.tapia.d@gmail.com');
            $mailer->msgHTML(date('Y-m-d H:i:s'));
            $mailer->Send();

            $json = json_encode(['message' => 'Email was sent!'], JSON_PRETTY_PRINT);
            $response->getBody()->write($json);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
