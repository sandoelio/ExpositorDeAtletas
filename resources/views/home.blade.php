@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="my-4">Bem-vindo ao Basquete Piraja</h1>
    <p class="lead">Esta aplicação tem o objetivo de gerenciar atletas cadastrados, oferecendo uma forma prática de acessar informações.</p>
    
    <img src="{{ asset('img/slogan.png') }}" alt="Logo" class="basquete-img"> 

    <div class="mt-4">
        <a href="{{ route('atletas.index') }}" class="btn btn-primary btn-lg">Listar Atletas</a>
         <a href="#" onclick="abrirModal()"class="btn btn-success btn-lg" >
            Administração
        </a>
    </div>

     <!-- Modal -->
        <div id="modalSenha" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">

            <div style="background: white; padding: 20px; border-radius: 10px; width: 90%; max-width: 300px; text-align: center;">
                <h3>Digite a senha</h3>
                <input type="password" id="senhaInput" placeholder="Senha" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;">

                <button onclick="verificarSenha()" style="background-color: #4CAF50; color: white; padding: 10px 15px; border: none; border-radius: 5px; font-size: 14px;">Confirmar</button>

                <button onclick="fecharModal()" style="background-color: #f44336; color: white; padding: 10px 15px; border: none; border-radius: 5px; font-size: 14px; margin-top: 10px;">Cancelar</button>
            </div>
        </div>
</div>

<script>
     function abrirModal() {
            document.getElementById("modalSenha").style.display = "flex";
        }

        function fecharModal() {
            const senhaInput = document.getElementById("senhaInput");
            senhaInput.value = ""; // Limpa o campo de senha
            document.getElementById("modalSenha").style.display = "none";
        }

        function verificarSenha() {
            const senhaCorreta = "san@prj"; // Defina aqui a senha de 8 dígitos
            const senhaInput = document.getElementById("senhaInput");

            if (senhaInput.value === senhaCorreta) {
                senhaInput.value = ""; // Limpa o campo de senha
                senhaInput.style.border = ""; // Remove a borda de erro, caso tenha sido adicionada
                window.location.href = "/atletas/create"; // Redireciona para a rota
            } else {
                // Exibe o erro no input
                senhaInput.style.border = "2px solid red";
                senhaInput.value = ""; // Limpa o valor digitado
            }
        }

        // Garante que o modal esteja fechado no carregamento da página
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("modalSenha");
            if (modal) {
                modal.style.display = "none"; // Fecha o modal ao carregar a página
                const senhaInput = document.getElementById("senhaInput");
                if (senhaInput) {
                    senhaInput.value = ""; // Limpa o campo de senha
                }
            }
        });
</script>
@endsection