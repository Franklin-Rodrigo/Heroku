<?php 
require_once("../connection/connection.php");
$conection = new Connection();
$con = $conection->getConnection();


 $target_dir = "";
$target_file = $target_dir . basename($_FILES["fileToUpload"]['name']);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "Arquivo é uma imagem - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "O arquivo escolhido deve ser uma imagem.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "<br>Desculpe, renomeie o arquivo pois já existe um com este nome.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "<br>Desculpe, arquivo muito grande.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "<br>Desculpe, apenas arquivos de IMAGEM (JPG, JPEG, PNG, GIF etc.)";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<br>Desculpe, seu arquivo não foi carregado.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          $dir_to_db = "";
          $dir_to_db = $dir_to_db . basename($_FILES["fileToUpload"]['name']); 
          var_dump($dir_to_db);
          $sql_insert = "INSERT INTO report2 (name,whatsapp,title,r_adress,descript,imgdir) VALUES (:nome,:whats,:title,:r_adress,:descript,:url)";
          
          $stm_insert = $con->prepare($sql_insert);
        $stm_insert->bindParam(':nome', $_POST['name']);
        $stm_insert->bindParam(':r_adress', $_POST['r_adress']);
          $stm_insert->bindParam(':whats',$_POST['whatsapp']);
          $stm_insert->bindParam(':title',$_POST['title']);
          $stm_insert->bindParam(':descript', $_POST['descript']);
          $stm_insert->bindParam(':url',$dir_to_db);
          try{
            $stm_insert->execute();
            echo "Cadastrado com sucesso,"; 
            echo " a imagem ". basename( $_FILES["fileToUpload"]["name"]). " foi carregada com sucesso.";
            $sql_select = "select count(id) from report2";
            $stm_select = $con->prepare($sql_select);
            $stm_select->execute();
            $row2 = $stm_select->fetchAll();
            foreach ($row2 as $key => $value) {
                echo "<h3> <br><center><font size='25'>Obrigado por Relatar um problema, ".$_POST['name']."<br>Número de Relatos: " . $value['count(id)'] . "</font></center></h3>";
            }
            
          }catch(PDOException $e){
              echo $e->getMessage();
          }
        
    } else {
        echo "Desculpe, houve um erro inesperado.";
    }
}
?>