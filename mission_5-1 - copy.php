<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <h1>好きな映画を語り合おう!</h1>
    <form action = ""method = "post">
        <input type = "text" name = "str"  placeholder = "映画名を入力してください">
        <input type = "text" name = "name" placeholder = "名前を入力してください">
        <input type = "text" name = "pass0" placeholder = "パスワードを入力してください">
        <input type = "submit" value = "送信"><br>
        <br>
        削除対象番号<br>
        <input type = "number" name = "delete" placeholder = "番号を入力してください">
        <input type = "text" name = "pass1" placeholder = "パスワードを入力してください">
        <input type = "submit" value= "削除"><br>
        <br>
        編集対象番号<br>
        <input type = "number" name = "edit" placeholder = "番号を入力してください">
        <input type = "text" name = "pass2" placeholder = "パスワードを入力してください">
        <input type = "submit" value = "編集"><br>
        <br>
        <h3>投稿一覧</h3>
        投稿番号　  名前　  好きな映画　  投稿日時
        <hr>
    </form>
<?php


	// DB接続設定
	$dsn = "データベース名"
	$user = "ユーザー名";
	$password = "パスワード";
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//テーブル作成（テーブル名：keijiban01）
    $sql = "CREATE TABLE IF NOT EXISTS keijiban01"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "dt datetime,"
	. "pass  char(32)"
	.");";
	$stmt = $pdo->query($sql);
	
	//データを入力
	if (!empty($_POST["str"] && $_POST["name"])){ 
	    	//データを編集
	  if (!empty($_POST["edit"])){
	    if(!empty($_POST["pass2"])){
	     $sql = 'SELECT * FROM keijiban01';
  	     $stmt = $pdo->query($sql);
     	 $results = $stmt->fetchAll();
    	 foreach ($results as $row){   //パスワードとidが一致するか調べる
	      if ($_POST["pass2"] ==  $row['pass'] && $_POST["edit"] ==  $row['id'] ){
         	$id = $_POST["edit"]; //変更する投稿番号
	        $name = $_POST["name"] ;
			$comment = $_POST["str"];
			$dt = date("Y-m-d H:i:s");//変更したい名前、変更したいコメント、変更したい投稿日時
    	    $sql = 'UPDATE keijiban01 SET name=:name,comment=:comment,dt=:dt WHERE id=:id';
	        $stmt = $pdo -> prepare($sql);
	        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt -> bindParam(':dt', $dt, PDO::PARAM_STR);
	        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt -> execute();
	      }
    	 }
	    }
	  }else{ //新規投稿
	    if(!empty($_POST["pass0"])){
      	  $sql = $pdo -> prepare("INSERT INTO keijiban01 (name, comment, pass, dt) VALUES (:name, :comment, :pass, :dt)");
     	  $sql -> bindParam(':name', $name, PDO::PARAM_STR);
		  $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		  $sql -> bindParam(':dt', $dt, PDO::PARAM_STR);
	      $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	      $name = $_POST["name"];
		  $comment = $_POST["str"]; 
		  $dt = date("Y-m-d H:i:s");
		  $pass= $_POST["pass0"];
		  
		   //新規投稿したい名前とコメントとパスワードと時間
	      $sql -> execute();
	    }
	  }
	}
    
    //データを削除
    if (!empty($_POST["delete"] && $_POST["pass1"])){
      $sql = 'SELECT * FROM keijiban01';
  	  $stmt = $pdo->query($sql);
	  $results = $stmt->fetchAll();
      foreach ($results as $row){   //パスワードとidが一致するか調べる
	    if ($_POST["pass1"] ==  $row['pass'] && $_POST["delete"] ==  $row['id']){
	     $id = $_POST["delete"];//削除したい番号
	     $sql = 'delete from keijiban01 where id=:id';
	     $stmt = $pdo->prepare($sql);
	     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	     $stmt->execute();
        }  
      }
    }
    	
	//データを表示
	$sql = 'SELECT * FROM keijiban01';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['dt'].'<br>';
     	echo "<hr>";
	}	
?>
</body>
</html>