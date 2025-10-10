 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/assets/js/front.js
index 0000000000000000000000000000000000000000..02b01d7a0f451eefc490b09c2d57cb5b42673ef7 100644
--- a//dev/null
+++ b/simulador-imobiliario/assets/js/front.js
@@ -0,0 +1,482 @@
+(function(window, document){
+    'use strict';
+
+    var currencyFormatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
+
+    function parseCurrency(value) {
+        if (typeof value === 'number') {
+            return value;
+        }
+        if (!value) {
+            return 0;
+        }
+        value = value.replace(/[^0-9,.-]/g, '');
+        value = value.replace(/\./g, '').replace(',', '.');
+        return parseFloat(value) || 0;
+    }
+
+    function formatCurrency(value) {
+        return currencyFormatter.format(Number(value) || 0);
+    }
+
+    function monthsUntil(target) {
+        if (!target) {
+            return 0;
+        }
+        var parts = target.split('-');
+        if (parts.length < 2) {
+            return 0;
+        }
+        var year = parseInt(parts[0], 10);
+        var month = parseInt(parts[1], 10) - 1;
+        if (isNaN(year) || isNaN(month)) {
+            return 0;
+        }
+        var now = new Date();
+        var current = new Date(now.getFullYear(), now.getMonth(), 1);
+        var targetDate = new Date(year, month, 1);
+        var months = (targetDate.getFullYear() - current.getFullYear()) * 12 + (targetDate.getMonth() - current.getMonth());
+        return Math.max(0, months) + 1;
+    }
+
+    function calcPMT(rate, nper, pv) {
+        if (!rate) {
+            return -(pv / nper);
+        }
+        var pmt = (rate * pv) / (1 - Math.pow(1 + rate, -nper));
+        return -pmt;
+    }
+
+    function calcPV(rate, nper, pmt) {
+        if (!rate) {
+            return -pmt * nper;
+        }
+        var pv = pmt * (1 - Math.pow(1 + rate, -nper)) / rate;
+        return -pv;
+    }
+
+    function sanitizeModalidadeParams(modalidade) {
+        var params = (SIMIMOB_CONFIG && SIMIMOB_CONFIG.modalidadeParams && SIMIMOB_CONFIG.modalidadeParams[modalidade]) || {};
+        return {
+            ltv: parseFloat(params.ltv_max || 0) / 100,
+            renda: parseFloat(params.renda_percent || 0) / 100,
+            taxaAA: parseFloat(params.taxa_aa || 0) / 100,
+            taxaAM: parseFloat(params.taxa_am || 0) / 100,
+            prazo: parseInt(params.prazo_max || 360, 10),
+            idade: parseInt(params.idade_max || 80, 10),
+            entradaMin: parseFloat(params.entrada_min || 0) / 100,
+            descricao: params.descricao || ''
+        };
+    }
+
+    function maskCurrencyInput(input) {
+        input.addEventListener('input', function(){
+            var value = parseCurrency(input.value);
+            input.dataset.raw = value;
+            input.value = formatCurrency(value);
+        });
+
+        input.addEventListener('focus', function(){
+            input.select();
+        });
+    }
+
+    function initCurrencyFields(scope) {
+        scope.querySelectorAll('[data-mask="currency"]').forEach(function(input){
+            if (!input.dataset.masked) {
+                input.dataset.masked = '1';
+                maskCurrencyInput(input);
+            }
+        });
+    }
+
+    function updateFinanciamentoCapacity(container) {
+        var modalidade = container.querySelector('[name="modalidade"]:checked');
+        if (!modalidade) {
+            return;
+        }
+        modalidade = modalidade.value;
+        var params = sanitizeModalidadeParams(modalidade);
+        var renda = parseCurrency(container.querySelector('[name="renda_familiar"]').value);
+        var idade = parseInt(container.querySelector('[name="idade"]').value || '0', 10);
+        var valorImovel = parseCurrency(container.querySelector('[name="valor_imovel"]') ? container.querySelector('[name="valor_imovel"]').value : '0');
+        var prazoInput = container.querySelector('[name="prazo"]');
+        var prazo = prazoInput && prazoInput.value ? parseInt(prazoInput.value, 10) : params.prazo;
+        var taxa = params.taxaAM || (params.taxaAA ? Math.pow(1 + params.taxaAA / 12, 1) - 1 : 0.008);
+        var rendaComprometivel = renda * params.renda;
+        var pmtMax = rendaComprometivel;
+        var pv = calcPV(taxa, prazo, -pmtMax);
+        var ltvMax = valorImovel * params.ltv;
+        if (ltvMax && pv > ltvMax) {
+            pv = ltvMax;
+        }
+        var entradaMin = valorImovel * params.entradaMin;
+        var idadeLimite = idade + prazo / 12;
+        var idadeValida = idadeLimite <= params.idade;
+        var summary = container.querySelector('.simimob-capacidade-resultado');
+        if (summary) {
+            summary.innerHTML = '';
+            var lista = document.createElement('ul');
+            lista.innerHTML = '' +
+                '<li><strong>' + container.dataset.labelCredito + ':</strong> ' + formatCurrency(pv) + '</li>' +
+                '<li><strong>' + container.dataset.labelParcela + ':</strong> ' + formatCurrency(calcPMT(taxa, prazo, pv)) + '</li>' +
+                '<li><strong>' + container.dataset.labelPrazo + ':</strong> ' + prazo + ' meses</li>' +
+                '<li><strong>' + container.dataset.labelTaxa + ':</strong> ' + (taxa * 100).toFixed(2) + '% a.m.</li>' +
+                '<li><strong>' + container.dataset.labelEntrada + ':</strong> ' + formatCurrency(entradaMin) + '</li>' +
+                '<li><strong>LTV:</strong> ' + (params.ltv * 100).toFixed(1) + '%</li>' +
+                (params.descricao ? '<li><em>' + params.descricao + '</em></li>' : '') +
+                (idadeValida ? '' : '<li class="simimob-alerta">' + container.dataset.labelIdade + '</li>');
+            summary.appendChild(lista);
+            summary.dataset.credito = pv;
+            summary.dataset.parcela = calcPMT(taxa, prazo, pv);
+            summary.dataset.prazo = prazo;
+            summary.dataset.taxa = taxa;
+            summary.dataset.entrada = entradaMin;
+        }
+    }
+
+    function renderObrasResumo(container) {
+        var valorImovel = parseCurrency(container.querySelector('[name="obras_valor_imovel"]').value);
+        var valorFinanciamento = parseCurrency(container.querySelector('[name="obras_valor_financiamento"]').value);
+        var entrega = container.querySelector('[name="obras_entrega"]').value;
+        var saldo = Math.max(0, valorImovel - valorFinanciamento);
+        var meses = monthsUntil(entrega);
+        var mensais = meses;
+        var semestrais = Math.floor(meses / 6);
+        var anuais = Math.floor(meses / 12);
+        var resumo = container.querySelector('.simimob-obras-resumo');
+        if (!resumo) {
+            return;
+        }
+        resumo.innerHTML = '' +
+            '<p><strong>' + container.dataset.labelSaldo + ':</strong> ' + formatCurrency(saldo) + '</p>' +
+            '<p><strong>' + container.dataset.labelMeses + ':</strong> ' + meses + '</p>' +
+            '<p><strong>' + container.dataset.labelSemestrais + ':</strong> ' + semestrais + ' | <strong>' + container.dataset.labelAnuais + ':</strong> ' + anuais + '</p>';
+        resumo.dataset.saldo = saldo;
+        resumo.dataset.meses = meses;
+        resumo.dataset.semestrais = semestrais;
+        resumo.dataset.anuais = anuais;
+    }
+
+    function calcularDistribuicao(container) {
+        var resumo = container.querySelector('.simimob-obras-resumo');
+        if (!resumo) {
+            return;
+        }
+        var saldo = parseFloat(resumo.dataset.saldo || '0');
+        var meses = parseInt(resumo.dataset.meses || '0', 10);
+        var limiteInter = parseFloat(SIMIMOB_CONFIG.intermediariaLimite || 10);
+        var campoSinal = container.querySelector('[name="obras_sinal"]');
+        var campoMensal = container.querySelector('[name="obras_mensal"]');
+        var campoIntermediaria = container.querySelector('[name="obras_intermediaria"]');
+        var quantidadeIntermediarias = 0;
+        var tipoIntermediaria = container.querySelector('[name="obras_tipo_intermediaria"]');
+        var inicioIntermediaria = container.querySelector('[name="obras_inicio_intermediaria"]');
+
+        if (tipoIntermediaria && tipoIntermediaria.value === 'semestral') {
+            quantidadeIntermediarias = Math.floor(meses / 6);
+        } else if (tipoIntermediaria && tipoIntermediaria.value === 'anual') {
+            quantidadeIntermediarias = Math.floor(meses / 12);
+        }
+
+        var mensal = parseCurrency(campoMensal.value);
+        var sinal = parseCurrency(campoSinal.value);
+        var intermediaria = parseCurrency(campoIntermediaria.value);
+
+        if (!campoSinal.dataset.user) {
+            campoSinal.value = formatCurrency(saldo);
+            sinal = saldo;
+        }
+        if (!campoMensal.dataset.user && meses) {
+            mensal = saldo / Math.max(meses, 1);
+            campoMensal.value = formatCurrency(mensal);
+        }
+        if (!campoIntermediaria.dataset.user && quantidadeIntermediarias) {
+            intermediaria = saldo / Math.max(quantidadeIntermediarias, 1);
+            campoIntermediaria.value = formatCurrency(intermediaria);
+        }
+
+        var totalMensal = mensal * Math.max(meses, 0);
+        var totalIntermediaria = intermediaria * Math.max(quantidadeIntermediarias, 0);
+        var totalSinal = sinal;
+        var totalPago = totalSinal + totalMensal + totalIntermediaria;
+        var saldoAberto = saldo - totalPago;
+
+        var aviso = container.querySelector('.simimob-aviso-intermediaria');
+        if (aviso) {
+            if (mensal > 0 && intermediaria > mensal * limiteInter) {
+                aviso.textContent = container.dataset.mensagemIntermediaria;
+                aviso.style.display = 'block';
+            } else {
+                aviso.style.display = 'none';
+            }
+        }
+
+        container.querySelector('.simimob-total-sinal').textContent = formatCurrency(totalSinal);
+        container.querySelector('.simimob-total-mensal').textContent = formatCurrency(totalMensal);
+        container.querySelector('.simimob-total-intermediaria').textContent = formatCurrency(totalIntermediaria);
+        container.querySelector('.simimob-total-geral').textContent = formatCurrency(totalPago);
+        container.querySelector('.simimob-saldo-aberto').textContent = formatCurrency(saldoAberto);
+    }
+
+    function buildCronograma(container) {
+        var resumo = container.querySelector('.simimob-obras-resumo');
+        if (!resumo) {
+            return [];
+        }
+        var meses = parseInt(resumo.dataset.meses || '0', 10);
+        var saldo = parseFloat(resumo.dataset.saldo || '0');
+        var mensal = parseCurrency(container.querySelector('[name="obras_mensal"]').value);
+        var intermediaria = parseCurrency(container.querySelector('[name="obras_intermediaria"]').value);
+        var tipoIntermediaria = container.querySelector('[name="obras_tipo_intermediaria"]').value;
+        var inicioIntermediaria = container.querySelector('[name="obras_inicio_intermediaria"]').value;
+
+        var eventos = [];
+        var today = new Date();
+        for (var i = 0; i < meses; i++) {
+            var data = new Date(today.getFullYear(), today.getMonth() + i, 1);
+            eventos.push({
+                vencimento: ('0' + (data.getMonth() + 1)).slice(-2) + '/' + data.getFullYear(),
+                tipo: 'Mensal',
+                valor: mensal
+            });
+        }
+        if (intermediaria > 0 && (tipoIntermediaria === 'semestral' || tipoIntermediaria === 'anual')) {
+            var gap = tipoIntermediaria === 'semestral' ? 6 : 12;
+            var inicioParts = inicioIntermediaria.split('-');
+            var baseDate = new Date();
+            if (inicioParts.length >= 2) {
+                baseDate = new Date(parseInt(inicioParts[0], 10), parseInt(inicioParts[1], 10) - 1, 1);
+            }
+            var totalIntermediarias = Math.floor(meses / gap);
+            for (var j = 0; j < totalIntermediarias; j++) {
+                var dataInt = new Date(baseDate.getFullYear(), baseDate.getMonth() + j * gap, 1);
+                eventos.push({
+                    vencimento: ('0' + (dataInt.getMonth() + 1)).slice(-2) + '/' + dataInt.getFullYear(),
+                    tipo: 'Intermediária',
+                    valor: intermediaria
+                });
+            }
+        }
+        eventos.sort(function(a, b){
+            var aParts = a.vencimento.split('/');
+            var bParts = b.vencimento.split('/');
+            var aDate = new Date(parseInt(aParts[1], 10), parseInt(aParts[0], 10) - 1, 1);
+            var bDate = new Date(parseInt(bParts[1], 10), parseInt(bParts[0], 10) - 1, 1);
+            return aDate - bDate;
+        });
+        return eventos;
+    }
+
+    function renderResultados(container) {
+        var cronograma = buildCronograma(container);
+        var tabela = container.querySelector('.simimob-cronograma tbody');
+        if (tabela) {
+            tabela.innerHTML = '';
+            cronograma.forEach(function(item){
+                var row = document.createElement('tr');
+                row.innerHTML = '<td>' + item.vencimento + '</td><td>' + item.tipo + '</td><td>' + formatCurrency(item.valor) + '</td>';
+                tabela.appendChild(row);
+            });
+        }
+    }
+
+    function setupStepWizard(container) {
+        var steps = Array.prototype.slice.call(container.querySelectorAll('.simimob-step'));
+        var stepButtons = container.querySelectorAll('.simimob-stepper button');
+        function showStep(index) {
+            steps.forEach(function(step, idx){
+                if (idx === index) {
+                    step.classList.add('active');
+                } else {
+                    step.classList.remove('active');
+                }
+            });
+            stepButtons.forEach(function(btn, idx){
+                if (idx === index) {
+                    btn.setAttribute('aria-current', 'step');
+                } else {
+                    btn.removeAttribute('aria-current');
+                }
+            });
+            container.dataset.step = index;
+        }
+        stepButtons.forEach(function(btn, idx){
+            btn.addEventListener('click', function(){
+                showStep(idx);
+            });
+        });
+        container.querySelectorAll('[data-next-step]').forEach(function(btn){
+            btn.addEventListener('click', function(){
+                var current = parseInt(container.dataset.step || '0', 10);
+                var next = Math.min(current + 1, steps.length - 1);
+                showStep(next);
+            });
+        });
+        container.querySelectorAll('[data-prev-step]').forEach(function(btn){
+            btn.addEventListener('click', function(){
+                var current = parseInt(container.dataset.step || '0', 10);
+                var prev = Math.max(current - 1, 0);
+                showStep(prev);
+            });
+        });
+        showStep(parseInt(container.dataset.step || '0', 10));
+    }
+
+    function shareTemplate(template, data) {
+        return template.replace(/\{(.*?)\}/g, function(match, key){
+            return data[key] || '';
+        });
+    }
+
+    function initFinanciamento(container) {
+        if (container.dataset.ready) {
+            return;
+        }
+        container.dataset.ready = '1';
+        initCurrencyFields(container);
+        setupStepWizard(container);
+
+        container.addEventListener('change', function(e){
+            if (e.target.matches('[name="modalidade"], [name="renda_familiar"], [name="idade"], [name="valor_imovel"], [name="prazo"]')) {
+                updateFinanciamentoCapacity(container);
+            }
+        });
+        container.addEventListener('input', function(e){
+            if (e.target.matches('[name="renda_familiar"], [name="valor_imovel"]')) {
+                updateFinanciamentoCapacity(container);
+            }
+        });
+        updateFinanciamentoCapacity(container);
+    }
+
+    function initObras(container) {
+        if (container.dataset.ready) {
+            return;
+        }
+        container.dataset.ready = '1';
+        initCurrencyFields(container);
+        setupStepWizard(container);
+
+        container.querySelectorAll('[data-mask="currency"]').forEach(function(input){
+            input.addEventListener('input', function(){
+                this.dataset.user = '1';
+                calcularDistribuicao(container);
+                renderResultados(container);
+            });
+        });
+
+        container.addEventListener('change', function(e){
+            if (e.target.matches('[name="obras_tipo_intermediaria"], [name="obras_inicio_intermediaria"], [name="obras_entrega"]')) {
+                renderObrasResumo(container);
+                calcularDistribuicao(container);
+                renderResultados(container);
+            }
+        });
+        container.addEventListener('input', function(e){
+            if (e.target.matches('[name="obras_valor_imovel"], [name="obras_valor_financiamento"], [name="obras_entrega"]')) {
+                renderObrasResumo(container);
+                calcularDistribuicao(container);
+                renderResultados(container);
+            }
+        });
+
+        renderObrasResumo(container);
+        calcularDistribuicao(container);
+        renderResultados(container);
+
+        container.querySelectorAll('[data-share="whatsapp"], [data-share="email"]').forEach(function(button){
+            button.addEventListener('click', function(){
+                var data = {
+                    empreendimento: container.querySelector('[name="obras_empreendimento"]').value,
+                    unidade: container.querySelector('[name="obras_unidade"]').value,
+                    saldo: container.querySelector('.simimob-saldo-aberto').textContent,
+                    mensal: container.querySelector('.simimob-total-mensal').textContent,
+                    intermediaria: container.querySelector('.simimob-total-intermediaria').textContent,
+                    entrega: container.querySelector('[name="obras_entrega"]').value,
+                    cliente: container.querySelector('[name="cliente_nome"]').value
+                };
+                if (button.dataset.share === 'whatsapp') {
+                    var text = shareTemplate(SIMIMOB_CONFIG.shareWhatsapp || '', data);
+                    window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank');
+                } else {
+                    var subject = shareTemplate(SIMIMOB_CONFIG.shareEmailSubject || '', data);
+                    var body = shareTemplate(SIMIMOB_CONFIG.shareEmailBody || '', data);
+                    window.location.href = 'mailto:?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
+                }
+            });
+        });
+
+        var pdfButton = container.querySelector('[data-action="print"]');
+        if (pdfButton) {
+            pdfButton.addEventListener('click', function(){
+                document.body.classList.add('simimob-printing');
+                window.print();
+                setTimeout(function(){
+                    document.body.classList.remove('simimob-printing');
+                }, 1000);
+            });
+        }
+    }
+
+    function initWizard(container) {
+        if (container.dataset.ready) {
+            return;
+        }
+        container.dataset.ready = '1';
+        setupStepWizard(container);
+        initCurrencyFields(container);
+        container.addEventListener('change', function(){
+            updateFinanciamentoCapacity(container);
+            renderObrasResumo(container);
+            calcularDistribuicao(container);
+            renderResultados(container);
+        });
+        container.addEventListener('input', function(){
+            updateFinanciamentoCapacity(container);
+            renderObrasResumo(container);
+            calcularDistribuicao(container);
+            renderResultados(container);
+        });
+        updateFinanciamentoCapacity(container);
+        renderObrasResumo(container);
+        calcularDistribuicao(container);
+        renderResultados(container);
+    }
+
+    function initAll(scope) {
+        scope.querySelectorAll('.simimob-simulador[data-type="financiamento"]').forEach(initFinanciamento);
+        scope.querySelectorAll('.simimob-simulador[data-type="obras"]').forEach(initObras);
+        scope.querySelectorAll('.simimob-simulador[data-type="wizard"]').forEach(initWizard);
+    }
+
+    if (document.readyState === 'loading') {
+        document.addEventListener('DOMContentLoaded', function(){
+            initAll(document);
+        });
+    } else {
+        initAll(document);
+    }
+
+    window.addEventListener('load', function(){
+        initAll(document);
+    });
+
+    if (window.elementorFrontend && window.elementorFrontend.hooks) {
+        window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function(scope){
+            initAll(scope[0] || scope);
+        });
+    }
+
+    var observer = new MutationObserver(function(mutations){
+        mutations.forEach(function(mutation){
+            mutation.addedNodes.forEach(function(node){
+                if (node.nodeType === 1) {
+                    initAll(node);
+                }
+            });
+        });
+    });
+    observer.observe(document.body, { childList: true, subtree: true });
+
+})(window, document);
 
EOF
)
