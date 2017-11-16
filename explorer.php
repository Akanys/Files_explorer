<?php
header('Content-Type: text/html; charset=utf-8');/*Encodage*/

/*
Navigateur de fichier permet de crée , supprimmer , lister les fichier présent sur un serveur
*/

if(!@include('fonction.php')){
echo '<div style="position:absolute; top:45%; left:40%; color:red;">Impossible d\'inclure fonction.php ...</div>';
exit;
}

$DEFAULT="C:/wamp64/www"; /*Default redirection quand le script commence*/
$IMGFOLDER='img/closedfolderd.png'; /*L'icon pour le dossier*/
$IMGFILE='img/filed.png'; /*Icon pour le fichier*/
$IMGCREATEFILE='img/document.png'; /*Fichier pour crée un fichier*/
$IMGUPLOAD='img/Downloadd.png'; /*Fichier pour upload des fichier*/
$IMGCREATEFOLDER='img/newfiles.png'; /*Fichier pour creer un fichier*/
$IMGSEARCH='img/search.png'; /*Recherche*/
$IMGDELETE='img/garbaged.png'; /*Supprimer un dossier*/
$IMGRENAME='img/rename.png'; /*Renommer un fichier*/
$IMGRETURN='img/return.png'; /*Retour en arrère*/

if(!isset($_GET['rename'])&&!isset($_GET['pathren'])&&!isset($_GET['en'])&&!isset($_GET['upload'])&&!isset($_POST['pathupload'])&&!isset($_GET['touch'])&&!isset($_GET['download'])&&/*Verifie si rien n'est appellé*/
!isset($_GET['delete'])&&!isset($_GET['path'])&&!isset($_GET['dir'])&&!isset($_FILES['fichier'])&&!isset($_GET['mkdir'])&&!isset
($_GET['pathmkdir']))
{
	header('location:?dir='.$DEFAULT);
}

if(isset($_GET['upload'])&&isset($_POST)&&!file_exists($_POST['pathupload'].$_FILES['fichier']['name']))
{
$tmp_file = $_FILES['fichier']['tmp_name'];
$name_file = $_FILES['fichier']['name'];

    if( !is_uploaded_file($tmp_file) )
    {
        Erreur('Erreur lors du telechargement !');

		exit;
    }
	
	 if( !move_uploaded_file($tmp_file, $_POST['pathupload'].'/'. $name_file) )
    {

		Erreur('Erreur lors du deplacement du fichier !</div></body></html>');
		exit;
    }

	header('location:'.$_SERVER['HTTP_REFERER']);
}



if(isset($_GET['touch'])&&!empty($_GET['touch'])&&isset($_GET['path'])&&!empty($_GET['path'])) /*Permer de crée un fichier*/
{
 
 if(file_exists($_GET['path'].'/'.$_GET['touch']))
 {
	Erreur('Un fichier porte deja le nom : '.$_GET['touch'].' !');
	exit;
 }
 
 if(!@touch($_GET['path'].'/'.$_GET['touch']))
 {
     Erreur('Erreur l\'ors de la creation du fichier '.$_GET['touch'].'');
     exit;
 }
  
 header('location:'.'?dir='.$_GET['path']); /*Redirection a l'url precedent*/

}

if(isset($_GET['mkdir'])&&!empty($_GET['mkdir'])&&isset($_GET['pathmkdir'])&&!empty($_GET['pathmkdir']))
{
 if(file_exists($_GET['pathmkdir'].'/'.$_GET['mkdir'])&&is_dir($_GET['pathmkdir'].'/'.$_GET['mkdir']))
 {
  Erreur('Erreur un dossier porte deja se nom :'.$_GET['mkdir'].' ...');
  exit;
	}
 
 if(!@mkdir($_GET['pathmkdir'].'/'.$_GET['mkdir'],0755)){
     Erreur('Erreur l\'ors de la création du fichier '.$_GET['mkdir'].'!');
     exit;
 }
  header('location:?dir='.$_GET['pathmkdir']);
}


if(isset($_GET['rename'])&&!empty($_GET['rename'])&&isset($_GET['pathren'])&&!empty($_GET['pathren'])&&isset($_GET['en'])&&!empty($_GET['en']))
{

if(!file_exists($_GET['pathren'].'/'.$_GET['rename']))
{
	Erreur('Fichier '.$_GET['rename'].' Introuvable ...');
	exit;
 }

if(file_exists($_GET['pathren'].'/'.$_GET['en']))
{
	Erreur('Un fichier porte deja le nom : '.$_GET['en'].' ...');
	exit;
	}
 
 if(!@rename($_GET['pathren'].'/'.$_GET['rename'],$_GET['pathren'].'/'.$_GET['en'])){
     Erreur('Erreur pour renommer '.$_GET['rename'].' en '.$_GET['en']);
     exit;
}

  header('location:?dir='.$_GET['pathren']);
}



if(isset($_GET['download'])&&!empty($_GET['download'])&&file_exists($_GET['download'])&&is_file($_GET['download']))/*Telecharge un fichier*/
{
  download($_GET['download']);/*....*/
}

if(isset($_GET['delete'])&&!empty($_GET['delete'])&&file_exists($_GET['delete'])&&is_file($_GET['delete']))/*Supprimé un fichier ...*/
{
  
  if(!@unlink($_GET['delete']))
  {
		Erreur('Erreur l\ors de la suppresion de '.$_GET['delete'].'');
		exit;
  }
  header('location:'.$_SERVER['HTTP_REFERER']);
}

if(isset($_GET['dir'])&&!empty($_GET['dir'])&&file_exists($_GET['dir'])&&is_dir($_GET['dir'])) /*Verifie la variable et bien un repertoire*/
{
$rep=$_GET['dir'];
$rep=str_replace("//","/",$rep);
$handle = @opendir($rep); /* Ouvre le repertoire */

if(!$handle)
  {
	Erreur('Erreur l\'ors de l\'ouverture de '.$rep.' !');
	exit;
}
?>
<!--********************************************** HTML **********************************************-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
    <head>
      
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M"
            crossorigin="anonymous">
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          <link rel="stylesheet" media="screen" type="text/css" title="Design" href="file/style-explorateur.css" />
          <script language="javascript" type="text/javascript" src="file/javascript.js"></script> 
          <title>Explorateur_de_fichier</title>
    </head>

    <body>
      <div id="header" class="container">
        <div class="row justify-content-center">                                                          
          <div class="col-2 col-md-1 nopadding">
            <a href="#" class="icone" onclick="display_('rename');"><img alt="button return" width="50px" title="Retourner en arrière" src="<?php echo $IMGRETURN; ?>" /></a>
          </div>
          <div class="col-2 col-md-1 nopadding">  
            <a href="#" class="icone" onclick="display_('rename');"><img alt="button rename" width="50px" title="Renommer un dossier" src="<?php echo $IMGRENAME; ?>" /> </a>
          </div>
          <div class="col-2 col-md-1 nopadding">
            <a href="#" class="icone" onclick="display_('mkdir');"><img alt="button newfiles" width="50px" title="Creer un dossier" src="<?php echo $IMGCREATEFOLDER; ?>" /></a> 
          </div>
          <div class="col-2 col-md-1 nopadding">
            <a href="#" class="icone" onclick="display_('download');"><img alt="button Download" width="50px" title="Telecharger un fichier" src="img/Download.png" /></a>       
          </div>
          <div class="col-2 col-md-1 nopadding">
            <a href="#" class="icone" onclick="display_('search');"><img alt="button search" width="50px" title="Rechercher un fichier" src="<?php echo $IMGSEARCH; ?>" /></a>
          </div>                 

          <form class="formulaire col-8 col-md-5">
            <input class="champ " type="text" placeholder="Search..." />
          </form>

          <div class=" col-8">
            <div class="filariane" />
            <?php
              $def = "index";
              $dPath = $_SERVER['PHP_SELF'];
              $dChunks = explode("/", $dPath);

              echo('<a class="dynNav" href="/">localhost</a><span class="dynNav"> > </span>');
              for($i=1; $i<count($dChunks); $i++ ){
                echo('<a class="dynNav" href="/');
                for($j=1; $j<=$i; $j++ ){
                  echo($dChunks[$j]);
                  if($j!=count($dChunks)-1){ echo("/");}
                }
                
                if($i==count($dChunks)-1){
                  $prChunks = explode(".", $dChunks[$i]);
                  if ($prChunks[0] == $def) $prChunks[0] = "";
                  $prChunks[0] = $prChunks[0] . "</a>";
                }
                else $prChunks[0]=$dChunks[$i] . '</a><span class="dynNav"> > </span>';
                echo('">');
                echo(str_replace("_" , " " , $prChunks[0]));
              } 
            ?>
            </div>
          </div>
        </div>
      </div>

      <div id="pagedossier" class="container">
          <?php
          while ($f = readdir($handle)) { //Boucle qui enumere tout les fichier d'un repertoire
              $lien=str_replace(" ",'%20',$f); /*Pour les espace fichier*/
            $replien=str_replace(" ",'%20',$rep);/*idem pour les dossier*/
              
            
            if(@is_dir($rep.'/'.$f)){ /*verifie si c'est un repertoire*/
              
              echo '<a href="?dir='.$replien.'/'.$lien.'"><img alt="Dossier" src="'.$IMGFOLDER.'" width="50px"/>'.$f.'</a><br />'; 
            
            }
            elseif(@is_file($rep.'/'.$f)){/*Verifie si c'est bien un fichier*/
            
              echo '<img src="'.$IMGFILE.'" width="50px" alt="Fichier"/>'.$f.'<a href="?delete='.$replien.'/'.$lien.'" onclick="return confirm(\'Supprimer '.$f.' ?\');"><img alt="Supprimer" width="30px" title="/!\Supprimer/!\ " src="'.$IMGDELETE.'" /></a><a href="?download='.$replien.'/'.$lien.'" ><img alt="Telecharger" width="30px" title="Telecharger " src="'.$IMGUPLOAD.'" /></a><br />';
          }
          }
          }

          /*Formulaire Pour crée un fichier */
          echo '<div class="bulle" id="touch" style="display:none;"><form method="get" action="?" >
          <img src="'.$IMGFILE.'"></img><input type="text" name="touch"  title="Fichier a cree" size="30" />
          <input type="hidden" name="path" value="'.$replien.'" />
          </form></div>';

          /*Formulaire pour upload un fichier*/
          echo '<div class="bulle" id="upload" style="display:none;">
          <form method="post" enctype="multipart/form-data" action="?upload">
          <input type="file" name="fichier" size="25">
          <input type="submit" name="upload" value="Go">
          <input type="hidden" name="pathupload" value="'.$replien.'" />
          </form></div>';

          /*Formulaire pour crée un dossier :)*/
          echo '<div class="bulle" id="mkdir" style="display:none;"><form method="get" action="?" >
          <img src="'.$IMGFOLDER.'"></img><input type="text" name="mkdir"  title="Cree dossier" size="30" />
          <input type="hidden" name="pathmkdir" value="'.$replien.'" />
          </form></div>';
          ?>  
      </div>
    </body>
  </html>