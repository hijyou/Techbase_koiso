<?php
    error_reporting(1);
    
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    
    $stmt = $pdo->query($sql);
        
    $filename="mission_5.txt";
    $date = date("Y年m月d日 H時i分s秒");            
    $num = 1;
            
    //削除変数
    $del_num = $_POST["del_num"];
    $del_password = $_POST["del_password"];
            
    //編集変数
    $up_num = $_POST["up_num"];
    $up_password = $_POST["up_password"];
    
    //投稿変数
    $input_name = $_POST["input_name"];
    $input_comment = $_POST["input_comment"];
    $input_password = $_POST["input_password"];

    //削除
    //削除のフォームに何か入っていたら
        if ($del_num != '' && $del_password != ''){
            $id = $del_num; // idがこの値のデータだけを抽出したい、とする
            $sql = 'SELECT * FROM tbtest WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                if($row['fpassword']==$del_password){
                    $sql = 'delete from tbtest where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    echo "削除に成功しました";
                }
                else{
                    echo "削除に失敗しました<br>もう一度投稿番号とパスワードを確認してください";
                }
            }
        }
            

            
        //編集
        //編集のフォームに何か入っていたら
        else if ($up_num != '' && $up_password != ''){
            $id = $up_num; // idがこの値のデータだけを抽出したい、とする
            $sql = 'SELECT * FROM tbtest WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                if($row['id']==$up_num&& $row['fpassword']==$up_password){
                    $up_name=$row['name'];
                    $up_comment=$row['comment'];
                    $input_password=$row['fpassword'];
                }
            }
        }
        

    

    
    
        //投稿
        if ($input_name != '' && $input_comment != '' && $input_password != ''){ 
            //再投稿
            if(isset($_POST["input_num"]) && $_POST["input_num"] != ''){
                $id = $_POST["input_num"]; //変更する投稿番号
                $name = $input_name;
                $comment = $input_comment;
                $fpassword = $input_password;//変更したい名前、変更したいコメントは自分で決めること
                $sql = 'UPDATE tbtest SET name=:name,comment=:comment ,fpassword=:fpassword WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':fpassword', $fpassword, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
                echo "編集を受け付けました！<br><br>";
                    $input_password = NULL;
            }


            else{
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, fpassword) VALUES (:name, :comment, :fpassword)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':fpassword', $comment, PDO::PARAM_STR);
            $name = $input_name;
            $comment = $input_comment;
            $fpassword = $input_password;//好きな名前、好きな言葉は自分で決めること
            $sql -> execute();
                    echo $comment."を受け付けました！<br><br>";
                    $input_password = NULL;
            }    
            
        }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>mission_3-05</title>
    </head>
    <body>
        <form action="" method="post">
                      <input type="hidden" name="input_num" placeholder="投稿番号" value="<?php echo $up_num; ?>"><br>
            名　　前　<input type="text" name="input_name" value="<?php echo $up_name; ?>"><br>
            コメント　<input type="text" name="input_comment" value="<?php echo $up_comment; ?>"><br>
            パスワード<input type="password" name="input_password" value="<?php echo $input_password; ?>"><br>
            <input type="submit" name="送信"><br><br>
            削除番号　<input type="text" name="del_num"><br>
            パスワード<input type="password" name="del_password"><br>
            <input type="submit" name="削除" value="削除"><br><br>
            編集番号　<input type="text" name="up_num"><br>
            パスワード<input type="password" name="up_password"><br>
            <input type="submit" name="編集" value="編集">
        </form>
        <hr>
        <?php
            //掲示板表示
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].'<br>';
                echo "<hr>";
            }
        ?>

    </body>
</html>