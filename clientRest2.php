<html>
    <form action="ajoutPhrase.php" method="POST">
        Entrez une phrase : <input name="phrase" type="text"/>
        <input type="submit" value="submit"/>
    </form>

    <form action="recupPhrase.php" method="POST">
        Combien de phrases voulez-vous récupérer ? <input name="nb" type=number/>
        <input type="submit" value="submit"/>
    </form>

    <form action="recupBestPhrase.php" method="POST">
        Combien des meilleures phrases voulez-vous récupérer ? <input name="nb" type=number/>
        <input type="submit" value="submit"/>
    </form>

<?php

     ////////////////// Cas des méthodes GET et DELETE //////////////////
$result = file_get_contents('http://localhost/archi/serveurRest.php',
   false,
     stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
 );
$data = json_decode($result, true);
$data=$data["data"];
//print_r($data);
foreach($data as $row){

    ?>

    <table>
        <tr>
            <th>id</th>
            <th>phrase</th>
            <th>vote</th>
            <th>date_ajout</th>
            <th>date_modif</th>
            <th>faute</th>
            <th>signalement</th>
            <th>actions</th>
        </tr>

        <tr>
            <td><?php echo htmlspecialchars($row["id"]);?></td>
            <td><?php echo htmlspecialchars($row["phrase"]);?></td>
            <td>
                <?php echo htmlspecialchars($row["vote"]);?>
                <a href=<?php echo "plusVote.php?id=".$row['id']; ?> > +1 </a>
                <a href=<?php echo "moinsVote.php?id=".$row['id']; ?> > -1 </a>
            </td>
            <td><?php echo htmlspecialchars($row["date_ajout"]);?></td>
            <td><?php echo htmlspecialchars($row["date_modif"]);?></td>
            <td><?php echo htmlspecialchars($row["faute"]);?></td>
            <td><?php echo htmlspecialchars($row["signalement"]);?></td>
            <td>
                <a href= <?php echo "supprimer.php?id=".$row['id']; ?> >supprimer</a></br>
                <a href= <?php echo "modifier.php?id=".$row['id'];?> > modifier </a>
            </td>
            </tr>
        </table>
    <?php } ?>
    <style>
        table {
          border-collapse: collapse;
          width: 100%;
      }

      th, td {
          text-align: left;
          padding: 8px;
      }

      tr:nth-child(even) {
          background-color: #f2f2f2;
      }

      th {
          background-color: #4CAF50;
          color: white;
      }
  </style>

  <?php

     ////////////////// Cas des méthodes POST et PUT //////////////////
     /// Déclaration des données à envoyer au Serveur
    

/*{
    "status": 200,
    "status_message": "[R401 API REST] GET request : Read Data OK",
    "data": [
        {
            "id": "162",
            "phrase": "Truc Marrant, Phrase, Humour Blague, Blagues De Merde",
            "vote": null,
            "date_ajout": "2023-02-08 11:49:26",
            "date_modif": "2023-02-08 11:54:26",
            "faute": null,
            "signalement": null
        }
    ]
}
*/

?>

</html>