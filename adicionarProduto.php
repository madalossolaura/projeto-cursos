<?php 
    require "req/database.php";
    require "req/funcoesProduto.php";
    include "inc/head.php";
    include "inc/header.php";

    try {
        global $conexao;

        $query = $conexao->query("SELECT * FROM usuarios WHERE tipo_usuario_fk = 2;");
        $professores = $query->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $Exception) {
        echo $Exception->getMessage();
    }

    if($_POST) {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $tag = $_POST['tag'];
        $professor = $_POST['professor'];
        $caminhoCompleto = '';

        if ($_FILES) {
            // verificando se não teve erro de upload 
            if ($_FILES["arquivo"]["error"] == UPLOAD_ERR_OK){
             // pegando o nome real do arquivo
             $nomeArquivo = $_FILES["arquivo"]["name"];
             // pegando o nome temporário do arquivo
             $nomeTemp = $_FILES["arquivo"]["tmp_name"];
             // pegando o caminnho até a pasta raiz
             $pastaRaiz = dirname(__FILE__);
             // selecionando a pasta para qual o arquivo será enviado
             $pastaDesejada = "\assets\img\produtos\\";
             // selecionando o caminho completo para ser utilizado na função move_uploaded_file
             $caminhoCompleto = $pastaRaiz . $pastaDesejada . $tag . ".png";
             // movendo o arquivo com a função move_uploaded_file
             move_uploaded_file($nomeTemp, $caminhoCompleto);
            }
        }
        $produto = [
            "nome" => $nome,
            "descricao" => $descricao,
            "preco" => $preco,
            "tag" => $tag,
            "professor" => $professor,
            "imagem" => $tag . ".png"
        ];

        $adicionou = adicionarProduto($produto);
    }
?>
    <div class="page-center">
        <h2>Adicionar Produto</h2>
        <!-- mostra a mensagem de erro caso a variável $erro esteja definida -->
        <?php if (isset($adicionou)  && $adicionou): ?>
            <div class="alert alert-success">
                <span>Produto adicionado com sucesso</span>
            </div>
        <?php endif; ?>
        <form action="adicionarProduto.php" method="post" class="col-md-7" enctype="multipart/form-data">
            <div class="col-xs-12">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome">
            </div>
            <div class="col-xs-12">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control"></textarea>
            </div>
            <div class="col-xs-12">
                <label for="preco">Preço</label>
                <input type="number" class="form-control" name="preco" id="preco">
            </div>
            <div class="col-xs-12">
                <label for="tag">Tag</label>
                <input type="text" class="form-control" name="tag" id="tag">
            </div>
            <div class="col-xs-12">
                <label for="professpr">Professor</label>
                <select name="professor" id="professor" class="form-control">
                    <option disabled selected>Escolha um Professor</option>
                    <?php 
                        if(isset($professores)) {
                            foreach ($professores as $professor) {
                                echo "<option value='". $professor['id_usuario'] ."'>". $professor['nome'] ."</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="col-xs-12" style="margin-top:10px;">
                <label for="inputArquivo" class="btn btn-info">Upload Foto do Produto</label>
                <input type="file" class="hidden" name="arquivo" id="inputArquivo">
            </div>
            <div class="col-xs-12" style="margin-top:10px;">
                <button type="submit" class="btn btn-primary" name="adicionarProduto">Enviar</button>
            </div>
        </form>
    </div>
<?php 
    include "inc/footer.php";
?>