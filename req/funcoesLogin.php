<?php 
    function cadastrarUsuario($usuario) {
        try {
            global $conexao;
            $query = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, tipo_usuario_fk) VALUES (:nome, :email, :senha, 3)");

            $query->execute([
                ':nome' => $usuario['nome'],
                ':email' => $usuario['email'],
                ':senha' => $usuario['senha']
            ]);

            $usuario = $query->fetchAll(PDO::FETCH_ASSOC);

            $conexao = null;
        } catch(PDOException $Exception) {
            echo $Exception->getMessage();
        }

        return true;
    }

    function logarUsuario($email, $senha) {
       $infoLogado  = false;
        
       try {
            global $conexao;

            $query = $conexao->prepare("SELECT * FROM usuarios WHERE email = :email");

            $query->execute([
                ':email' => $email
            ]);

            $usuario = $query->fetch(PDO::FETCH_ASSOC);

            if($usuario['email'] == $email && password_verify($senha, $usuario["senha"])) {
                $infoLogado = [
                    "nomeUsuario" => $usuario['nome'],
                    "tipoUsuario" => $usuario['tipo_usuario_fk']
                ];
            }

       } catch (PDOException $Exception) {
            echo $Exception->getMessage();
       }

       return $infoLogado;
    }
?>