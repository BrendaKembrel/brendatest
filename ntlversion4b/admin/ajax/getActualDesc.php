
<?
//this is used so that when the user types in the frenchDescription and only sees the escape
//characters, they will be able to click on a button allowing them to visualize the description
//as it would actually be displayed; that is, with the actual accents put in.

$desc = $_POST["desc"];

echo $desc." ".html_entity_decode($desc);

?>