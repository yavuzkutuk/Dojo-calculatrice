<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Calculatrice PHP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.5/js/uikit-icons.min.js"></script>
</head>
<?php
$buttons=[1,2,3,'+',4,5,6,'-',7,8,9,'*','C',0,'.','/','='];
$click='';
if(isset($_GET['history'])){
    $_SESSION['history']='';
}else{
    if(empty($_SESSION['history'])) $_SESSION['history']=[];
}
if(isset($_POST['click']) && in_array($_POST['click'],$buttons)){
    $click=$_POST['click'];
}
$save='';
// Comprendre les expressions régulières https://regex101.com/
if(isset($_POST['save']) && preg_match('@^(?:[\d.]+[*/+-]?)+$@',$_POST['save'],$out)){
    $save=$out[0];
}
$display=$save.$click;
if($click=='C'){
    $display='';
// Comprendre les expressions régulières https://regex101.com/
}elseif($click=='=' && preg_match('@^\d*\.?\d+(?:[*/+-]\d*\.?\d+)*$@',$save)){
    $display.=eval("return $save;");
    $_SESSION['history'][] = $display;
}
?>
<body class="uk-margin-top">
<div class="uk-flex-center" uk-grid>
        <div class="uk-container">
            <form action="/" method="POST">
                <table class="uk-table uk-table-middle" style="width:300px;border:1px solid #CCC;">
                    <tr>
                        <td colspan="4"><input class="uk-input uk-text-right uk-text-large" value="<?php echo $display; ?>" readonly/></td>
                    </tr>
                    <?php
                    // Comprendre array_chunk https://www.php.net/manual/fr/function.array-chunk.php
                    foreach(array_chunk($buttons,4) as $chunk){
                        echo '<tr>';
                        foreach($chunk as $button){
                            echo '<td'.(sizeof($chunk)!=4? ' colspan="4"':'').'><button name="click" class="uk-button uk-button-default" value="'.$button.'">'.$button.'</button></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                    <input type="hidden" name="save" value="<?php echo $display;?>"/>
                </table>
            </form>
        </div>
        <div class="uk-container">
            <table>
                <?php
                if(is_array($_SESSION['history'])){
                    echo '<h3>Historique des calcules <a href="?history=del" class="uk-button uk-button-danger uk-button-smal">vider</a></h3>';
                    foreach ($_SESSION['history'] as $key=>$item){
                        echo '<tr><td>'.$item.'</td></tr>';
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>