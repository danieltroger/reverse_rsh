<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
$address = '192.168.178.41';
$port = 4444;
$lastping = 0;
$stdin = fopen ("php://stdin","r");;
stream_set_blocking($stdin,0);
if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}
socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    exit;
}
if (socket_listen($sock, 5) === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    exit;
}
echo "Waiting for connection" . PHP_EOL;
if (($msgsock = socket_accept($sock)) === false) {
    echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    exit;
}
echo "Someone connected" . PHP_EOL;
// hi
$lastping = time();
socket_set_nonblock($msgsock);
sleep(1);
do {
    $data = fgets($stdin);
    if($data) answer("data:" . base64_encode($data));
    $buf = @socket_read($msgsock, 1048576);
    if (!$buf = trim($buf)) {
        continue;
    }
    if(!empty($buf))
    {
      if ($buf == 'bye') {
          break;
      }
      $s = explode(":",$buf);
      if($s[0] == "ping")
      {
        answer("pong:" . $s[1]);
        $lastping = time();
      }
      if($s[0] == "data")
      {
        echo base64_decode($s[1]) . "> ";
      }
    }
    //if(time()-$lastping > 60){echo "Ping timeout" . PHP_EOL; break;}
    usleep(500000);
} while (true);
socket_close($msgsock);
socket_close($sock);
exit;
function answer($txt)
{
  $txt .= PHP_EOL;
  socket_write($GLOBALS['msgsock'], $txt, strlen($txt));
}
?>
