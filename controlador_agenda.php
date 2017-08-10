<?php
function abrirArquivo(){//Pegar arquivo "contatos.json"
    $contatosAuxiliar = file_get_contents('contatos.json');
    $contatosAuxiliar = json_decode($contatosAuxiliar, true);
    return $contatosAuxiliar;
}
function salvarArquivo($contatosAuxiliar){
    $contatosJson = json_encode($contatosAuxiliar, JSON_PRETTY_PRINT);
    file_put_contents('contatos.json', $contatosJson);

    header("Location: index.phtml");
}
function cadastrar($nome, $email, $telefone){ //função para cadastrar um contato

    $contatosAuxiliar = abrirArquivo();
    $contato = [
        'id'       => uniqid(),
        'nome'     => $nome,
        'email'    => $email,
        'telefone' => $telefone
    ];

    array_push($contatosAuxiliar, $contato);
    salvarArquivo($contatosAuxiliar);

}

function pegarContatos($busca = null){ //Função pegarContatos pega todos os contatos do arquivo contatos.json

    $contatosAuxiliar = abrirArquivo();
    $contatosEncontrados = [];
    if ($busca == null OR $busca == "") {
        $contatosEncontrados = $contatosAuxiliar;
    } else {
        foreach ($contatosAuxiliar as $contato) {
            if (strtolower($contato['nome']) == strtolower($busca)) {
                $contatosEncontrados[] = $contato;
            }
        }
    }
    return $contatosEncontrados;
}
function excluirContato($id){ //Função para excluir os contatos
    $contatosAuxiliar = abrirArquivo();
    print_r($contatosAuxiliar);

    foreach ($contatosAuxiliar as $posicao => $contato){ //iteração
        if($id == $contato['id']) {
            unset($contatosAuxiliar[$posicao]);

        }
    }

    salvarArquivo($contatosAuxiliar);

}

function editarContato($id){ //Função para editar o contato

    $contatosAuxiliar = abrirArquivo();
    foreach ($contatosAuxiliar as $contato){ //iteração
        if ($contato['id'] == $id){
            return $contato;
        }
    }
}
function SalvarContatoEditado($id, $nome, $email, $telefone){ //Função para Salvar o contato que foi editado
    $contatosAuxiliar = abrirArquivo();
    foreach ($contatosAuxiliar as $posicao => $contato){ //iteração
        if ($contato['id'] == $id){

            $contatosAuxiliar[$posicao]['nome']     = $nome;
            $contatosAuxiliar[$posicao]['email']    = $email;
            $contatosAuxiliar[$posicao]['telefone'] = $telefone;
            break;
        }
    }
    salvarArquivo($contatosAuxiliar);
}

if ($_GET['acao'] == 'cadastrar') {
    cadastrar($_POST['nome'], $_POST['email'], $_POST['telefone']);
} elseif ($_GET['acao'] == 'excluir'){
    excluirContato($_GET['id']);
} elseif ($_GET['acao'] == 'editar') {
    SalvarContatoEditado($_POST['id'], $_POST['nome'], $_POST['email'], $_POST['telefone']);
}