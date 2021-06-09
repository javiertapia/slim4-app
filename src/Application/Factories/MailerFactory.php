<?php
namespace App\Application\Factories;

use PHPMailer\PHPMailer\PHPMailer;
use Throwable;

final class MailerFactory
{
    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function createMailer(): PHPMailer
    {
        try {
            $mailer = new PHPMailer(true);
            $mailer->From = $this->settings['from']['email'] ?? '';
            $mailer->FromName = $this->settings['from']['email'] ?? '';
            $mailer->CharSet = 'UTF-8';

            if (!empty($this->settings['smtp']['host'])) {
                $mailer->isSMTP();
                $mailer->SMTPAuth = true;
                $mailer->Host = $this->settings['smtp']['host'];
                $mailer->Username = $this->settings['smtp']['user'];
                $mailer->Password = $this->settings['smtp']['pass'];
                $mailer->Port = $this->settings['smtp']['port'];
            }

            if (!empty($this->settings['bcc'])) {
                foreach ($this->settings['bcc'] as $bcc) {
                    $address = filter_var($bcc, FILTER_SANITIZE_EMAIL);
                    if ($address) {
                        $mailer->addBCC($bcc);
                    }
                }
            }

            return $mailer;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
