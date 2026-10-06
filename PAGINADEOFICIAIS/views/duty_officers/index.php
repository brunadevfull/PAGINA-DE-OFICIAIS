<?php
$officerOptions = $officerOptions ?? [];
$masterOptions = $masterOptions ?? [];
$personnelErrors = $personnelErrors ?? [];

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDirectory = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$dutyOfficersApiUrl = ($scriptDirectory === '' ? '' : $scriptDirectory) . '/proxy-duty-officers.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficiais de Serviço - PAPEM</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        
        .toggle-switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #2196F3;
        }
        
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .duty-officers-container {
            background-color: #f0f4f8;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .officers-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .duty-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #e6f7ff;
            border-radius: 4px;
            border-left: 4px solid #1890ff;
        }

        .current-officers {
            margin-top: 20px;
            padding: 15px;
            background-color: #f6ffed;
            border-radius: 4px;
            border-left: 4px solid #52c41a;
        }

        .error-message {
            color: #ff4d4f;
            margin-top: 5px;
            font-size: 14px;
        }

        .buttons-container {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #1890ff;
            color: white;
        }

        .btn-secondary {
            background-color: #f0f0f0;
            color: #333;
        }

        .btn-primary:hover {
            background-color: #096dd9;
        }

        .btn-secondary:hover {
            background-color: #d9d9d9;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 3px solid rgba(0,0,0,0.2);
            border-radius: 50%;
            border-top-color: #1890ff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body class="<?php echo $body_class; ?>">
    <header>
        <h1>Gestão de Oficiais de Serviço</h1>
        <div class="user-info">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuário'); ?></span>
                <a href="controllers/UserController.php?method=logout">Sair</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <div class="duty-officers-container">
            <h2>Definir Oficiais de Serviço</h2>

            <?php if (!empty($personnelErrors)): ?>
                <div class="error-message">
                    <?php foreach ($personnelErrors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div id="currentOfficers" class="current-officers">
                <h3>Oficiais de Serviço Atuais</h3>
                <div id="loadingCurrentOfficers">
                    <span class="loading"></span> Carregando...
                </div>
                <div id="officersDisplay" class="hidden">
                    <p><strong>Oficial de Serviço:</strong> <span id="currentOfficer">Não definido</span></p>
                    <p><strong>Contramestre:</strong> <span id="currentMaster">Não definido</span></p>
                    <p><small>Última atualização: <span id="lastUpdated">-</span></small></p>
                </div>
                <div id="errorLoadingOfficers" class="error-message hidden">
                    Erro ao carregar os oficiais de serviço atuais.
                </div>
            </div>

            <form id="dutyOfficersForm" class="officers-form">
                <div class="form-group">
                    <label for="officerSelect">Oficial de Serviço:</label>
                    <select id="officerSelect" name="officerName">
                        <option value="">Selecione um Oficial</option>
                        <?php foreach ($officerOptions as $option): ?>
                            <?php
                                $optionValue = $option['value'] ?? '';
                                $optionName = $option['name'] ?? '';
                                $optionRankDisplay = $option['rank'] ?? '';
                                $optionShortRank = $option['short_rank'] ?? '';
                                $optionType = $option['type'] ?? 'officer';
                                $optionDisplay = $option['display'] ?? '';

                                if ($optionDisplay === '') {
                                    $optionDisplay = trim(($optionRankDisplay ? $optionRankDisplay . ' ' : '') . $optionName);
                                }

                                if ($optionValue === '') {
                                    $optionValue = $optionName !== '' ? $optionName : $optionDisplay;
                                }

                                if ($optionValue === '' || $optionDisplay === '') {
                                    continue;
                                }
                            ?>
                            <option
                                value="<?php echo htmlspecialchars($optionValue); ?>"
                                data-rank="<?php echo htmlspecialchars($optionRankDisplay); ?>"
                                data-short-rank="<?php echo htmlspecialchars($optionShortRank); ?>"
                                data-type="<?php echo htmlspecialchars($optionType); ?>"
                                data-display="<?php echo htmlspecialchars($optionDisplay); ?>"
                                data-raw-name="<?php echo htmlspecialchars($optionName); ?>"
                            >
                                <?php echo htmlspecialchars($optionDisplay); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="masterSelect">Contramestre:</label>
                    <select id="masterSelect" name="masterName">
                        <option value="">Selecione um Contramestre</option>
                        <?php foreach ($masterOptions as $option): ?>
                            <?php
                                $optionValue = $option['value'] ?? '';
                                $optionName = $option['name'] ?? '';
                                $optionRankDisplay = $option['rank'] ?? '';
                                $optionShortRank = $option['short_rank'] ?? '';
                                $optionType = $option['type'] ?? 'master';
                                $optionDisplay = $option['display'] ?? '';

                                if ($optionDisplay === '') {
                                    $optionDisplay = trim(($optionRankDisplay ? $optionRankDisplay . ' ' : '') . $optionName);
                                }

                                if ($optionValue === '') {
                                    $optionValue = $optionName !== '' ? $optionName : $optionDisplay;
                                }

                                if ($optionValue === '' || $optionDisplay === '') {
                                    continue;
                                }
                            ?>
                            <option
                                value="<?php echo htmlspecialchars($optionValue); ?>"
                                data-rank="<?php echo htmlspecialchars($optionRankDisplay); ?>"
                                data-short-rank="<?php echo htmlspecialchars($optionShortRank); ?>"
                                data-type="<?php echo htmlspecialchars($optionType); ?>"
                                data-display="<?php echo htmlspecialchars($optionDisplay); ?>"
                                data-raw-name="<?php echo htmlspecialchars($optionName); ?>"
                            >
                                <?php echo htmlspecialchars($optionDisplay); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="duty-info">
                <p>Os oficiais de serviço definidos serão exibidos na barra superior do sistema PAPEM.</p>
                <p>A atualização é imediata e ficará visível para todos os usuários.</p>
            </div>

            <div id="formError" class="error-message hidden"></div>

            <div class="buttons-container">
                <button id="updateButton" class="btn btn-primary">
                    <span id="updateSpinner" class="loading hidden"></span>
                    Atualizar Oficiais de Serviço
                </button>
                <button id="cancelButton" class="btn btn-secondary">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        const dutyOfficersApiUrl = <?php echo json_encode($dutyOfficersApiUrl, JSON_UNESCAPED_SLASHES); ?>;

        // JavaScript para comunicação com a API do Node.js
        document.addEventListener('DOMContentLoaded', function() {
            // Carregar oficiais de serviço atuais ao iniciar a página (sem pré-selecionar)
            loadCurrentDutyOfficers({ preselect: false });

            // Evento para o botão de atualização
            document.getElementById('updateButton').addEventListener('click', function() {
                updateDutyOfficers();
            });

            // Evento para o botão de cancelar
            document.getElementById('cancelButton').addEventListener('click', function() {
                window.location.href = 'index.php';
            });
        });

        function clearDutyOfficerSelections() {
            const officerSelect = document.getElementById('officerSelect');
            const masterSelect = document.getElementById('masterSelect');

            if (officerSelect) {
                officerSelect.value = '';
                officerSelect.dispatchEvent(new Event('change'));
            }

            if (masterSelect) {
                masterSelect.value = '';
                masterSelect.dispatchEvent(new Event('change'));
            }
        }

        // Função para carregar os oficiais de serviço atuais
        function loadCurrentDutyOfficers(options = {}) {
            const shouldPreselect = options.preselect !== undefined ? Boolean(options.preselect) : true;

            document.getElementById('loadingCurrentOfficers').classList.remove('hidden');
            document.getElementById('officersDisplay').classList.add('hidden');
            document.getElementById('errorLoadingOfficers').classList.add('hidden');
            
            // Usar o proxy PHP no mesmo domínio para evitar problemas de CORS
            fetch(dutyOfficersApiUrl, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao carregar oficiais de serviço');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('loadingCurrentOfficers').classList.add('hidden');
                
                if (data.success) {
                    document.getElementById('officersDisplay').classList.remove('hidden');
                    
                    // Atualizar os dados na interface
                    const officerName = data.officers.officerName ?? null;
                    const officerRank = data.officers.officerRank ?? null;
                    const officerDisplayName = data.officers.officerDisplayName ?? officerName;
                    const masterName = data.officers.masterName ?? null;
                    const masterRank = data.officers.masterRank ?? null;
                    const masterDisplayName = data.officers.masterDisplayName ?? masterName;

                    document.getElementById('currentOfficer').textContent = formatDutyDisplay(officerDisplayName, officerRank);
                    document.getElementById('currentMaster').textContent = formatDutyDisplay(masterDisplayName, masterRank);
                    
                    // Formatar data de atualização
                    const updatedDate = data.officers.updatedAt ? new Date(data.officers.updatedAt) : null;
                    document.getElementById('lastUpdated').textContent = updatedDate ? 
                        updatedDate.toLocaleString('pt-BR') : '-';
                    
                    // Pré-selecionar os valores nos dropdowns, se disponíveis
                    if (shouldPreselect && (data.officers.officerName || data.officers.officerRank)) {
                        selectOptionByValue('officerSelect', data.officers.officerName, data.officers.officerRank);
                    }

                    if (shouldPreselect && (data.officers.masterName || data.officers.masterRank)) {
                        selectOptionByValue('masterSelect', data.officers.masterName, data.officers.masterRank);
                    }
                } else {
                    document.getElementById('errorLoadingOfficers').classList.remove('hidden');
                    console.error('Erro ao carregar dados:', data.error);
                }
            })
            .catch(error => {
                document.getElementById('loadingCurrentOfficers').classList.add('hidden');
                document.getElementById('errorLoadingOfficers').classList.remove('hidden');
                console.error('Erro na requisição:', error);
            });
        }

        // Função para selecionar uma opção no dropdown pelo texto
        function selectOptionByValue(selectId, value, rank = null) {
            const selectElement = document.getElementById(selectId);

            if (!selectElement) {
                return;
            }

            const normalizedValue = typeof value === 'string' ? value.trim() : '';
            const normalizedRank = typeof rank === 'string' ? rank.trim() : '';

            if (normalizedValue === '' && normalizedRank === '') {
                selectElement.value = '';
                selectElement.dispatchEvent(new Event('change'));
                return;
            }

            for (let i = 0; i < selectElement.options.length; i++) {
                const option = selectElement.options[i];
                const optionValue = option.value?.trim?.() ?? '';
                const optionDisplay = option.dataset.display?.trim?.() ?? '';
                const optionRawName = option.dataset.rawName?.trim?.() ?? '';
                const optionRank = option.dataset.rank?.trim?.() ?? '';

                const matchesValue = normalizedValue !== '' && (
                    optionValue === normalizedValue ||
                    optionDisplay === normalizedValue ||
                    optionRawName === normalizedValue
                );

                const matchesRank = normalizedRank !== '' && optionRank === normalizedRank;

                if (matchesValue || matchesRank) {
                    selectElement.selectedIndex = i;
                    selectElement.dispatchEvent(new Event('change'));
                    break;
                }
            }
        }

        function formatDutyDisplay(name, rank) {
            const normalizedName = typeof name === 'string' ? name.trim() : '';
            const normalizedRank = typeof rank === 'string' ? rank.trim() : '';

            if (normalizedName === '' && normalizedRank === '') {
                return 'Não definido';
            }

            if (normalizedName === '') {
                return normalizedRank;
            }

            if (normalizedRank === '') {
                return normalizedName;
            }

            if (normalizedName.toUpperCase().startsWith(normalizedRank.toUpperCase())) {
                return normalizedName;
            }

            return `${normalizedRank} ${normalizedName}`.trim();
        }

        // Função para atualizar os oficiais de serviço
        function updateDutyOfficers() {
            // Limpar mensagens de erro anteriores
            document.getElementById('formError').classList.add('hidden');
            
            // Mostrar spinner de carregamento
            const updateButton = document.getElementById('updateButton');
            const updateSpinner = document.getElementById('updateSpinner');
            updateButton.disabled = true;
            updateSpinner.classList.remove('hidden');
            
            // Obter valores dos campos
            const officerSelect = document.getElementById('officerSelect');
            const masterSelect = document.getElementById('masterSelect');
            
            const officerOption = officerSelect?.options[officerSelect.selectedIndex] ?? null;
            const masterOption = masterSelect?.options[masterSelect.selectedIndex] ?? null;

            const officerRawName = officerOption ? (officerOption.dataset.rawName || officerOption.value || '') : '';
            const officerDisplayName = officerOption ? (officerOption.dataset.display || officerOption.text || '') : '';
            const officerRank = officerOption
                ? officerOption.dataset.rank || officerOption.dataset.shortRank || null
                : null;

            const masterRawName = masterOption ? (masterOption.dataset.rawName || masterOption.value || '') : '';
            const masterDisplayName = masterOption ? (masterOption.dataset.display || masterOption.text || '') : '';
            const masterRank = masterOption
                ? masterOption.dataset.rank || masterOption.dataset.shortRank || null
                : null;

            const officerName = officerRawName;
            const masterName = masterRawName;

            // Verificar se pelo menos um oficial foi selecionado
            if (officerName === "" && masterName === "") {
                document.getElementById('formError').textContent = 'Selecione pelo menos um oficial de serviço.';
                document.getElementById('formError').classList.remove('hidden');
                updateButton.disabled = false;
                updateSpinner.classList.add('hidden');
                return;
            }

            // Preparar dados para envio
            const officerData = {
                officerName: officerName === "" ? null : officerName,
                officerRank: officerRank === null || officerRank === '' ? null : officerRank,
                officerDisplayName: officerDisplayName === '' ? null : officerDisplayName,
                masterName: masterName === "" ? null : masterName,
                masterRank: masterRank === null || masterRank === '' ? null : masterRank,
                masterDisplayName: masterDisplayName === '' ? null : masterDisplayName,
            };
            
            // Usar o proxy PHP no mesmo domínio para evitar problemas de CORS
            // Enviar requisição para a API
            fetch(dutyOfficersApiUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify(officerData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao atualizar oficiais de serviço');
                }
                return response.json();
            })
            .then(data => {
                updateButton.disabled = false;
                updateSpinner.classList.add('hidden');
                
                if (data.success) {
                    // Atualização bem-sucedida, recarregar dados atuais
                    alert('Oficiais de serviço atualizados com sucesso!');
                    loadCurrentDutyOfficers({ preselect: false });
                    clearDutyOfficerSelections();
                } else {
                    // Exibir mensagem de erro
                    document.getElementById('formError').textContent = data.error || 'Erro ao atualizar oficiais de serviço.';
                    document.getElementById('formError').classList.remove('hidden');
                }
            })
            .catch(error => {
                updateButton.disabled = false;
                updateSpinner.classList.add('hidden');
                document.getElementById('formError').textContent = 'Erro na comunicação com o servidor. Tente novamente.';
                document.getElementById('formError').classList.remove('hidden');
                console.error('Erro na requisição:', error);
            });
        }
    </script>
</body>
</html>