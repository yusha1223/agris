<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - AGRIS</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: 'Segoe UI', Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4; padding:40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;">

                    <tr>
                        <td style="background:white; border-radius:8px; padding:36px 32px;">

                            <h2 style="margin:0 0 8px; font-size:20px; font-weight:700; color:#222;">Verifikasi Email</h2>
                            <p style="margin:0 0 24px; font-size:14px; color:#666;">
                                Halo <?php echo e($namaLengkap); ?>, berikut kode OTP untuk verifikasi akun AGRIS Anda:
                            </p>

                            <div style="background:#f7f7f7; border:1px solid #e0e0e0; border-radius:8px; padding:20px; text-align:center; margin:0 0 24px;">
                                <span style="font-size:32px; font-weight:700; letter-spacing:6px; color:#222; font-family:'Courier New', monospace;"><?php echo e($otp); ?></span>
                            </div>

                            <p style="margin:0 0 8px; font-size:13px; color:#666; line-height:1.6;">
                                Kode berlaku selama <strong>10 menit</strong>. Jangan bagikan kode ini kepada siapa pun.
                            </p>
                            <p style="margin:0; font-size:13px; color:#999; line-height:1.6;">
                                Jika Anda tidak merasa mendaftar, abaikan email ini.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-top:24px;">
                            <p style="font-size:12px; color:#999; margin:0;">
                                © <?php echo e(date('Y')); ?> AGRIS
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
<?php /**PATH D:\project\Agris\resources\views/emails/otp.blade.php ENDPATH**/ ?>