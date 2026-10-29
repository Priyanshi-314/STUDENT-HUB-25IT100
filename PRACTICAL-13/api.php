<?php
declare(strict_types=1);
require_once __DIR__ . '/../PRACTICAL-10/session.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
function respond(int $status,array $data):never {http_response_code($status);echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE);exit;}
$user=sh_auth_user();
if(!$user)respond(401,['ok'=>false,'error'=>'Session expired. Sign in again.']);
if(($user['role']??'')!=='admin')respond(403,['ok'=>false,'error'=>'Admin access required.']);
$method=$_SERVER['REQUEST_METHOD']??'GET';
if(!in_array($method,['GET','POST'],true))respond(405,['ok'=>false,'error'=>'Method not allowed.']);
if($method==='POST'){
 $origin=$_SERVER['HTTP_ORIGIN']??'';$host=$_SERVER['HTTP_HOST']??'';
 if($origin!==''&&parse_url($origin,PHP_URL_HOST)!==explode(':',$host)[0])respond(403,['ok'=>false,'error'=>'Invalid request origin.']);
 if(!sh_valid_csrf($_SERVER['HTTP_X_CSRF_TOKEN']??null))respond(419,['ok'=>false,'error'=>'Invalid CSRF token. Refresh page.']);
}
try{
 $db=studentHubMysqli();
 if($method==='GET'){
  $q=trim((string)($_GET['q']??''));$q=mb_substr($q,0,100);$like='%'.$q.'%';
  $s=$db->prepare('SELECT student_id,full_name,email,mobile,program,study_year,gender,status FROM students WHERE full_name LIKE ? OR email LIKE ? ORDER BY student_id DESC LIMIT 100');
  $s->bind_param('ss',$like,$like);$s->execute();$rows=$s->get_result()->fetch_all(MYSQLI_ASSOC);$s->close();respond(200,['ok'=>true,'students'=>$rows]);
 }
 $data=json_decode(file_get_contents('php://input'),true,32,JSON_THROW_ON_ERROR);
 if(!is_array($data))respond(400,['ok'=>false,'error'=>'Invalid JSON.']);
 $action=(string)($data['action']??'');$id=filter_var($data['student_id']??0,FILTER_VALIDATE_INT)?:0;
 if($action==='delete'){
  if($id<1)respond(422,['ok'=>false,'error'=>'Choose a student.']);
  $s=$db->prepare('DELETE FROM students WHERE student_id=?');$s->bind_param('i',$id);$s->execute();$affected=$s->affected_rows;$s->close();
  if(!$affected)respond(404,['ok'=>false,'error'=>'Student not found.']);
 }elseif(in_array($action,['create','update'],true)){
  if($action==='update'&&$id<1)respond(422,['ok'=>false,'error'=>'Choose a student to edit.']);
  $name=trim((string)($data['full_name']??''));$email=trim((string)($data['email']??''));$mobile=trim((string)($data['mobile']??''));$program=trim((string)($data['program']??''));$year=filter_var($data['study_year']??null,FILTER_VALIDATE_INT);$gender=trim((string)($data['gender']??'prefer-not'));$status=(string)($data['status']??'active');
  if($name===''||mb_strlen($name)>100||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($email)>190||!preg_match('/^[0-9]{10}$/',$mobile)||$program===''||mb_strlen($program)>100||!in_array($year,[1,2,3,4],true)||!in_array($status,['active','inactive'],true)||mb_strlen($gender)>20)respond(422,['ok'=>false,'error'=>'Check name, email, 10-digit mobile, program, year (1–4), gender and status.']);
  if($action==='create'){
   $s=$db->prepare('INSERT INTO students(full_name,email,mobile,program,study_year,gender,status) VALUES(?,?,?,?,?,?,?)');$s->bind_param('ssssiss',$name,$email,$mobile,$program,$year,$gender,$status);
  }else{
   $s=$db->prepare('UPDATE students SET full_name=?,email=?,mobile=?,program=?,study_year=?,gender=?,status=? WHERE student_id=?');$s->bind_param('ssssissi',$name,$email,$mobile,$program,$year,$gender,$status,$id);
  }
  $s->execute();$s->close();
 }else respond(400,['ok'=>false,'error'=>'Unknown action.']);
 // Audit successful API operations in the existing authentication audit table.
 $event='api_student_'.$action;sh_audit($event,(int)$user['user_id']);
 respond(200,['ok'=>true,'message'=>'Student '.($action==='create'?'added':($action==='update'?'updated':'deleted')).' successfully.']);
}catch(JsonException $e){respond(400,['ok'=>false,'error'=>'Malformed JSON request.']);}
catch(mysqli_sql_exception $e){error_log('P13 API: '.$e->getMessage());$code=(int)$e->getCode();respond($code===1062?409:($code===1451?409:500),['ok'=>false,'error'=>$code===1062?'Email already exists.':($code===1451?'Student has event registrations and cannot be deleted.':'Database error. Check imported SQL tables.')]);}
catch(Throwable $e){error_log('P13 API: '.$e->getMessage());respond(500,['ok'=>false,'error'=>'Request failed. Check server logs.']);}
