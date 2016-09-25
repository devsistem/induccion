<?php

require '../vendor/autoload.php';
use Mailgun\Mailgun;
				
class MailGunMail {

  function enviar_simple_mail($email_tipo_envio='curl', $nombre_destinatario, $email_destinatario, $email_asunto, $email_cuerpo) {
    
    switch($email_tipo_envio) {
     
     case 'curl':	

	    $ch = curl_init();
		  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		  curl_setopt($ch, CURLOPT_USERPWD, MG_API_KEY);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		  curl_setopt($ch, CURLOPT_URL, CONF_MAILGUN_SANDBOX);
  	  curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => 'Interlogical <me@samples.mailgun.org>',
                                      'to' => $email_destinatario,
                                      'subject' => $email_asunto,
                                      'text' => $email_cuerpo));
			 $result = curl_exec($ch);
  		 curl_close($ch);
  		
  		 print "--" . $result;
       return $result;
     
     break;

     case 'smtp':
     
     require FILE_PATH.'/api/phpmailer/PHPMailerAutoload.php';

			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.mailgun.org';                     // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'postmaster@sandbox674cc8874b6845299e4764a41828cb6f.mailgun.org';   // SMTP username
			$mail->Password = '73b5ba1e85ff7bc53c7ec77dcb1ec774';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable encryption, only 'tls' is accepted
			
			$mail->From = 'desarrollo@interlogical.net';
			$mail->FromName = 'Mailer';
			$mail->addAddress($email_destinatario);                 // Add a recipient
			
			$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
			
			$mail->Subject = 'Hello';
			$mail->Body    = 'Testing some Mailgun awesomness';
			
			if(!$mail->send()) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
			    echo 'Message has been sent';
			}
     
     break;
     
     case 'api':

				# Instantiate the client.
				$mgClient = new Mailgun(MG_API_KEY);
				$domain = MG_API_DOMAIN;

				$mgClient = new Mailgun('key-8e34dd8e7a23fa5f5a37357e57807d35');
				$domain = "sandbox674cc8874b6845299e4764a41828cb6f.mailgun.org";

				# Make the call to the client.
				$result = $mgClient->sendMessage($domain, array(
    			'from'    => 'My Business Name <me@samples.mailgun.org',
    			'to'      => $email_destinatario,
    			'subject' => 'Hello',
		    'text'    => 'Testing some Mailgun awesomness!'
				));       
     break;   
    }   
  } // f
} //class
?>