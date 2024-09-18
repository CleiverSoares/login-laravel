<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medições do Lighthouse</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.6.16/dist/css/uikit.min.css" />
    <style>
        #carregando {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            padding: 20px;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            transition: all 0.5s ease;
        }

        .uk-container {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        #carregando.reduzido {
            display: flex;
            position: fixed;
            top: 20px;
            right: 0px;
            transform: none;
            font-size: 16px;
            padding: 20px;
            width: 350px;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            box-shadow: none;
            text-align: right;
        }

        .img {
            text-align: center;
        }

        .img img {
            width: 120px;
            object-fit: cover;
        }

        .lista-sites {
            margin-bottom: 20px;
            padding: 0;
            list-style: none;
        }

        .meu-icone-check {
            color: #63b946;
        }

        .meu-icone-check polyline {
            stroke-width: 2px !important;
        }

        .lista-sites li {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 15%;
        }

        .lista-sites li span {
            margin-right: 10px;
        }

        .lista-sites {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .uk-card a {
            width: 100%;
            display: flex;
            text-align: center;
            justify-content: center;
        }

        #formAdicionar {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="uk-section uk-section-default">
        <div class="uk-container">
            <h1 class="uk-heading-line"><span>Medições do Lighthouse</span></h1>

            <div class="uk-margin">
                <button class="uk-button uk-button-primary" onclick="mostrarFormulario()">Adicionar mais</button>
            </div>

            <div id="formAdicionar">
                <input class="uk-input uk-width-1-3" id="nomeInput" type="text" placeholder="Nome do site">
                <input class="uk-input uk-width-1-3" id="urlSiteInput" type="text" placeholder="URL do site">
                <input class="uk-input uk-width-1-3" id="urlImgInput" type="text" placeholder="URL da imagem">
                <button class="uk-button uk-button-primary" onclick="adicionarURLManual()">Adicionar</button>
                <button class="uk-button uk-button-secondary" onclick="ocultarFormulario()">Cancelar</button>
            </div>

            <div id="progressoContainer" class="uk-margin">
                <div style="display: flex;">
                    <p class="texto-verificar" style="width: 20%; align-items: center; display: flex; color: red;">
                        Verificando ...</p>
                    <ul id="listaSites" class="lista-sites"></ul>
                </div>
                <progress id="progressBar" class="uk-progress" value="0" max="100"></progress>
                <p id="progressText" class="uk-text-meta">0/5 páginas processadas</p>
            </div>
            <div id="carregando" class="uk-alert-primary" uk-alert>
                <div uk-spinner></div> Carregando medições, por favor aguarde...
            </div>

            <div class="uk-child-width-1-4@m uk-grid-small uk-grid-match uk-margin" id="resultadosContainer" uk-grid>
            </div>
        </div>
    </div>

    <div id="notificacao" class="uk-notification"></div>

    <script>
        const urlsPredefinidas = [
            {
                url: 'https://immobile.software/',
                urlImg: 'images/ferramenta/footer__immobile.svg',
                nome: 'Immobile'
            },

            {
                url: 'https://nfstock.com.br/',
                urlImg: 'images/ferramenta/1nf-stock_color_rgb.svg',
                nome: 'Nf-stock'
            },

            {
                url: 'https://erpfor.me/',
                urlImg: 'images/ferramenta/erp4me.jpg',
                nome: 'Erp4me'
            },

            {
                url: 'https://site-alterdata.apps.production.clusters.alterdatasoftware.com.br/',
                urlImg: 'images/ferramenta/logo_alterdata-03%201.svg',
                nome: 'Alterdata'
            },
            {
                url: 'https://blog.alterdata.com.br/',
                urlImg: '',
                nome: 'Blog '
            }
        ];

        let progresso = 0;
        let carregamentoIniciado = false;

        function adicionarSitesNaLista() {
            const listaSites = document.getElementById('listaSites');
            listaSites.innerHTML = '';
            urlsPredefinidas.forEach((urlObj, index) => {
                const item = document.createElement('li');
                item.id = `site-${index}`;
                item.innerHTML = `<span>${urlObj.nome}</span> <span class="meu-icone-check" id="check-${index}"></span>`;
                listaSites.appendChild(item);
            });
        }

        function mostrarFormulario() {
            document.getElementById('formAdicionar').style.display = 'block';
        }

        function ocultarFormulario() {
            document.getElementById('formAdicionar').style.display = 'none';
        }

        function adicionarURLManual() {
            const nomeInput = document.getElementById('nomeInput').value;
            const urlSiteInput = document.getElementById('urlSiteInput').value;
            const urlImgInput = document.getElementById('urlImgInput').value;

            if (nomeInput && urlSiteInput && urlImgInput) {
                const urlExistente = urlsPredefinidas.find(urlObj => urlObj.url === urlSiteInput);
                if (!urlExistente) {
                    const novoSite = { url: urlSiteInput, urlImg: urlImgInput, nome: nomeInput };
                    urlsPredefinidas.push(novoSite);
                    const index = urlsPredefinidas.length - 1;
                    adicionarSitesNaLista();
                    buscarMedicoes(novoSite.url, index);
                } else {
                    alert('Esta URL já foi adicionada.');
                }
                ocultarFormulario();
            }
        }
        async function buscarMedicoes(url, index) {
            const apiUrlDesktop = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(url)}&strategy=desktop`;
            const apiUrlMobile = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(url)}&strategy=mobile`;
            const textoVerificar = document.querySelector('.texto-verificar')
            const carregandoIndicador = document.getElementById('carregando');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            if (!carregamentoIniciado) {
                carregandoIndicador.classList.add('uk-alert-primary');
                carregamentoIniciado = true;
            }

            try {
                const responseDesktop = await fetch(apiUrlDesktop);
                const dadosDesktop = await responseDesktop.json();
                const responseMobile = await fetch(apiUrlMobile);
                const dadosMobile = await responseMobile.json();

                exibirMedicoes(dadosDesktop.lighthouseResult, dadosMobile.lighthouseResult, url, index);

                progresso++;
                document.getElementById(`check-${index}`).innerHTML = '<span uk-icon="check"></span>';

                const progressPercent = (progresso / urlsPredefinidas.length) * 100;
                progressBar.value = progressPercent;
                progressText.innerText = `${progresso}/${urlsPredefinidas.length} páginas processadas`;

                if (progresso === 1) {
                    carregandoIndicador.classList.add('reduzido');
                }
                if (progresso === urlsPredefinidas.length) {
                    carregandoIndicador.style.display = 'none';
                    textoVerificar.textContent = 'Sites verificados'
                    textoVerificar.style.color = 'green'
                }
            } catch (erro) {
                console.error('Erro:', erro);
            }
        }

        function exibirMedicoes(resultadosDesktop, resultadosMobile, url, index) {
            const containerResultados = document.getElementById('resultadosContainer');
            const cartao = document.createElement('div');

            const pontuacaoDesempenhoDesktop = resultadosDesktop.categories.performance.score * 100;
            const pontuacaoDesempenhoMobile = resultadosMobile.categories.performance.score * 100;

            cartao.innerHTML = `
                <div class="uk-card uk-card-default uk-card-body">
                    <h3 class="uk-card-title img"><img src="${urlsPredefinidas[index].urlImg ||
                         'https://via.placeholder.com/120'}" alt="${urlsPredefinidas[index].nome}" /></h3>
                    <p><strong>Desempenho Desktop:</strong> ${pontuacaoDesempenhoDesktop}</p>
                    <p><strong>Desempenho Mobile:</strong> ${pontuacaoDesempenhoMobile}</p>
                    <a href="${urlsPredefinidas[index].url}">Ver site</a>

                </div>
            `;
            containerResultados.appendChild(cartao);
        }



        window.addEventListener('DOMContentLoaded', () => {
            adicionarSitesNaLista();
            urlsPredefinidas.forEach((urlObj, index) => {
                buscarMedicoes(urlObj.url, index);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.16/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.6.16/dist/js/uikit-icons.min.js"></script>

</body>

</html>
