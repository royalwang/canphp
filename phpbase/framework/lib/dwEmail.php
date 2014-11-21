<?php

defined('DWAE_EMAIL_HOST') or define('DWAE_EMAIL_HOST','mail.duowan.com');
defined('DWAE_EMAIL_RELAY_HOST') or define('DWAE_EMAIL_RELAY_HOST','mail.duowan.com');
defined('DWAE_EMAIL_PORT') or define('DWAE_EMAIL_PORT',25);
defined('DWAE_EMAIL_FROM') or define('DWAE_EMAIL_FROM','service@duowan.com');
defined('DWAE_EMAIL_USER') or define('DWAE_EMAIL_USER','service@duowan.com');
defined('DWAE_EMAIL_PASSWORD') or define('DWAE_EMAIL_PASSWORD','yy!@#$duowan)(*&');

/**
 * 电子邮件扩展类
 * 
 * 使用方法
 * 
 * 1. 首先要配置SMTP服务器，host,relayHost, port,user,pass,from
 * 2. 使用sendmail函数发送邮件
 * 	include('dwEmail.php');
 *	$mail = new dwEmail;
 *	$mail->sendMail(收件人地址, 标题, 内容, 邮件类型, 发件人, 补充邮件头);
 * 
 */
class dwEmail {
	public $port;
	public $timeOut = 30;
	public $host = '';
	public $relayHost;
	public $from;
	public $user;
	public $pass;

	public $sock;

	/* Constractor */
	function  __construct() {
		if(defined('DWAE_EMAIL_HOST')) $this->host = DWAE_EMAIL_HOST;
		if(defined('DWAE_EMAIL_RELAY_HOST')) $this->relayHost = DWAE_EMAIL_RELAY_HOST;
		if(defined('DWAE_EMAIL_PORT')) $this->port = DWAE_EMAIL_PORT;
		if(defined('DWAE_EMAIL_FROM')) $this->from = DWAE_EMAIL_FROM;
		if(defined('DWAE_EMAIL_USER')) $this->user = DWAE_EMAIL_USER;
		if(defined('DWAE_EMAIL_PASSWORD')) $this->pass = DWAE_EMAIL_PASSWORD;
		$this->sock = false;
	}

	/**
	 * @param $to         :收信人
	 * @param $subject    :主题
	 * @param $body       :内容
	 * @param $mailType   :邮件类型
	 * @param $from       :发送人
	 * @param $cc         :抄送
	 * @param $bcc        :秘密抄送
	 * @param $addHeaders :补充邮件头
	 * @return boolean
	 */
	public function sendMail($to, $subject = "", $body = "", $mailType = '', $from='', $cc = "", $bcc = "", $addHeaders = "") {
		if($from) $this->from = $from;
		
		$mailFrom = $this->getAddress($this->stripComment($this->from));
		$body = preg_replace("/(^|(\r\n))(\\.)/", "\\1.\\3", $body);
		$header = $addHeaders;
		$header .= "MIME-Version:1.0\r\n";
		if($mailType=="HTML") {
			$header .= "Content-Type:text/html\r\n";
		}
		$header .= "To: ".$to."\r\n";
		if ($cc != "") {
			$header .= "Cc: ".$cc."\r\n";
		}
		$header .= "From: $this->from<".$this->from.">\r\n";
		$header .= "Subject: ".$subject."\r\n";
		$header .= $addHeaders;
		$header .= "Date: ".date("r")."\r\n";
		$header .= "X-Mailer:By Redhat (PHP/".phpversion().")\r\n";
		list($msec, $sec) = explode(" ", microtime());
		$header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mailFrom.">\r\n";
		$TO = explode(",", $this->stripComment($to));
		if ($cc != "") {
			$TO = array_merge($TO, explode(",", $this->stripComment($cc)));
		}

		if ($bcc != "") {
			$TO = array_merge($TO, explode(",", $this->stripComment($bcc)));
		}

		$sent = true;
		foreach ($TO as $rcptTo) {
			$rcptTo = $this->getAddress($rcptTo);
			if (!$this->smtpSockOpen($rcptTo)) {
				$sent = false;
				continue;
			}
			if (!$this->smtpSend($this->host, $mailFrom, $rcptTo, $header, $body)) {
				$sent = false;
			}
			fclose($this->sock);
		}
		echo "<br>";
		echo $header;
		return $sent;
	}

	/* Private Functions */

	function smtpSend($helo, $from, $to, $header, $body = "") {
		if (!$this->smtpPutCmd("HELO", $helo)) return false;
		
		if (!$this->smtpPutCmd("AUTH LOGIN", base64_encode($this->user))) return false;
		
		if (!$this->smtpPutCmd("", base64_encode($this->pass))) return false;
		
		if (!$this->smtpPutCmd("MAIL", "FROM:<".$from.">")) return false;
		
		if (!$this->smtpPutCmd("RCPT", "TO:<".$to.">")) return false;
		
		if (!$this->smtpPutCmd("DATA")) return false;
		
		if (!$this->smtpMessage($header, $body)) return false;

		if (!$this->smtpEom()) return false;

		if (!$this->smtpPutCmd("QUIT")) return false;
		
		return true;
	}

	function smtpSockOpen($address) {
		if ($this->relayHost == "") {
			return $this->sockMx($address);
		} else {
			return $this->sockRelay();
		}
	}

	function sockRelay() {
		$this->sock = @fsockopen($this->relayHost, $this->port, $errno, $errstr, $this->timeOut);
		if (!($this->sock && $this->smtpOk())) {
			$this->smtpDebug("Error: Cannot connenct to relay host ".$this->relayHost."\n");
			return false;
		}
		return true;
	}

	function sockMx($address) {
		$domain = preg_replace("/^.+@([^@]+)$/", "\\1", $address);
		if (!@getmxrr($domain, $MXHOSTS)) {
			return false;
		}
		foreach ($MXHOSTS as $host) {
			$this->sock = @fsockopen($host, $this->port, $errno, $errstr, $this->timeOut);
			if (!($this->sock && $this->smtpOk())) {
				$this->smtpDebug("Warning: Cannot connect to mx host ".$host."\n");
				continue;
			}
			return true;
		}
		return false;
	}

	function smtpMessage($header, $body) {
		fputs($this->sock, $header."\r\n".$body);
		$this->smtpDebug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));
		return true;
	}

	function smtpEom() {
		fputs($this->sock, "\r\n.\r\n");
		$this->smtpDebug(". [EOM]\n");
		return $this->smtpOk();
	}

	function smtpOk() {
		$response = str_replace("\r\n", "", fgets($this->sock, 512));
		$this->smtpDebug($response."\n");
		if (!preg_match("/^[23]/", $response)) {
			fputs($this->sock, "QUIT\r\n");
			fgets($this->sock, 512);
			return false;
		}
		return true;
	}

	function smtpPutCmd($cmd, $arg = "") {
		if ($arg != "") {
			if($cmd=="") $cmd = $arg;
			else $cmd = $cmd." ".$arg;
		}
		fputs($this->sock, $cmd."\r\n");
		$this->smtpDebug("> ".$cmd."\n");
		return $this->smtpOk();
	}

	function stripComment($address) {
		$comment = "/\\([^()]*\\)/";
		while (preg_match($comment, $address)) {
			$address = preg_replace($comment, "", $address);
		}
		return $address;
	}

	function getAddress($address) {
		$address = preg_replace("/([ \t\r\n])+/", "", $address);
		$address = preg_replace("/^.*<(.+)>.*$/", "\\1", $address);
		return $address;
	}

	function smtpDebug($message) {
		if (defined('DEBUG') && DEBUG) {
			echo $message."<br>";
		}
	}

}
