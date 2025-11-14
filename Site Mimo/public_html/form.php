<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

$is_mail_sent = true;
phpinfo();
if($_POST) {

    $nomeremetente = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $emailremetente = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $assunto = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $mensagem = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    /* Montando a mensagem a ser enviada no corpo do e-mail. */
    $mensagemHTML = '<strong>Formulário de Contato</strong>
        <p><b>Nome:</b> ' . $nomeremetente . '
        <p><b>E-Mail:</b> ' . $emailremetente . '
        <p><b>Assunto:</b> ' . $assunto . '
        <p><b>Mensagem:</b> ' . $mensagem . '</p>
        <hr>';

    // TODO: form data validation;
    if (false) { // validacao do form, nesse teste falhou;

        $is_mail_sent = false;
    }

    if ($is_mail_sent) {

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.mailgun.org';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'postmaster@sandbox9f3592090d614338b56eb9e0a694c9be.mailgun.org';                 // SMTP username
            $mail->Password = '33269aed41eac955a092e43b77889cae-52cbfb43-39bc0163';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('contato@esteticamimo.com.br', 'Estética MIMO');
            $mail->addAddress('contato@esteticamimo.com.br', 'Contato');     // Add a recipient
//    $mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('contato@esteticamimo.com.br', 'Estética MIMO');
//    $mail->addCC('cc@example.com');
//    $mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Formulário site - ' . $assunto;
            $mail->Body = $mensagemHTML;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php if ($is_mail_sent): ?>

    <h1>BELEZA</h1>

<?php else: ?>

    <h1>deu ruim</h1>

<?php endif; ?>
</body>
</html>
