<?php
$host="localhost";  //server name
$users="root";    //اسم المستخدم لقاعدة البيانات
$pass="";  // كلمة المرور غالبا فارغه اكسزامب 
$dbname="cake_store"; //اسم قاعدة البيانات اللي انشئتها 

//ربط مع قاعدة البيانات
$conn= new mysqli($host, $users, $pass, $dbname);
 //تحقق من الإتصال
if($conn ->connect_error){
    die("connection failed".$conn->connect_error);
}







