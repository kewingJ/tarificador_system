<?php
    include_once '../includes/config.php';
    include_once '../includes/security.php';

    session_start();
    require_once '../includes/auth_check.php';
    require_web_auth(null, '../index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Agenda</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: monospace;
      background: #f4f7fa;
      margin: 0;
      padding: 0;
    }
    .header {
      background: #007bff;
      padding: 20px;
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header h1 {
      margin: 0;
    }
    .search-box {
      margin: 20px;
      text-align: right;
    }
    .search-box input {
      padding: 10px;
      width: 300px;
      max-width: 90%;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      padding: 20px;
    }
    .card {
      background: #e9ecef;
      padding: 15px;
      display: flex;
      align-items: center;
      border-radius: 10px;
    }
    .avatar {
      width: 70px;
      height: 70px;
      border-radius: 10px;
      margin-right: 15px;
    }
    .info {
      flex: 1;
    }
    .info strong {
      display: block;
      font-size: 1.1em;
      color: #333;
    }
    .info small {
      display: block;
      margin-bottom: 5px;
      color: #666;
    }
    footer {
      text-align: center;
      padding: 20px;
      border-top: 1px solid #ccc;
      color: #666;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>📘 Agenda</h1>
    <div>
      <input type="text" id="search" placeholder="Search..." onkeyup="filterContacts()" />
    </div>
  </div>

  <div class="container" id="contact-list">

  <?php
  $query = mysqli_query($link, "SELECT * FROM tbla_book_phonebook
                    INNER JOIN tbla_book_tipo_extencion
                    ON tbla_book_phonebook.type = tbla_book_tipo_extencion.id_tipo_ex
                    WHERE activo_p = 1 ORDER BY tbla_book_phonebook.id_phonebook DESC");
                  $i = 1;
                  while($row = mysqli_fetch_array($query))
                  {
                    $imagen = "";
                    if($i%2==0){
                      $imagen = "../img/avatar1.png";
                    } else {
                      $imagen = "../img/avatar2.png";
                    }
                    echo '
                    <!-- Contact 1 -->
                    <div class="card">
                      <img class="avatar" src="'.$imagen.'" alt="avatar">
                      <div class="info">
                        <strong>'.$row['first_name'].'</strong>
                        <small>'.$row['last_name'].'</small>
                        <small>'.$row['phone_number'].'</small>
                        <small><strong>Indice:</strong> '.$row['account_index'].'</small>
                      </div>
                    </div>
                    ';
                    $i++;
                  }
  ?>
  </div>

  <footer></footer>

  <script>
    function filterContacts() {
      const input = document.getElementById('search').value.toLowerCase();
      const cards = document.querySelectorAll('.card');

      cards.forEach(card => {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(input) ? 'flex' : 'none';
      });
    }
  </script>
</body>
</html>
