<?php

session_start();
use App\Core\Controller;

class Categorias extends Controller
{

    public function index()
    {

        $categoriaModel = $this->model("Categoria");

        $categorias = $categoriaModel->listarTodas();

        $this->view("categorias/index", $categorias);
    }

    public function create()
    {

        $this->view("categorias/create");
    }

    public function store()
    {
        $erros = $this->validarCampos();

        if (count($erros) > 0) {
            $_SESSION["erros"] = $erros;

            header("location: /categorias/create");

            exit();
        }


        //instanciamos uma categoria inical
        $categoriaModel = $this->model("Categoria");
        //atribuimos a ela a descricao a ser inserida
        $categoriaModel->descricao = $_POST["descricao"];
        //chamamos o método de inserir que retorna a classe alterada ja com o id inserido
        $categoriaModel = $categoriaModel->inserir();
        //deu certo a inserção?
        if ($categoriaModel) {
            $_SESSION["mensagem"] = "Categoria cadastrada com sucesso";
        } else {
            $_SESSION["mensagem"] = "Problemas ao cadastrar a categoria";
        }
        //redirecionamos para a tela de listagem (index)
        header("location: /categorias");
    }

    public function edit($id){
        $categoriaModel = $this->model("Categoria");

        $categoriaModel = $categoriaModel->buscarPorId($id);

        $this->view("/categorias/edit", $categoriaModel);
    }

    public function update($id){
        $erros = $this->validarCampos();

        if(count($erros) > 0){ 
            $_SESSION["erros"] = $erros;

            header("location: /categorias/edit/" . $id);

            exit();
        }
        $categoriaModel = $this->model("Categoria");
        $categoriaModel->id = $id;
        $categoriaModel->descricao = $_POST["descricao"];
        
        if($categoriaModel->atualizar()){
            $_SESSION["mensagem"] = "Categoria atualizada com sucesso";
        }else{
            $_SESSION["mensagem"] = "Problemas ao atualizar categoria";
        }

        header("location: /categorias");

    }
    
    public  function destroy($id){
        $categoriaModel = $this->model("Categoria");

        $categoriaModel->id = $id;

        if($categoriaModel->deletar()){

            $_SESSION["mensagem"] = "Categoria excluída com sucesso";
        }else{

            $_SESSION["mensagem"] = "Problemas ao excluir categoria";

    }
    header("location: /categorias");
    }
    private function validarCampos()
    {
        $erros = [];

        if (!isset($_POST["descricao"]) || $_POST["descricao"] == "") {
            $erros[] = "O campo descrição é obrigatório";
        }

        return $erros;
    }
}
