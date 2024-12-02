<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Exception;

use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Log;

use League\OAuth2\Client\Provider\Google;

use App\Services\OAuth2Mailer;
use App\Services\EmailConfigurator;

class OauthController extends Controller
{
    public function mailTest(Request $request)
    {
        try {
            $to = env('MAIL_SUPPORT_TEAM');
            $info = [
                'name' => 'Luis Pumaricra Díaz',
                'message' => 'This is the test message',
            ];

            $subject = 'Test Mail';

            // Class to configure embedded images in specific areas of the email body and attachments.
            $configurator = new EmailConfigurator();

            $configurator->embedImage(
                'images/mustang-logo.png',
                'logo', // This field is important, with this name you will embed somewhere in the body of the email.
                ['alt' => 'Your Company - Logo']
            );

            // You can add more images
            // $configurator->embedImage(...);

            $configurator->attach('attachments/lorem-ipsum.pdf', ['mimeType' => 'application/pdf']);

            // You can attach more files
            // $configurator->attach('attachments/other-file.pdf');

            $send = OAuth2Mailer::sendEmail('emails.mail-test', $to, $subject, $info, $configurator);

            if ($send['success']) {
                return response()->json(['message' => 'Email sent successfully!'], 200);
            }
            
            return response()->json(['message' => $send['message']], 500);
            
        } catch (Exception $e) {
            return response()->json(['message' => "Error sending email: " . $e->getMessage()], 500);
        }
    }

    public function getAuthorization(Request $request) {
        $provider = new Google([
            'clientId'     => env('GMAIL_CLIENT_ID'),
            'clientSecret' => env('GMAIL_CLIENT_SECRET'),
            'redirectUri'  => env('GMAIL_REDIRECT_URI'),
        ]);

        if (isset($request->code)) {
            try {
                $token = $provider->getAccessToken(
                    'authorization_code',
                    ['code' => $request->code]
                );

                // Additional information:
                $refreshToken = $token->getRefreshToken();
                if ($refreshToken) {
                    echo "'Refresh Token: {$refreshToken} <br>";
                } else {
                    echo 'Refresh token not received. You may have previously authorized it.';
                }
                // ------

                $accessToken = $token->getToken();
                echo 'Access Token: ' . $accessToken . '<br>';

            } catch (\Exception $e) {
                echo 'Error trying to get access token: <br>' . $e->getMessage();
            }
        } else {
            $authUrl = $provider->getAuthorizationUrl(
                [
                    'scope' => ['https://mail.google.com/'],
                    'access_type' => 'offline',
                    'prompt' => 'consent',
                ]
            );

            echo "Access this URL to authorize:<br>";
            echo "<a href='{$authUrl}'>$authUrl</a>";  
        }
    }
}
