<?php
	
	$HPAN_SRC="5559572777537442";
	$HPAN_SRC_EXPDT="201712";
	$HPAN_SRC_CVV="123";
	$HPAN_DST="4083972054074721";
	$HPAN_DST_EXPDT="201712";
	$P2P_AMT="100";
	$P2P_CURRENCY="RUB";
	$link_token="https://testjmb.alfabank.ru/oauth/oauth/token";
	$link_fee="https://testjmb.alfabank.ru/uapi/v1/fee";
	$link_transter="https://testjmb.alfabank.ru/uapi/v1/transfers";
	$client_ip=$_SERVER['REMOTE_ADDR'];
	
	
		echo"<br><br><h3>Get TOKEN</h3><br>";	
	
	$string="scope=read&grant_type=client_credentials";
	$headers = array("Authorization: Basic ".base64_encode('TEST:test_user_secret'),"Cache-Control: no-cache"); //SOAPAction: your op URL
		
	
		echo "<b>Link:</b> <br>".$link_token;
		echo "<br><br><b>Sending headers:</b> <br>&nbsp;&nbsp;&nbsp;Authorization: Basic ".base64_encode('TEST:test_user_secret')."Cache-Control: no-cache";
		echo "<br><br><b>Sending POST data:</b> <br>&nbsp;&nbsp;&nbsp;".$string;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link_token);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$token_data = curl_exec($ch);
	curl_close($ch);
	
		echo "<br><br><b>Server response:</b><br>&nbsp;&nbsp;&nbsp;".$token_data;

	$token= json_decode($token_data,true);
	$token=$token['access_token'];
		
		echo "<br><br><b>Token is:</b> <br>&nbsp;&nbsp;&nbsp;".$token;
		echo"<br><br><h3>Get FEE</h3><br>";	
	

	
	$string='
	{
		"sender": {
		"card":{
		"number":"'.$HPAN_SRC.'"
		}
		},
		"recipient": {
		"card":{
		"number":"'.$HPAN_DST.'"
		}
		},
		"amount":"'.$P2P_AMT.'",
		"currency":"'.$P2P_CURRENCY.'"
	}
	';

	
	$headers = array("Authorization: Bearer ".$token,"Content-Type: application/json"); //SOAPAction: your op URL
		
		
		echo "<b>Link:</b> <br>".$link_fee;
		echo "<br><br><b>Sending headers:</b> <br>&nbsp;&nbsp;&nbsp;Authorization: Bearer ".$token." Content-Type: application/json";
		echo "<br><br><b>Sending POST data:</b> <br>&nbsp;&nbsp;&nbsp;<pre>".$string."</pre>";
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link_fee);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$fee_data = curl_exec($ch);
	curl_close($ch);
	
		echo "<br><br><b>Server response:</b><br>&nbsp;&nbsp;&nbsp;<pre>".$fee_data."</pre>";
		
	$fee=json_decode($fee_data,true);
	$fee_is=$fee['fee'];
	$fee_max=$fee['max'];
	$fee_interest=$fee['interest'];
	$fee_min=$fee['min'];
	$fee_constant=$fee['constant'];
	
		echo "<br><br><b>Сумма Комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_is;
		echo "<br><br><b>Максимальная сумма комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_max;
		echo "<br><br><b>Минимальная сумма комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_min;
		echo "<br><br><b>Обязательная часть комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_constant;
		echo "<br><br><b>Прцентная часть комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_interest;
	
	
	echo"<br><br><h3>Make TRANSFER</h3><br>";	
	
	$string='
	{
		"sender": {
			"card":{
				"cvv": "'.$HPAN_SRC_CVV.'",
        			"exp_date": "'.$HPAN_SRC_EXPDT.'",
				"number":"'.$HPAN_SRC.'"
			}
		},
		"recipient": {
			"card":{
				"number":"'.$HPAN_DST.'"
			}
		},
		"amount":"'.$P2P_AMT.'",
		"currency":"'.$P2P_CURRENCY.'",
		"client_ip": "'.$client_ip.'"
	}
	';

	
	$headers = array("Authorization: Bearer ".$token,"Content-Type: application/json","TYPE: PUT"); //SOAPAction: your op URL
		
		
		echo "<b>Link:</b> <br>".$link_fee;
		echo "<br><br><b>Sending headers:</b> <br>&nbsp;&nbsp;&nbsp;Authorization: Bearer ".$token." Content-Type: application/json  TYPE: PUT";
		echo "<br><br><b>Sending PUT data:</b> <br>&nbsp;&nbsp;&nbsp;<pre>".$string."</pre>";
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link_transter);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$transfer_data = curl_exec($ch);
	curl_close($ch);
	
		echo "<br><br><b>Server response:</b><br>&nbsp;&nbsp;&nbsp;<pre>".$transfer_data."</pre>";	
	
	$transfer=json_decode($transfer_data,true);
	$trans_term_url=$transfer['termURL'];
	$trans_md=$transfer['md'];
	$trans_acsurl=$transfer['acsURL'];
	$trans_pareq=$transfer['pareq'];
	$trans_trans_id=$transfer['transaction_id'];
	$Trans_template_id=$transfer['template_id'];
	
	
	//!!!!!!!!!!!!!!!!!!!
	//!!!!!!!!!!!!!!!!!!!
	$trans_pareq=str_replace("\n", '', $trans_pareq);
	//!!!!!!!!!!!!!!!!!!!
	//!!!!!!!!!!!!!!!!!!!
	
		echo "<br><br><b>ID карты отправителя:</b><br>&nbsp;&nbsp;&nbsp;".$transfer['sender']['card']['id'];
		echo "<br><br><b>ID карты получателя:</b><br>&nbsp;&nbsp;&nbsp;".$transfer['recipient']['card']['id'];
		echo "<br><br><b>termURL: Callback URL (завершение транзакции после 3DS):</b><br>&nbsp;&nbsp;&nbsp;".$trans_term_url;
		echo "<br><br><b>MD: ID транзакции который придет на CallBackURL:</b><br>&nbsp;&nbsp;&nbsp;".$trans_md;
		echo "<br><br><b>UacsURL: RL для перенаправления пользователя для 3DS:</b><br>&nbsp;&nbsp;&nbsp;".$trans_acsurl;
		echo "<br><br><b>pareq: Секретный код для отправки банку эмитенту:</b><br>&nbsp;&nbsp;&nbsp;".$trans_pareq;
		echo '<br><b><font color=red>ОБЯЗАТЕЛНО вырезаем смволы "\n"!!! </font></b>';
		echo "<br><br><b>ID транзакции:</b><br>&nbsp;&nbsp;&nbsp;".$trans_trans_id;
		echo "<br><br><b>ID шаблона:</b><br>&nbsp;&nbsp;&nbsp;".$Trans_template_id;
	
		echo "<br><br><br><br><h3>Подтверждение 3DS</h3><br>Теперь необходимо отправить пользователя на страницу <i>acsURL</i> и методом POST передать параметры PaReq=<i>pareq</i>, md=<i>md</i>, TermUrl=<i>termURL</i>  <br><br>Что бы не перенаправлять с этой страницы я сделал HTML FORM, в которой есть все необходимые параметры. При нажатии на кнопку СДЕЛАТЬ ПЕРЕВОД вы будете перемещены на страницу банка выпустившего карту отправителя и вам придет СМС с одноразовым паролем. <br><br><i>Вот пример этой формы. (данные уже подставлены из ранее выполненных запросов)</i>";
		
		echo '<br><br><pre>
		&lt;!DOCTYPE html&gt;
		  &lt;head&gt;
		    &lt;title&gt;3DS make&lt;/title&gt;
		    &lt;meta http-equiv="Content-Type" content="text/html; charset=utf-8"&gt;
		  &lt;/head&gt;
		  &lt;body&gt;
			&lt;/div&gt;
		        &lt;div&gt;
		                    &lt;div&gt;
		                            &lt;form id="postForm" action="'.$trans_acsurl.'" method="POST"&gt;
		                                    &lt;input type="hidden" name="PaReq" value="'.$trans_pareq.'"&gt;
		                                    &lt;input type="hidden" name="MD" value="'.$trans_md.'"&gt;
		                                    &lt;input type="hidden" name="TermUrl" value="'.$trans_term_url.'"&gt;
		                                    &lt;input type="submit" value="Сделать перевод"/&gt;
		                            &lt;/form&gt;
		                    &lt;/div&gt;
		                &lt;/div&gt;
			&lt;/div&gt;
		  &lt;/body&gt;
		&lt;/html&gt;
		</pre>';
		
	echo '<br><br><br> 
	После того, как пользователь введет пароль из СМС, его перенаправит обратно на <i>termURL</i>, где перевод будет окончательно завершен (автоматически) после чего будет показана ваша страница, которую вы указывали при регистрации. <br><br><br>Вот сама кнопка.<br><i> Клик на нее перебросит вас на страницу банка выпустившего карту отправителя ($HPAN_SRC)</i><br><br>
	';	
		echo'
		<!DOCTYPE html>
		  <head>
		    <title>3DS make</title>
		    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		  </head>
		  <body>
			</div>
		        <div>
                    <div>
                        <form id="postForm" action="'.$trans_acsurl.'" method="POST">
                            <input type="hidden" name="PaReq" value="'.$trans_pareq.'">
                            <input type="hidden" name="MD" value="'.$trans_md.'">
                            <input type="hidden" name="TermUrl" value="'.$trans_term_url.'">
                            <input type="submit" value="Сделать перевод"/>
                        </form>
                    </div>
                </div>
			</div>
		  </body>
		</html><br><br><br>
		';
	


	echo"<br><br><h3>Get FEE с использованием связки</h3><br>";	
	

	
	$string='
	{
		"sender": {
		"card":{
		"id":"'.$transfer['sender']['card']['id'].'"
		}
		},
		"recipient": {
		"card":{
		"id":"'.$transfer['recipient']['card']['id'].'"
		}
		},
		"amount":"'.$P2P_AMT.'",
		"currency":"'.$P2P_CURRENCY.'"
	}
	';

	
	$headers = array("Authorization: Bearer ".$token,"Content-Type: application/json"); //SOAPAction: your op URL
		
		
		echo "<b>Link:</b> <br>".$link_fee;
		echo "<br><br><b>Sending headers:</b> <br>&nbsp;&nbsp;&nbsp;Authorization: Bearer ".$token." Content-Type: application/json";
		echo "<br><br><b>Sending POST data:</b> <br>&nbsp;&nbsp;&nbsp;<pre>".$string."</pre>";
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link_fee);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$fee_data = curl_exec($ch);
	curl_close($ch);
	
		echo "<br><br><b>Server response:</b><br>&nbsp;&nbsp;&nbsp;<pre>".$fee_data."</pre>";
		
	$fee=json_decode($fee_data,true);
	$fee_is=$fee['fee'];
	$fee_max=$fee['max'];
	$fee_interest=$fee['interest'];
	$fee_min=$fee['min'];
	$fee_constant=$fee['constant'];
	
		echo "<br><br><b>Сумма Комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_is;
		echo "<br><br><b>Максимальная сумма комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_max;
		echo "<br><br><b>Минимальная сумма комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_min;
		echo "<br><br><b>Обязательная часть комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_constant;
		echo "<br><br><b>Прцентная часть комиссии:</b><br>&nbsp;&nbsp;&nbsp;".$fee_interest;
	
	
	echo"<br><br><h3>Make TRANSFER использованием связки</h3><br>";	
	
	$string='
	{
		"sender": {
			"card":{
				"cvv": "'.$HPAN_SRC_CVV.'",
				"id":"'.$transfer['sender']['card']['id'].'"
			}
		},
		"recipient": {
			"card":{
				"id":"'.$transfer['recipient']['card']['id'].'"
			}
		},
		"amount":"'.$P2P_AMT.'",
		"currency":"'.$P2P_CURRENCY.'",
		"client_ip": "'.$client_ip.'"
	}
	';

	
	$headers = array("Authorization: Bearer ".$token,"Content-Type: application/json","TYPE: PUT"); //SOAPAction: your op URL
		
		
		echo "<b>Link:</b> <br>".$link_fee;
		echo "<br><br><b>Sending headers:</b> <br>&nbsp;&nbsp;&nbsp;Authorization: Bearer ".$token." Content-Type: application/json  TYPE: PUT";
		echo "<br><br><b>Sending PUT data:</b> <br>&nbsp;&nbsp;&nbsp;<pre>".$string."</pre>";
	
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link_transter);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$transfer_data = curl_exec($ch);
	curl_close($ch);
	
		echo "<br><br><b>Server response:</b><br>&nbsp;&nbsp;&nbsp;<pre>".$transfer_data."</pre>";	
	
	$transfer=json_decode($transfer_data,true);
	$trans_term_url=$transfer['termURL'];
	$trans_md=$transfer['md'];
	$trans_acsurl=$transfer['acsURL'];
	$trans_pareq=$transfer['pareq'];
	$trans_trans_id=$transfer['transaction_id'];
	$Trans_template_id=$transfer['template_id'];
	
	
	//!!!!!!!!!!!!!!!!!!!
	//!!!!!!!!!!!!!!!!!!!
	$trans_pareq=str_replace("\n", '', $trans_pareq);
	//!!!!!!!!!!!!!!!!!!!
	//!!!!!!!!!!!!!!!!!!!
	
		echo "<br><br><b>ID карты отправителя:</b><br>&nbsp;&nbsp;&nbsp;".$transfer['sender']['card']['id'];
		echo "<br><br><b>ID карты получателя:</b><br>&nbsp;&nbsp;&nbsp;".$transfer['recipient']['card']['id'];
		echo "<br><br><b>termURL: Callback URL (завершение транзакции после 3DS):</b><br>&nbsp;&nbsp;&nbsp;".$trans_term_url;
		echo "<br><br><b>MD: ID транзакции который придет на CallBackURL:</b><br>&nbsp;&nbsp;&nbsp;".$trans_md;
		echo "<br><br><b>UacsURL: RL для перенаправления пользователя для 3DS:</b><br>&nbsp;&nbsp;&nbsp;".$trans_acsurl;
		echo "<br><br><b>pareq: Секретный код для отправки банку эмитенту:</b><br>&nbsp;&nbsp;&nbsp;".$trans_pareq;
		echo '<br><b><font color=red>ОБЯЗАТЕЛНО вырезаем смволы "\n"!!! </font></b>';
		echo "<br><br><b>ID транзакции:</b><br>&nbsp;&nbsp;&nbsp;".$trans_trans_id;
		echo "<br><br><b>ID шаблона:</b><br>&nbsp;&nbsp;&nbsp;".$Trans_template_id;
		echo "<br><br><br><br><h3>Подтверждение 3DS</h3><br>Теперь необходимо отправить пользователя на страницу <i>acsURL</i> и методом POST передать параметры PaReq=<i>pareq</i>, md=<i>md</i>, TermUrl=<i>termURL</i>  <br><br>Что бы не перенаправлять с этой страницы я сделал HTML FORM, в которой есть все необходимые параметры. При нажатии на кнопку СДЕЛАТЬ ПЕРЕВОД вы будете перемещены на страницу банка выпустившего карту отправителя и вам придет СМС с одноразовым паролем. <br><br><i>Вот пример этой формы. (данные уже подставлены из ранее выполненных запросов)</i>";
		
		echo '<br><br><pre>
		&lt;!DOCTYPE html&gt;
		  &lt;head&gt;
		    &lt;title&gt;3DS make&lt;/title&gt;
		    &lt;meta http-equiv="Content-Type" content="text/html; charset=utf-8"&gt;
		  &lt;/head&gt;
		  &lt;body&gt;
			&lt;/div&gt;
		        &lt;div&gt;
		                    &lt;div&gt;
		                            &lt;form id="postForm" action="'.$trans_acsurl.'" method="POST"&gt;
		                                    &lt;input type="hidden" name="PaReq" value="'.$trans_pareq.'"&gt;
		                                    &lt;input type="hidden" name="MD" value="'.$trans_md.'"&gt;
		                                    &lt;input type="hidden" name="TermUrl" value="'.$trans_term_url.'"&gt;
		                                    &lt;input type="submit" value="Сделать перевод"/&gt;
		                            &lt;/form&gt;
		                    &lt;/div&gt;
		                &lt;/div&gt;
			&lt;/div&gt;
		  &lt;/body&gt;
		&lt;/html&gt;
		</pre>';
		
	echo '<br><br><br> 
	После того, как пользователь введет пароль из СМС, его перенаправит обратно на <i>termURL</i>, где перевод будет окончательно завершен (автоматически) после чего будет показана ваша страница, которую вы указывали при регистрации. <br><br><br>Вот сама кнопка.<br><i> Клик на нее перебросит вас на страницу банка выпустившего карту отправителя ($HPAN_SRC)</i><br><br>
	';	
		echo'
		<!DOCTYPE html>
		  <head>
		    <title>3DS make</title>
		    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		  </head>
		  <body>
			</div>
		        <div>
                    <div>
                        <form id="postForm" action="'.$trans_acsurl.'" method="POST">
                            <input type="hidden" name="PaReq" value="'.$trans_pareq.'">
                            <input type="hidden" name="MD" value="'.$trans_md.'">
                            <input type="hidden" name="TermUrl" value="'.$trans_term_url.'">
                            <input type="submit" value="Сделать перевод"/>
                        </form>
                    </div>
                </div>
			</div>
		  </body>
		</html><br><br><br>
		';


?>