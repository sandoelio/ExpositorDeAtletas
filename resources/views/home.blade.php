@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-90 text-center">
    <div>
        <h2 class="my-4">Bem-vindo a vitrine de atletas</h2>
        <h6>Interessado em fazer parte! Entre em contato.</h6>
        
        <!-- Logo responsiva -->
        <img src="{{ asset('img/LOGO1.png') }}" alt="Logo" class="basquete-img"> 

        <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('atletas.index') }}" class="btn-custom">Listar Atletas</a>
            <a href="#" onclick="abrirModal()" class="btn-custom">Administração</a>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modalSenha" style="display: none; position: fixed; top: 0; left: 0; 
     width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); 
     justify-content: center; align-items: center; z-index: 1000;">

    <div style="background: white; padding: 20px; border-radius: 10px; 
         width: 90%; max-width: 300px; text-align: center;">
        <h3>Digite a senha</h3>
        <input type="password" id="senhaInput" placeholder="Senha" 
               style="width: 100%; padding: 10px; margin-bottom: 15px; 
               border-radius: 5px; border: 1px solid #ccc;">

        <button onclick="verificarSenha()" 
                style="background-color: #4CAF50; color: white; padding: 10px 15px; 
                border: none; border-radius: 5px; font-size: 14px;">Confirmar</button>

        <button onclick="fecharModal()" 
                style="background-color: #f44336; color: white; padding: 10px 15px; 
                border: none; border-radius: 5px; font-size: 14px; margin-top: 10px;">Cancelar</button>
    </div>
</div>

<style>
    /* Logo responsiva fluida */
    .basquete-img {
        width: 80%;
        max-width: 400px;
        height: auto;
        display: block;
        margin: 20px auto;
    }

    /* Botões estilizados */
    .btn-custom {
        display: inline-block;
        background: #FF7209;
        color: white;
        font-size: 1.1rem;
        font-weight: 500;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-custom:hover {
        background: #e66000;
        transform: scale(1.05);
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .basquete-img {
            width: 90%;        /* ocupa quase toda a tela */
            max-width: 500px;  /* pode crescer mais no mobile */
        }

        .btn-custom {
            width: 100%;       /* ocupa largura total */
            padding: 15px;     /* aumenta área de clique */
            font-size: 1.2rem; /* texto maior */
        }

        .gap-3 {
            gap: 15px !important; /* mais espaço entre botões */
        }
    }
</style>

<script>
     function abrirModal() {
        document.getElementById("modalSenha").style.display = "flex";
    }

    function fecharModal() {
        const senhaInput = document.getElementById("senhaInput");
        senhaInput.value = "";
        document.getElementById("modalSenha").style.display = "none";
    }

    function verificarSenha() {
        const senhaCorreta = "san@prj";
        const senhaInput = document.getElementById("senhaInput");

        if (senhaInput.value === senhaCorreta) {
            senhaInput.value = "";
            senhaInput.style.border = "";
            window.location.href = "/atletas/create";
        } else {
            senhaInput.style.border = "2px solid red";
            senhaInput.value = "";
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("modalSenha");
        if (modal) {
            modal.style.display = "none";
            const senhaInput = document.getElementById("senhaInput");
            if (senhaInput) senhaInput.value = "";
        }
    });
</script>
@endsection
