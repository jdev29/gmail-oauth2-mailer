<?php
namespace App\Services;

use Google\Client as GoogleClient;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Plugins_Loggers_ArrayLogger;
use Swift_Plugins_LoggerPlugin;
use Swift_Plugins_Authentication_XOAUTH2;
use Illuminate\Support\Facades\View;
use Swift_Image;
use Illuminate\Support\Facades\Log;
class OAuth2Mailer
{
    public static function getAccessToken()
    {
        $client = new GoogleClient();
        $client->setClientId(env('GMAIL_CLIENT_ID'));
        $client->setClientSecret(env('GMAIL_CLIENT_SECRET'));
        $client->setScopes(['https://mail.google.com/']);
        
        $client->fetchAccessTokenWithRefreshToken(env('GMAIL_REFRESH_TOKEN'));

        if ($client->getAccessToken()) {
            return $client->getAccessToken()['access_token'];
        } else {
            throw new \Exception('Error getting access token');
        }
    }

    public static function sendEmail($template, $to, $subject, $data, EmailConfigurator $configurator = null)
    {
        try {
            $accessToken = self::getAccessToken();
            
            Log::info("Transport Config: ", [
                'username' => env('MAIL_USERNAME'),
                'access_token' => $accessToken,
            ]);

            $message = (new Swift_Message($subject))
            ->setFrom([env('MAIL_USERNAME') => env('MAIL_FROM_NAME')])
            ->setTo($to);

            if ($configurator) {
                $inlineImagesByKey = [];
                foreach ($configurator->getInlineImages() as $image) {
                    $imageData = \Swift_Image::fromPath($image['file']);
                    $cid = $message->embed($imageData);

                    $inlineImagesByKey[$image['key']] = [
                        'cid' => $cid,
                        'options' => $image['options'],
                    ];
                }
                
                $data['inline_images'] = $inlineImagesByKey;

                // --------

                $attachments = [];
                foreach ($configurator->getAttachments() as $attachment) {
                    $attachments[] = [
                        'url' => $attachment['file'],
                        'name' => basename($attachment['file']),
                    ];
                    $message->attach(\Swift_Attachment::fromPath($attachment['file'], $attachment['options']['mimeType'] ?? 'application/octet-stream'));
                }
                $data['attachments'] = $attachments;
            }

            $content = view($template, $data)->render();
            $message->setContentType("multipart/related");
            $message->setBody($content, 'text/html');

            $transport = (new Swift_SmtpTransport(env('MAIL_HOST'), env('MAIL_PORT'), env('MAIL_ENCRYPTION')))
                ->setUsername(env('MAIL_USERNAME'))
                ->setPassword($accessToken)
                ->setAuthMode('XOAUTH2');
    
            $mailer = new Swift_Mailer($transport);
            
            $mailer->send($message);
            return [
                'success' => true,
                'message' => 'Email sent successfully!',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Internal error in OAuth2 Mailer > sendEmail(): ' . $e->getMessage(),
            ];
            throw $e;
        }
    }
}
