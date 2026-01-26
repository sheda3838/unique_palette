<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailDebugController extends Controller
{
    public function test(Request $request)
    {
        // Increase execution time so we don't hit the 30s limit before SMTP times out
        set_time_limit(120);

        // Security check
        $token = env('DEBUG_TOKEN', 'debug123');
        if ($request->query('token') !== $token) {
            return response()->json(['error' => 'Unauthorized. Provide ?token=' . $token], 403);
        }

        $config = config('mail.mailers.smtp');
        $defaultMailer = config('mail.default');
        $queueConnection = config('queue.default');

        // Check for cached config
        $isCached = app()->configurationIsCached();

        // Remove password from logs for security, but check if it's set
        $sanitizedConfig = $config;
        if (isset($sanitizedConfig['password'])) {
            $sanitizedConfig['password'] = !empty($config['password']) ? '********' : 'NOT SET';
        }

        Log::info('Mail Debug Info triggered', [
            'default_mailer' => $defaultMailer,
            'smtp_config' => $sanitizedConfig,
            'queue_connection' => $queueConnection,
            'config_cached' => $isCached,
            'app_url' => config('app.url'),
        ]);

        $action = $request->query('action', 'test');

        if ($action === 'clear-cache') {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            return response()->json([
                'status' => 'success',
                'message' => 'Config and Cache cleared successfully.'
            ]);
        }

        try {
            // Attempt to send a test email SYNCHRONOUSLY
            // We use Mail::mailer('smtp') to ensure we use SMTP even if default is 'log'
            Mail::mailer('smtp')->raw('This is a test email from Unique Palette production debug tool. If you see this, your SMTP settings are CORRECT.', function ($message) use ($request) {
                $to = $request->query('email', 'test@example.com');
                $message->to($to)
                    ->subject('Production Mail Test - ' . now()->toDateTimeString());
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Email sent synchronously via SMTP. Check Mailtrap.',
                'debug_info' => [
                    'default_mailer' => $defaultMailer,
                    'smtp_host' => $config['host'] ?? 'not set',
                    'smtp_port' => $config['port'] ?? 'not set',
                    'queue_connection' => $queueConnection,
                    'config_cached' => $isCached,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Mail Test Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'hint' => 'Check Railway logs for more details.',
                'smtp_host' => $config['host'] ?? 'not set',
            ], 500);
        }
    }
}
