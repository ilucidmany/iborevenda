<?php
ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
session_start();

if ($_SESSION['store_type'] == 2) {
    header("Location: users_mac.php");
}

error_reporting(32767);
$db = new SQLite3("./api/.ansdb.db");
$db->exec("CREATE TABLE IF NOT EXISTS ibo(id INTEGER PRIMARY KEY NOT NULL,mac_address VARCHAR(100),key VARCHAR(100),username VARCHAR(100),password VARCHAR(100),expire_date VARCHAR(100),dns VARCHAR(100),epg_url VARCHAR(100),title VARCHAR(100),url VARCHAR(100), type VARCHAR(100), id_user INT)");
$res = $db->query("SELECT * FROM ibo WHERE id_user = " . $_SESSION['id']);
if (isset($_GET["delete"])) {
    $db->exec("DELETE FROM ibo WHERE id=" . $_GET["delete"]);
    $db->close();
    header("Location: users.php");
}
include "includes/header.php";
echo "<div class=\"modal fade\" id=\"confirm-delete\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">\n    <div class=\"modal-dialog\">\n        <div class=\"modal-content\">\n            <div class=\"modal-header\">\n                <h2>Confirm</h2>\n            </div>\n            <div class=\"modal-body\">\n                Do you really want to delete?\n            </div>\n            <div class=\"modal-footer\">\n                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cancel</button>\n                <a class=\"btn btn-danger btn-ok\">Delete</a>\n            </div>\n        </div>\n    </div>\n</div>\n<main role=\"main\" class=\"col-15 pt-4 px-5\"><div class=\"row justify-text-center\"><div class=\"chartjs-size-monitor\" style=\"position:absolute ; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;\"><div class=\"chartjs-size-monitor-expand\" style=\"position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;\"><div style=\"position:absolute;width:1000000px;height:1000000px;left:0;top:0\"></div></div><div class=\"chartjs-size-monitor-shrink\" style=\"position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;\"><div style=\"position:absolute;width:200%;height:200%;left:0; top:0\"></div></div></div>\n          <div id=\"main\">\n\n          <!-- Page Heading -->\n   <br><h2>Painel do Usuário</h2>\r\n                      <div class=\"input-group \">\r\n\n                        <a button class=\"btn btn-success btn-icon-split\" id=\"button\" href=\"./users_create.php\">\n                        <span class=\"icon text-white-50\"><i class=\"fas fa-check\"></i></span><span class=\"text\">Novo</span>\n                        </button></a>&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n  \r\n    <div class=\"input-group-prepend \">\r\n    <span class=\"input-group-text \" style=\"font-size:24px;color:#1cc88a\"><i class=\"fas fa-search\"></i></span></div>\r\n    <input class=\"form-control\" type=\"text\" id=\"search\" placeholder=\"Pesquise por MAC, Nome ou Usuário...\"  name=\"search_value\"/>\r\n        \r\n                    </div></div>\n\t\t<div class=\"table-responsive\">\n\t\t\t<table  id=\"myTable\" class=\"table table-striped table-sm\">\n\t\t\t<thead class= \"text-primary\">\n\t\t\t\t<tr>\n\t\t\t\t  <th>ID</th>\n\t\t\t\t  <th>MAC</th>\n\t\t\t\t  <th>Usuário</th>\n                  <th>Nome</th>\n\t\t\t\t  <th>Editar</th>\n\t\t\t\t  <th>Excluir</th>\n                </tr>\n              </thead>\n";
$db = new SQLite3("./api/.ansdb.db");
$res = $db->query("SELECT * FROM ibo WHERE id_user = " . $_SESSION['id']);
while ($row = $res->fetchArray()) {
    $playlist_password = $row['playlistpassword'];

    $iid = $row["id"];
    $imac = $row["mac_address"];
    $ikey = $row["key"];
    $iexpire_date = $row["expire_date"];
    if ($idns = $row["dns"] == NULL) {
        $iusername = "listm3u-id" . $row["id"];
        $idns = $row["url"];
    } else {
        $iusername = $row["username"];
        $idns = $row["dns"];
    }
    $iepg = $row["epg_url"];
    $ititle = $row["title"];

    if ($playlist_password) {
        $idns = "****";
        $iepg = "****";
        $ititle = "****";
    }

    echo "              <tbody class=\" text-primary\">\n";
    echo "                  <td>" . $iid . "</td>" . "\n";
    echo "                  <td>" . $imac . "</td>" . "\n";
    echo "                  <td>" . $iusername . "</td>" . "\n";
    echo "                  <td>" . $ititle . "</td>" . "\n";
    echo "                  <td><a class=\"btn btn-icon\" href=\"./users_update.php?update=" . $iid . "\"><span class=\"icon text-white-50\"><i class=\"fa fa-pencil\" style=\"font-size:24px;color:blue\"></i></span></a></td>" . "\n";
    echo "                  <td><a class=\"btn btn-icon delete-btn\" href=\"#\" data-href=\"./users.php?delete=" . $iid . "\" data-toggle=\"modal\" data-target=\"#confirm-delete\"><span class=\"icon text-white-50\"><i class=\"fa fa-trash\" style=\"font-size:24px;color:red\"></i></span></a></td>" . "\n";
    echo "\t\t\t\t</tr>\n\t\t\t</tbody>\n";
}
echo "\t\t\t</table>\n\t\t</div>\n</main>\n\n    <br><br><br>\n";
include "includes/footer.php";
echo "\r\n<script>\r\n\$(\"#search\").keyup(function () {\r\n    var value = this.value.toLowerCase().trim();\r\n\r\n    \$(\"table tr\").each(function (index) {\r\n        if (!index) return;\r\n        \$(this).find(\"td\").each(function () {\r\n            var id = \$(this).text().toLowerCase().trim();\r\n            var not_found = (id.indexOf(value) == -1);\r\n            \$(this).closest('tr').toggle(!not_found);\r\n            return not_found;\r\n        });\r\n    });\r\n});\r\n</script>\n    <script>\n\$('#confirm-delete').on('show.bs.modal', function(e) {\n    \$(this).find('.btn-ok').attr('href', \$(e.relatedTarget).data('href'));\n});\n</script>\n<script>\ndocument.addEventListener('DOMContentLoaded', function () {\n    // Função para confirmar a exclusão\n    function confirmDelete(id) {\n        var confirmation = confirm(\"Você tem certeza de que deseja excluir este registro?\");\n        \n        if (confirmation) {\n            // Redirecionar para a URL de exclusão\n            window.location.href = \"./users.php?delete=\" + id;\n        }\n    }\n\n    // Adicionar evento de clique aos botões de exclusão\n    var deleteButtons = document.querySelectorAll('.delete-btn');\n    deleteButtons.forEach(function (button) {\n        button.addEventListener('click', function (event) {\n            event.preventDefault(); // Impede o comportamento padrão do link\n            var dataHref = this.getAttribute('data-href');\n            confirmDelete(dataHref.split('=')[1]);\n        });\n    });\n});\n</script>\n</body>\n\n</html>";
?>
