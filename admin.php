<?php 
$lang = json_decode(file_get_contents("langs.json"),1)??[];
$txt = array(
"ar" => array(
    "1" => "✅ - Your account has been recharged with __point__",
    "2" => "__point__ has been deducted from your account",
    "3" => "You have been banned from using the bot",
    "4" => "You have been unbanned from using the bot",
),
"en" => array(
    "1" => "✅ - Your account has been recharged with __point__",
    "2" => "__point__ has been deducted from your account",
    "3" => "You have been banned from using the bot",
    "4" => "You have been unbanned from using the bot",
),
"ru" => array(
    "1" => "✅ - Ваш счет был пополнен на сумму __point__",
    "2" => "__point__ было списано с вашего счета",
    "3" => "Вы были забанены от использования бота",
    "4" => "Вы были разблокированы для использования бота",
),
"fa" => array(
    "1" => "✅ - حساب شما با مبلغ __point__ شارژ شد",
    "2" => "__point__ از حساب شما کسر شد",
    "3" => "شما از استفاده از ربات مسدود شده‌اید",
    "4" => "مسدودیت شما از استفاده از ربات لغو شد",
),
"cht" => array(
    "1" => "✅ - 您的帳戶已加值 __point__",
    "2" => "__point__ 已從您的帳戶中扣除",
    "3" => "您已被禁止使用機器人",
    "4" => "您已被解除禁止使用機器人",
),
"chb" => array(
    "1" => "✅ - 您的账户已充值 __point__",
    "2" => "__point__ 已从您的账户中扣除",
    "3" => "您已被禁止使用机器人",
    "4" => "您已被解除禁止使用机器人",
)
);

if ($text == "/start" || $data == "back") {
	$btn = 
	mkBtn(
		array(
			array(
				"Add Balance" => "addPoint",
				"Deduct Balance" => "takePoint"
			),
			array(
				"Ban User" => "ban",
				"Unban User" => "unban"
			),
			array(
				"Add Agent" => "addWk",
				"Remove Agent" => "remWk"
			),
			array(
				"Agents List" => "wk",
			),
			array(
				"Add Country" => "addContry",
				"Remove Country" => "remContry"
			),			
			array(
				"Statistics" => "stats"
			)				
		)
	
	);
	$info[$id]['action']="";
	saveInfo();
	$tx = "Welcome Admin,\nYour current balance: $balance\n\nAdmin Control Panel:";
	if ($text)
	send($tx,$btn);
	else
	edit($tx,$btn);
} else if ($data == "addPoint") {
	$tx = "Please send the User ID:";
	$info[$id]['action']="addPointId";
	saveInfo();
	edit($tx,$back);
} else if ($text && ($info[$id]['action'] == "addPointId") ){
	if(!isset ($points[$text])) {
		$tx = "User ID not found.";
		send($tx,$back);
	} else {
		$info[$id]['idPoint']  = $text;
		$info[$id]['action']="addPoint";
		saveInfo();
		$tx = "Send the amount you want to add:";
		send($tx,$back);
	}
} else if ($text && $info[$id]['action'] == "addPoint") {
	if( is_numeric($text) && $text > 0 ) {
		$points[$info[$id]['idPoint']] += ($text);
		savePoint();
		$tx = "Balance added successfully.";
		send($tx,$back);
		$tx=str_replace("__point__",$text,$txt[$lang[$info[$id]['idPoint']]][1]);
		send($tx,null,$info[$id]['idPoint']);
		$info[$id]['idPoint']  = "";
		$info[$id]['action']="";
		saveInfo();
	} else {
		$tx = "Please send a number greater than zero.";
		send($tx,$back);
	}
}else if ($data == "takePoint") {
	$tx = "Please send the User ID:";
	$info[$id]['action']="takePointId";
	saveInfo();
	edit($tx,$back);
} else if ($text && ($info[$id]['action'] == "takePointId") ){
	if(!isset ($points[$text])) {
		$tx = "User ID not found.";
		send($tx,$back);
	} else {
		$info[$id]['idPoint']  = $text;
		$info[$id]['action']="takePoint";
		saveInfo();
		$tx = "Send the amount you want to deduct:";
		send($tx,$back);
	}
} else if ($text && $info[$id]['action'] == "takePoint") {
	if( is_numeric($text) && $text > 0 ) {
		$points[$info[$id]['idPoint']] -= ($text);
		savePoint();
		$tx = "Balance deducted successfully.";
		send($tx,$back);
		$tx=str_replace("__point__",$text,$txt[$lang[$info[$id]['idPoint']]][2]);
		send($tx,null,$info[$id]['idPoint']);
		$info[$id]['idPoint']  = "";
		$info[$id]['action']="";
		saveInfo();
	} else {
		$tx = "Please send a number greater than zero.";
		send($tx,$back);
	}
} else if ($data == "ban") {
	$tx = "Please send the User ID to ban:";
	$info[$id]['action']="ban";
	saveInfo();
	edit($tx,$back);
} else if ($text && $info[$id]['action'] == "ban") {
	$tx = "User banned successfully.";
	$info[$id]['action']="";
	saveInfo();
	$bans[$text]=$text;
	saveBans();
	send($tx,$back);
	$tx = $txt[$lang[$text]][3];
	send($tx,null,$text);
}else if ($data == "unban") {
	$tx = "Please send the User ID to unban:";
	$info[$id]['action']="unban";
	saveInfo();
	edit($tx,$back);
}else if ($text && $info[$id]['action'] == "unban") {
	$tx = "User unbanned successfully.";
	$info[$id]['action']="";
	saveInfo();
	$bans[$text]=null;
	saveBans();
	send($tx,$back);
	$tx = $txt[$lang[$text]][4];
	send($tx,null,$text);
} else if ($data == "addWk") {
	$tx = "Send the Name in the first line and the Username in the second line:";
	$info[$id]['action']="addWk";
	saveInfo();
	edit($tx,$back);
} else if ($text && $info[$id]['action']=="addWk") {
	$extx = explode ("\n",$text);
	if(count ($extx) == 2) {
		$info['bot']['wk'] [] = array(
			"name" => $extx[0],
			"user" => $extx[1],
		);
		$info[$id]['action']="";
		saveInfo ();
		$tx="Agent added successfully.";
		send($tx,$back);
	} else {
		$tx = "Error: Send the Name in the first line and the Username in the second line.";
		send($tx,$back);
	}
} else if ($data == "remWk" ) {
	$btn = array();
	$btn[]= 
		array(
			"Name" => "ntn",
			"Username" => "ntn"
		);
	
	foreach ($info['bot']['wk'] as $k => $v ) {
		$d= "remWk-{$k}";
		$btn[] = 
			array(
				"{$v['name']}"??""=> $d,
				"{$v['user']}"??"" => $d,
			);
		
	}
	$btn[]=array (
			"Back" => "back"
		);
	$tx ="Select the Agent you want to remove:";
	edit($tx, mkBtn ($btn));
}else if (preg_match("/remWk\-/",$data)) {
	$info['bot']['wk'][explode ("-",$data)[1]]=null;
	unset($info['bot']['wk'][explode ("-",$data)[1]]);
	saveInfo ();
	$tx ="Agent removed successfully.";
	edit($tx, mkBtn ($btn));
} else if ($data == "wk") {
	$btn = array();
	$btn[]= 
		array(
			"Name" => "ntn",
			"Username" => "ntn"
		);
	
	foreach ($info['bot']['wk'] as $k => $v ) {
		$btn[] = 
			array(
				"{$v['name']}"??""=> "ntn",
				"{$v['user']}"??"" => "ntn",
			);
	}
	$btn[]=array (
			"Back" => "back"
		);
	$tx ="Current Agents List:";
	edit($tx, mkBtn ($btn));
} else if ($data == "addContry" || $exData[0] == 'next' || $exData[0] == 'before') {
	$get = $api->getCountries();
	$tx="Available Countries:\n";
	if ($data == "addContry" ) {
		$start = 0;
	} else if ($exData[0] == 'next') {
		$start= $exData[1];
		if ($start > count ($get)) {
			bot('answercallbackquery',[
				'callback_query_id'=>$update->callback_query->id,
				'show_alert'=>true,
				'text' => "No more pages."
			]);
			exit;
		}
	} else if ( $exData[0] == 'before') {
		$start= $exData[1];
		if($start >= 30) {$start -=30;}
		else if ($start > 0) { $start = 0;}
		else  {
			bot('answercallbackquery',[
				'callback_query_id'=>$update->callback_query->id,
				'show_alert'=>true,
				'text' => "This is the first page."
			]);
			exit;
		}
	}	
	$end = $start + 30;
	$btn =array();
	$bt=array();
	$count=-1;
	$a=1;
	$btn[]=[['text' => "Country | Cost",'callback_data' => "test" ],['text' => "Status",'callback_data' => "change"]];
	foreach ($get as $k => $p) {
		$count++;
		if($count < $start) continue;
		else if ($count >= $end) break;
		$z = isset($contries[$k])? "✅" : "❌";
		if($a%2==0) {
			$bt[]=['text' => "{$names[$k]} | $p",'callback_data' => "add#$k#$data" ];
			$btn[]=$bt;
			$bt=[];
		} else {
			$bt[]=['text' => "{$names[$k]} | $p",'callback_data' => "add#$k#$data" ];
		}
		$a++;
	}
	if(count($bt)>0) $btn[]=$bt;
	$btn[]=array(
		['text' => "⏮️ Previous",'callback_data' => "before#{$start}" ],
		['text' => "Next ⏭️",'callback_data' => "next#{$end}" ],
	);
	$btn[]=array(
		['text' =>"🔙 Back",'callback_data' => "back" ],
	);
	
	edit($tx,$btn);
} else if ($data == "stats") {
	$a=$stats['all']['trybuy']??0;
	$b= $stats['all']['buy']??0;
	$tx = "Statistics:\nTotal purchase attempts: $a\nSuccessful purchases: $b";
	edit ($tx,$back);
} else if ($exData[0] == "add") {
	$info[$id]['action']="addContry";
	$info[$id]['contry']=$exData[1];
	saveInfo();
	$tx = "Please send the selling price:";
	$btn =mkBtn (
		array(
			"🔙 Back" => $exData[2]
		)
	);
	edit($tx,$btn);
} else if ($text && $info[$id]['action']=="addContry") {
	if ( is_numeric($text) && $text > 0 ) {
		$tx = "Country added successfully.";
		$contries[$info[$id]['contry']]=$text;
		$info[$id]['action']="";
		$info[$id]['contry']="";
		saveInfo();
		saveContries ();
	} else {
		$tx="Please send a numeric value greater than zero.";
	}
	send($tx,$back);
} else if ($data == "remContry" || $exData[0] == 'NEXT' || $exData[0] == 'BEFORE') {
	$tx="Countries List:\n";
	if ($data == "remContry") {
		$start = 0;
	} else if ($exData[0] == 'NEXT') {
		$start= $exData[1];
		if ($start > count ($contries)) {
			bot('answercallbackquery',[
				'callback_query_id'=>$update->callback_query->id,
				'show_alert'=>true,
				'text' => "No more pages."
			]);
			exit;
		}
	} else if ( $exData[0] == 'BEFORE') {
		$start= $exData[1];
		if($start >= 30) {$start -=30;}
		else if ($start > 0) { $start = 0;}
		else  {
			bot('answercallbackquery',[
				'callback_query_id'=>$update->callback_query->id,
				'show_alert'=>true,
				'text' => "This is the first page."
			]);
			exit;
		}
	}	
	$end = $start + 30;
	$btn =array();
	
	$tx="Select the Country you want to remove:";
	$bt=array();
	$count=-1;
	$a=0;
	foreach ($contries as $k => $p) {
		$count++;
		if($count < $start) continue;
		else if ($count >= $end) break;	
		if($a%2==0) {
			$btn[]=$bt;
			$bt=[];
			$bt[]=['text' => "{$names[$k]} | $p",'callback_data' => "remove#$k"];
		} else {
			$bt[]=['text' => "{$names[$k]} | $p",'callback_data' => "remove#$k" ];
		}
		$a++;
	}
	if(count($bt)>0) $btn[]=$bt;
	$btn[]=array(
		['text' => "⏮️ Previous",'callback_data' => "BEFORE#{$start}" ],
		['text' => "Next ⏭️",'callback_data' => "NEXT#{$end}" ],
	);
	$btn[]=array(
	['text' => "🔙 Back",'callback_data' => "back" ],
	);
	edit($tx,$btn);
} else if ($exData[0] == "remove") {
	$contries[$exData[1]]=null;
	unset($contries[$exData[1]]);
	saveContries ();
	$tx="Country removed successfully.";
	edit($tx,$back);
}
