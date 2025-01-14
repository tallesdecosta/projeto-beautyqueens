<?php

    include '../auth/criar-bd.php';

    $conn = conectarBanco();

    
    if (create_tables($conn) == true) 
    {

        if($_GET['termo'] == '') {
            echo json_encode([]);
            exit;
        }

        $st = $conn -> prepare("SELECT nome, nome_fantasia, produto_id, arquivo FROM produto JOIN pessoa_juridica ON produto.pessoa_id = pessoa_juridica.pessoa_id JOIN imagem ON produto.imagem_id = imagem.imagem_id WHERE nome LIKE CONCAT('%', ?, '%')");


        $st -> bind_param("s", $_GET['termo']);
        $st -> execute();
        
        $result = $st -> get_result();
        $data = [];
        

        while ($row = $result->fetch_assoc()) {
            
            if ($row['arquivo'] !== null) {

                $row['arquivo'] = base64_encode($row['arquivo']);
            }

            $data[] = $row;
        }
        
        $st->close();
        header('Content-Type: application/json');
        echo json_encode($data);
          
    } else 
    {

        echo json_encode(['error' => 'internal server error']);

    }
    

?>