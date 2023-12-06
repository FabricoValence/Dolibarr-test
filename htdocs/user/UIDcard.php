<?php

try
{
    $db = new PDO('mysql:host=localhost;dbname=dolibarrdebian;port=3306;charset=utf8', 'root', 'f@br1c0');
}catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
        echo 'non';
}

$rfidCard =$_GET["rfidcard"];
$suprr =$_GET["suprr"];
//var_dump($rfidCard);
//print"$rfidCard";
if ($suprr=="suprr"){
    $query = "update llx_user set card_uid = 'nothing' where rowid ='$id'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $suprr="";
    print"La carte a bien été suprimé";
}

if (strlen($rfidCard)==0){
    //print $id;
    $query = "select card_uid from llx_user where rowid=$id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $uid_card = $row[0]["card_uid"];
    //var_dump($row);
    if ($uid_card!=="nothing"){
        print"L'uid de la carte de l'utilisateur est : '$uid_card'";
        $suprr="suprr";
        print'<form action="';
        print"card.php?id=$id";
        print'" method="get">';
        print "<input type='hidden' name='suprr' value='$suprr'/>";
        print "<input type='hidden' name='id' value='$id'/>";
        print'<input value="Suprimer la carte" type="submit">
    </form>';
    }else{
        print'<form action="';
        print"card.php?id=$id";
        print'" method="get">
        Uid de la carte: <input type="text" name="rfidcard"><br>';
        print "<input type='hidden' name='id' value='$id'/>";
        print'<input value="Enregistrer" type="submit">
    </form>';
    }
}elseif(strlen($rfidCard)>3){
    $uid="";
    foreach (str_split($rfidCard) as $elements){
        //print"$elements <br>";
        if ($elements ==":"){
            
        }else{
            $uid=strval($uid).strtolower(strval($elements));
        }
    }
    $query = "update llx_user set card_uid = '$uid' where rowid=$id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    print "La uid de la carte est '$rfidCard'";
}
?>