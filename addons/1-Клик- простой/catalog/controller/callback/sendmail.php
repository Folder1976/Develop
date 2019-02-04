<?php
if (isset($_POST['phone']) and $_POST['phone'] != '' and isset($_POST['name']) and $_POST['name'] != '') {
	function clean_user_data($arr) {
		foreach ($arr as &$str) {
			$str = trim($str);
			$str = strip_tags($str);
			$str = htmlspecialchars($str);
		}
		return $arr;
	}
	
	$_POST = clean_user_data($_POST);
	
	$mail_bcc = $mail_to = $mail_from = 'folder.list@gmail.com';
	
	$mail_subject = 'Новый заказ';
	
	$mail_message = '
		<html>
			<head>
				<title>'.$mail_subject.'</title>
			</head>
			<body>
				Товар: '.$_POST['product'].'<br><br>
				Кол-во: '.$_POST['value'].'<br><br>
				Валюта: '.$_POST['currency'].'<br><br>
				
				<b>Контактные данные</b><br><br>
				Имя: '.$_POST['name'].'<br><br>
				Телефон: '.$_POST['phone'].'<br><br>
				E-mail: '.$_POST['email'].'<br><br>
				Сообщение:<br>'.$_POST['comment'].'
			</body>
		</html>';
	
	$headers  = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= 'From: '.$mail_from."\r\n";
	$headers .= 'BCC: '.$mail_bcc."\r\n";
	$headers .= 'X-Mailer: PHP/'.phpversion();
	
	if (mail($mail_to, $mail_subject, $mail_message, $headers)) {
		$json['success'] = 'Сообщение отправлено.';
	} else {
		$json['error'] = 'Ошибка отправки сообщения.';
	}
} else {
	$json['error'] = 'Неверно заполнены контактные данные.';
}

echo json_encode($json);

