# Railway Production Debugging Checklist

If image uploads or displays are failing in the production environment (Railway), follow these steps to diagnose and resolve the issue.

## 1. PHP Configuration (`.user.ini`)

Large images require higher upload and memory limits. Ensure you have a `.user.ini` file in your `public/` directory with the following settings:

```ini
upload_max_filesize = 32M
post_max_size = 32M
memory_limit = 256M
max_execution_time = 300
```

_Note: After changing this, you may need to redeploy or restart the Railway service._

## 2. MySQL `max_allowed_packet`

Since we are storing images as `LONGBLOB`, the MySQL server must allow large packets.

- **Symptom**: "MySQL server has gone away" or "Packet bigger than 'max_allowed_packet'".
- **Fix**: In Railway MySQL settings (or via a custom configuration), set `max_allowed_packet` to at least `64M`.

## 3. Livewire Temporary Storage

Livewire stores files in `storage/app/livewire-tmp` before processing.

- **Symptom**: "The file could not be uploaded" instantly, or 403/500 errors on `/livewire/upload`.
- **Fix**: Ensure the `storage` directory is writable. On Railway, if you don't use a Volume, this folder is ephemeral but should still be writable by the PHP process. If it's full or permissions are wrong, uploads will fail.

## 4. Database Column Types

Verify that your production database actually has `LONGBLOB` columns.

- **Requirement**: `image_blob` and `profile_image_blob` MUST be `LONGBLOB`.
- **How to check**: Run `DESCRIBE artworks;` and `DESCRIBE users;` in the Railway MySQL shell.

## 5. Required PHP Extensions

Ensure the following extensions are active in your Railway environment:

- `fileinfo`: Required for `getMimeType()` and MIME detection.
- `gd` or `imagick`: Required for image validation.

## 6. Logs to Inspect

- **Laravel Logs**: Check `storage/logs/laravel.log`. Look for:
    - `Malformed UTF-8`: Indicates a blob column was accidentally serialized into JSON (should be fixed by `$hidden` and `select()` usage).
    - `Allowed memory size exhausted`: Indicates `memory_limit` is too low for the image size.
- **Railway Service Logs**: Look for:
    - `413 Request Entity Too Large`: Nginx/Proxy limit. Railway's proxy usually allows up to 100MB, but double-check.
    - `504 Gateway Timeout`: Upload is taking too long.

## 7. Verification Steps

- Run `php artisan migrate` in the Railway terminal to ensure all blob columns exist.
- Run `php artisan db:seed --class=ArtworkSeeder` to test storing blobs from existing local files.

## 8. Email Verification Debugging

If verify emails are not being sent:

1.  **Check Env Vars**: Ensure `MAIL_MAILER=smtp` is set in Railway. If missing, it defaults to `log`.
2.  **Queue Connection**: We are using `QueuedVerifyEmail`. Set `QUEUE_CONNECTION=sync` to send emails instantly without a worker.
3.  **Clear Config Cache**: If you see old values, run `php artisan config:clear` in the Railway terminal.
4.  **Smoke Test**: Visit `https://your-app.up.railway.app/debug/mail-test?token=debug123&email=your@email.com` to force a synchronous test email.
