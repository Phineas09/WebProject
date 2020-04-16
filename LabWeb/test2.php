<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">

<style>
  thead {
      color:green;
      white-space: wrap;
  }
  tbody {
      text-align: center;    
      color:blue;

  }

  table, th, td {
      text-align: center;
      border: 1px solid black;
  }
</style>
</head>
<?php

    //Am facut si un user special pentru laboratoare

    $servername = 'localhost';
    $username = "laboratorWeb";
    $password = "";
    $dbname = "weblab";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error) {
        die("Eroare la conectare:" . $conn->connect_error);
    }

    $companii = "
    create table if not exists companii (
      id integer auto_increment primary key,
      numar integer,
      an integer not null,
      sef_companie varchar(100)
    );";

    $pluton = "
      create table if not exists plutoane (
        id integer auto_increment primary key,
        numar integer,
        nr_studenti integer,
        sef_pluton varchar(50),
        id_companie integer,
        constraint fk_is_companie_name_name foreign key (id_companie) references companii(id)
      );";

    $studenti = "
    CREATE TABLE If NOT EXISTS studenti (
        id integer auto_increment primary key,
        cnp varchar(14) not null unique,
        nume varchar(50) not null,
        prenume varchar(50) not null,
        email varchar(100),
        id_pluton integer,
        constraint fk_studenti_pluton foreign key (id_pluton) references plutoane(id)
    );";
    

    mysqli_query($conn, $companii);
    mysqli_query($conn, $pluton);
    mysqli_query($conn, $studenti);


?>
<body id="body">


<table style="width:50%;">
<thead>
  <tr>
    <th>ID</th>
    <th>Nume</th>
    <th>Prenume</th>
    <th>Email</th>
    <th>Pluton</th>
    <th>An</th>
    <th>Sef Pluton</th>
    <th>Sef Companie</th>
  </tr>
</thead> 
<tbody>
  <?php

      $sql = "SELECT student.id as id, 
                student.nume as nume, 
                student.prenume as prenume, 
                student.email as email, 
                pl.numar as pluton, 
                cp.an as an, 
                pl.sef_pluton as sef_pluton, 
                cp.sef_companie as sef_companie
            from studenti as student inner join plutoane as pl on student.id_pluton = pl.id 
                inner join companii as cp on pl.id_companie = cp.id;";

      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>" . $row["id"]. "</td>
            <td>" . $row["nume"] . "</td>
            <td>" . $row["prenume"] . "</td>
            <td>" . $row["email"] . "</td>
            <td>" . $row["pluton"] . "</td>
            <td>" . $row["an"] . "</td>
            <td>" . $row["sef_pluton"] . "</td>
            <td>" . $row["sef_companie"] . "</td></tr>";
        }
      }
      mysqli_close($conn);

  ?>
 
</tbody>  
</table>
</body>
</html>
