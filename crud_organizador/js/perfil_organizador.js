document.addEventListener('DOMContentLoaded', async () => {
    const formPerfil = document.getElementById('formPerfil');
    const nomeInput = document.getElementById('nome');
    const bioInput = document.getElementById('bio');
    const emailInput = document.getElementById('email');
    const telefoneInput = document.getElementById('telefone');
    const documentoInput = document.getElementById('documento');
    const novaSenhaInput = document.getElementById('nova_senha');
    const confirmaNovaSenhaInput = document.getElementById('confirma_nova_senha');
    
    const displayName = document.getElementById('displayName');
    const displayBio = document.getElementById('displayBio');

    async function carregarDados() {
        try {
            const res = await fetch('../php/perfil_organizador_get.php');
            const data = await res.json();

            if (data.status === 'sucesso') {
                const info = data.data;
                nomeInput.value = info.nome || '';
                bioInput.value = info.bio || '';
                emailInput.value = info.email || '';
                telefoneInput.value = info.telefone || '';
                documentoInput.value = info.documento || '';

                displayName.textContent = info.nome || 'Organizador';
                displayBio.textContent = info.bio || 'Gerencie seus eventos e personalize suas informações no Viewritiba.';
            } else {
                alert('Acesso restrito. Por favor, faça login para acessar seu perfil.');
                window.location.href = '../login/Login.html';
            }
        } catch (error) {
            console.error('Erro ao buscar dados do perfil:', error);
            alert('Não foi possível carregar os dados do perfil. Verifique sua conexão.');
        }
    }

    await carregarDados();

    const fileAvatar = document.getElementById('fileAvatar');
    const avatarImage = document.getElementById('avatarImage');

    if (fileAvatar && avatarImage) {
        fileAvatar.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    avatarImage.src = event.target.result;
                    showToast('Foto de perfil pré-visualizada!');
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }

    if (formPerfil) {
        formPerfil.addEventListener('submit', async (e) => {
            e.preventDefault();

            const nome = nomeInput.value.trim();
            const email = emailInput.value.trim();
            const telefone = telefoneInput.value.trim();
            const bio = bioInput.value.trim();
            const novaSenha = novaSenhaInput.value;
            const confirmaNovaSenha = confirmaNovaSenhaInput.value;

            if (!nome || !email || !telefone) {
                alert('Preencha os campos obrigatórios (Nome, E-mail e Telefone).');
                return;
            }

            if (novaSenha || confirmaNovaSenha) {
                if (novaSenha.length < 6) {
                    alert('A nova senha deve ter no mínimo 6 caracteres.');
                    return;
                }
                if (novaSenha !== confirmaNovaSenha) {
                    alert('A nova senha e a confirmação não coincidem.');
                    return;
                }
            }

            const fd = new FormData();
            fd.append('nome', nome);
            fd.append('bio', bio);
            fd.append('email', email);
            fd.append('telefone', telefone);
            if (novaSenha) {
                fd.append('nova_senha', novaSenha);
            }

            try {
                const res = await fetch('../php/perfil_organizador_update.php', {
                    method: 'POST',
                    body: fd
                });
                const result = await res.json();

                if (result.status === 'sucesso') {
                    showToast(result.mensagem);
                    displayName.textContent = nome || 'Organizador';
                    displayBio.textContent = bio || 'Gerencie seus eventos e personalize suas informações no Viewritiba.';
                    novaSenhaInput.value = '';
                    confirmaNovaSenhaInput.value = '';
                } else {
                    alert('Erro: ' + result.mensagem);
                }
            } catch (error) {
                console.error('Erro na atualização:', error);
                alert('Erro ao atualizar o perfil. Tente novamente.');
            }
        });
    }

    const modalConfirmarExclusao = document.getElementById('modalConfirmarExclusao');
    const btnAbrirModalExcluir = document.getElementById('btnAbrirModalExcluir');
    const btnFecharModalExcluir = document.getElementById('btnFecharModalExcluir');
    const btnCancelarExclusao = document.getElementById('btnCancelarExclusao');
    const btnConfirmarExclusaoFinal = document.getElementById('btnConfirmarExclusaoFinal');
    const backdropExcluir = document.getElementById('backdropExcluir');

    if (btnAbrirModalExcluir && modalConfirmarExclusao) {
        btnAbrirModalExcluir.addEventListener('click', () => modalConfirmarExclusao.classList.remove('hidden'));
        if (btnFecharModalExcluir) btnFecharModalExcluir.addEventListener('click', () => modalConfirmarExclusao.classList.add('hidden'));
        if (btnCancelarExclusao) btnCancelarExclusao.addEventListener('click', () => modalConfirmarExclusao.classList.add('hidden'));
        if (backdropExcluir) backdropExcluir.addEventListener('click', () => modalConfirmarExclusao.classList.add('hidden'));

        if (btnConfirmarExclusaoFinal) {
            btnConfirmarExclusaoFinal.addEventListener('click', async () => {
                try {
                    const res = await fetch('../php/perfil_organizador_excluir.php', {
                        method: 'POST'
                    });
                    const result = await res.json();

                    if (result.status === 'sucesso') {
                        modalConfirmarExclusao.classList.add('hidden');
                        alert('Conta excluída com sucesso. Você será redirecionado para a tela de login.');
                        window.location.href = '../login/Login.html';
                    } else {
                        alert('Erro ao excluir conta: ' + result.mensagem);
                    }
                } catch (error) {
                    console.error('Erro na exclusão:', error);
                    alert('Não foi possível processar a exclusão da conta.');
                }
            });
        }
    }

    const toastNotification = document.getElementById('toastNotification');
    const toastMessage = document.getElementById('toastMessage');
    let toastTimeout;

    function showToast(msg) {
        if (!toastNotification || !toastMessage) return;
        toastMessage.textContent = msg;
        toastNotification.classList.remove('hidden');

        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toastNotification.classList.add('hidden');
        }, 3500);
    }
});
