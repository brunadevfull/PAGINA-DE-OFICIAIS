 <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/sunset_data.php';?> 
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

// Verifique se o usuÃ¡rio estÃ¡ logado
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
$is_portaria = isset($_SESSION['user_id']) && !$is_admin;
$username = strtolower($_SESSION['username'] ?? '');
$can_manage_duty_officers = $is_logged_in;



// Recupere os postos do banco de dados
try {
    $stmt = $pdo->prepare('SELECT id, descricao, imagem FROM postos');
    $stmt->execute();
    $postos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao recuperar postos: " . $e->getMessage();
}

try {
  $stmt = $pdo->query('SELECT id, username FROM users');
  $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Erro ao recuperar usuÃ¡rios: " . $e->getMessage();
}

$officerOptions = $officerOptions ?? [];
$masterOptions = $masterOptions ?? [];

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDirectory = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$dutyOfficersApiUrl = ($scriptDirectory === '' ? '' : $scriptDirectory) . '/proxy-duty-officers.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>PAPEM - Quadro de Oficiais</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="../../js/jquery.min.js"></script>
    <link href="../../css/select2.min.css" rel="stylesheet" />
 <script src="../../js/temperature-bagde.js"></script>
    <script src="../../js/select2.min.js"></script>
 <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <!-- CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
   
    <link rel="stylesheet" href="../../css/all.min.css">

   
    <script src="../../js/scripts.js"></script>
    <script>
    $(document).ready(function() {
        <?php if (isset($_SESSION['login_error'])): ?>
            $('#loginModal').modal('show');
        <?php endif; ?>
    });
</script>



</head>

<?php
$bodyClasses = [];

if ($is_logged_in) {
    $bodyClasses[] = 'logged-in';

    if ($is_admin) {
        $bodyClasses[] = 'admin';
    } elseif ($is_portaria) {
        $bodyClasses[] = 'portaria';
    }
}

$bodyClassAttribute = empty($bodyClasses) ? '' : ' class="' . implode(' ', $bodyClasses) . '"';
?>

<body<?= $bodyClassAttribute ?>>
<header id="topo">
    <div class="navbar-left">
        <img src="imagens/brasao2.png" id="papem" alt="Conecta PAPEM" class="navbar-logo">
        <div class="navbar-text">
            <h6 class="top-site-name">Marinha do Brasil</h6>
            <h1>Pagadoria de Pessoal da Marinha</h1>
            <p>"ORDEM, PRONTIDÃO E REGULARIDADE"</p>
        </div>
    </div>




 <div class="navbar-right">


<?php if ($is_logged_in): ?>
    <!-- Ações Administrativas - apenas para administradores -->
    <?php if ($is_admin): ?>
        <div class="dropdown">
            <button class="glass-button dropdown-toggle" type="button" id="adminDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Ações Administrativas
            </button>
            <div class="dropdown-menu" aria-labelledby="adminDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addUserModal">Adicionar Usuário</a>
                <hr>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePasswordModal">Redefinir Senha</a>
                <hr>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#resetUserPasswordModal">Redefinir Senha de Usuário</a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($can_manage_duty_officers): ?>
        <!-- Botão Gerenciar Oficiais de Serviço - apenas para administradores e usuário EOR -->
        
        <button class="glass-button duty-manage-button" data-toggle="modal" data-target="#dutyOfficersModal">
            Gerenciar Oficiais de Serviço
        </button>
        
    <?php endif; ?>

    <!-- Botão de Logout -->
    <button class="glass-button logout-button" onclick="window.location.href='views/logout.php'">Logout</button>
<?php else: ?>
    <button class="btn btn-primary login-button" data-toggle="modal" data-target="#loginModal">Login</button>
<?php endif; ?>
</div>


<!-- R�dio Marinha -->
<div class="radio-section">
<img class="radiomarinha" src="imagens/radio_marinha.png" alt="R�dio Marinha" >
<audio controls>
        <source src="https://stm0.inovativa.net/listen/radiomarinha/radio.mp3" type="audio/mpeg">
    
    </audio>
      
       </div>
    
        

</header>





<div class="header-bottom-area-bg">



      </div>





<?php if ($can_manage_duty_officers): ?>
<!-- Modal Gerenciar Oficiais de Serviço -->
<div class="modal fade" id="dutyOfficersModal" tabindex="-1" role="dialog" aria-labelledby="dutyOfficersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dutyOfficersModalLabel">Gerenciar Oficiais de Serviço</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="currentOfficers" class="mb-4">
                    <h6>Oficiais de Serviço Atuais</h6>
                    <div id="loadingCurrentOfficers">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                         
                        </div>

                    </div>
                    <div id="officersDisplay" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Oficial de Serviço:</strong> <span id="currentOfficer">Não definido</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Contramestre:</strong> <span id="currentMaster">Não definido</span></p>
                            </div>
                        </div>
                        <p class="text-muted small">Última atualização: <span id="lastUpdated">-</span></p>
                    </div>
                   
                </div>

                <form id="dutyOfficersForm">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="officerSelect">Oficial de Serviço:</label>
                            <select class="form-control" id="officerSelect" name="officerName">
                                <option value="">Selecione um Oficial</option>
                                <?php if (!empty($officerOptions)): ?>
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
                                            value="<?php echo htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-rank="<?php echo htmlspecialchars($optionRankDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-short-rank="<?php echo htmlspecialchars($optionShortRank, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-type="<?php echo htmlspecialchars($optionType, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-display="<?php echo htmlspecialchars($optionDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-raw-name="<?php echo htmlspecialchars($optionName, ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                            <?php echo htmlspecialchars($optionDisplay, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Nenhum oficial disponível</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="masterSelect">Contramestre:</label>
                            <select class="form-control" id="masterSelect" name="masterName">
                                <option value="">Selecione um Contramestre</option>
                                <?php if (!empty($masterOptions)): ?>
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
                                            value="<?php echo htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-rank="<?php echo htmlspecialchars($optionRankDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-short-rank="<?php echo htmlspecialchars($optionShortRank, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-type="<?php echo htmlspecialchars($optionType, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-display="<?php echo htmlspecialchars($optionDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-raw-name="<?php echo htmlspecialchars($optionName, ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                            <?php echo htmlspecialchars($optionDisplay, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Nenhum contramestre disponível</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> Os oficiais de serviço definidos serão exibidos na barra superior do sistema PAPEM. A atualização é imediata.
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="updateOfficersButton" class="btn btn-primary">
                    <span id="updateSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Atualizar Oficiais de Serviço
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="dutyOfficersModalAurora" tabindex="-1" role="dialog" aria-labelledby="dutyOfficersModalAuroraLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content neo-duty-modal">
            <div class="neo-modal-hero">
                <div>
                    <p class="neo-modal-subtitle">Atualize os responsáveis pelo serviço com uma experiência moderna.</p>
                </div>
                <button type="button" class="neo-close-button" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="neo-modal-body">
                <div class="neo-status-area">
                    <div id="neoLoadingCurrentOfficers" class="neo-loading-state">
                        <div class="spinner-border text-light" role="status">
                            <span class="sr-only">Carregando...</span>
                        </div>
                        <span>Carregando dados atuais...</span>
                    </div>
                    <div id="neoOfficersDisplay" class="neo-status-grid d-none">
                        <div class="neo-status-card">
                            <span class="neo-status-label">Oficial de Serviço Atual</span>
                            <strong id="neoCurrentOfficer">Não definido</strong>
                        </div>
                        <div class="neo-status-card">
                            <span class="neo-status-label">Contramestre de Serviço</span>
                            <strong id="neoCurrentMaster">Não definido</strong>
                        </div>
                        <div class="neo-status-card neo-status-card--compact">
                            <span class="neo-status-label">Última atualização</span>
                            <time id="neoLastUpdated">-</time>
                        </div>
                    </div>
                    <div id="neoErrorLoadingOfficers" class="neo-alert neo-alert-danger d-none">
                        <span id="neoErrorLoadingOfficersMessage">Não foi possível carregar os oficiais de serviço. Tente novamente.</span>
                    </div>
                </div>
                <form id="neoDutyOfficersForm" class="neo-duty-form">
                    <div class="neo-field-group">
                        <label for="neoOfficerSelect">Novo Oficial de Serviço</label>
                        <div class="neo-select-wrapper">
                            <select class="form-control neo-select" id="neoOfficerSelect" name="officerName">
                                <option value="">Selecione um Oficial</option>
                                <?php if (!empty($officerOptions)): ?>
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
                                            value="<?php echo htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-rank="<?php echo htmlspecialchars($optionRankDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-short-rank="<?php echo htmlspecialchars($optionShortRank, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-type="<?php echo htmlspecialchars($optionType, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-display="<?php echo htmlspecialchars($optionDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-raw-name="<?php echo htmlspecialchars($optionName, ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                            <?php echo htmlspecialchars($optionDisplay, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="neo-field-group">
                        <label for="neoMasterSelect">Novo Contramestre de Serviço</label>
                        <div class="neo-select-wrapper">
                            <select class="form-control neo-select" id="neoMasterSelect" name="masterName">
                                <option value="">Selecione um Contramestre</option>
                                <?php if (!empty($masterOptions)): ?>
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
                                            value="<?php echo htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-rank="<?php echo htmlspecialchars($optionRankDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-short-rank="<?php echo htmlspecialchars($optionShortRank, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-type="<?php echo htmlspecialchars($optionType, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-display="<?php echo htmlspecialchars($optionDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                                            data-raw-name="<?php echo htmlspecialchars($optionName, ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                            <?php echo htmlspecialchars($optionDisplay, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div id="neoFormError" class="neo-alert neo-alert-danger d-none"></div>
                    <div class="neo-modal-actions">
                        <button type="button" class="neo-secondary-button" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="neo-primary-button" id="neoUpdateOfficersButton">
                            <span id="neoUpdateSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Atualizar Designação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>









<!-- Modal de Login -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <?php if (isset($_SESSION['login_error'])): ?>
          <div class="alert alert-danger">
            <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
          </div>
        <?php endif; ?>
        <form action="views/process_login.php" method="POST">
          <div class="form-group">
            <label for="username">Usuário:</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal Adicionar UsuÃ¡rio -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Adicionar Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action="views/process_add_user.php" method="POST">
                    <div class="form-group">
                        <label for="username">Usuário:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div id="usernameError" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="is_admin">Administrador:</label>
                        <input type="checkbox" id="is_admin" name="is_admin">
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de sucesso ao adicionar usuário -->
<div class="modal fade" id="userAddSuccessModal" tabindex="-1" role="dialog" aria-labelledby="userAddSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userAddSuccessModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Usuário adicionado com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Erro ao adicionar usuário -->
<div id="userAddErrorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userAddErrorModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userAddErrorModalLabel">Erro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="userAddErrorMessage">Usuário ja existe!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal de Erro -->
<div id="passwordErrorModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Erro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p id="passwordErrorMessage">A nova senha e a confirmação não coincidem.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal de Sucesso -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Usuário adicionado com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Redefinir Senha -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePasswordModalLabel">Redefinir Senha</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="changePasswordForm" action="views/process_change_password.php" method="POST">
          <div class="form-group">
            <label for="current_password">Senha Atual:</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
          </div>
          <div class="form-group">
            <label for="new_password">Nova Senha:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirmar Nova Senha:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>
          <button type="submit" class="btn btn-primary">Redefinir Senha</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Sucesso para RedefiniÃ§Ã£o de Senha -->
<div class="modal fade" id="passwordChangeSuccessModal" tabindex="-1" role="dialog" aria-labelledby="passwordChangeSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordChangeSuccessModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Senha redefinida com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Erro -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Erro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo isset($_SESSION['error']) ? $_SESSION['error'] : ''; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Redefinir Senha de UsuÃ¡rio -->
<div class="modal fade" id="resetUserPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetUserPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetUserPasswordModalLabel">Redefinir Senha de Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resetUserPasswordForm" action="views/process_reset_user_password.php" method="POST">
                    <div class="form-group">
                        <label for="reset_username">Usuário:</label>
                        <select class="form-control" id="reset_username" name="username" required>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reset_new_password">Nova Senha:</label>
                        <input type="password" class="form-control" id="reset_new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="reset_confirm_password">Confirmar Nova Senha:</label>
                        <input type="password" class="form-control" id="reset_confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Redefinir Senha</button>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- Modal de Sucesso para Redefinição de Senha do Usuário -->
<div class="modal fade" id="resetPasswordSuccessModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordSuccessModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Senha do usuário redefinida com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<script>
const dutyOfficersApiUrl = <?php echo json_encode($dutyOfficersApiUrl, JSON_UNESCAPED_SLASHES); ?>;
const dutyModalConfigs = [
    {
        modalSelector: '#dutyOfficersModal',
        loadingIndicator: '#loadingCurrentOfficers',
        displayContainer: '#officersDisplay',
        errorContainer: '#errorLoadingOfficers',
        errorMessage: '#errorLoadingOfficersMessage',
        currentOfficer: '#currentOfficer',
        currentMaster: '#currentMaster',
        lastUpdated: '#lastUpdated',
        officerSelect: '#officerSelect',
        masterSelect: '#masterSelect',
        formError: '#formError',
        updateButton: '#updateOfficersButton',
        updateSpinner: '#updateSpinner'
    },
    {
        modalSelector: '#dutyOfficersModalAurora',
        loadingIndicator: '#neoLoadingCurrentOfficers',
        displayContainer: '#neoOfficersDisplay',
        errorContainer: '#neoErrorLoadingOfficers',
        errorMessage: '#neoErrorLoadingOfficersMessage',
        currentOfficer: '#neoCurrentOfficer',
        currentMaster: '#neoCurrentMaster',
        lastUpdated: '#neoLastUpdated',
        officerSelect: '#neoOfficerSelect',
        masterSelect: '#neoMasterSelect',
        formError: '#neoFormError',
        updateButton: '#neoUpdateOfficersButton',
        updateSpinner: '#neoUpdateSpinner'
    }
];
let activeDutyModalConfigs = [];

$(document).ready(function() {
    activeDutyModalConfigs = dutyModalConfigs.filter(config => document.querySelector(config.modalSelector));

    activeDutyModalConfigs.forEach(config => {
        $(config.modalSelector).on('shown.bs.modal', function() {
            loadCurrentDutyOfficers(config, { preselect: false });
        });

        $(config.updateButton).on('click', function() {
            updateDutyOfficers(config);
        });
    });
});

function resolveElement(reference) {
    if (!reference) {
        return null;
    }

    if (typeof Element !== 'undefined' && reference instanceof Element) {
        return reference;
    }

    if (typeof reference === 'string') {
        const selector = reference.startsWith('#') || reference.startsWith('.') ? reference : `#${reference}`;
        return document.querySelector(selector);
    }

    return null;
}

function clearDutyOfficerSelections(config) {
    const officerSelectElement = resolveElement(config?.officerSelect);
    const masterSelectElement = resolveElement(config?.masterSelect);

    if (officerSelectElement) {
        $(officerSelectElement).val('').trigger('change');
    }

    if (masterSelectElement) {
        $(masterSelectElement).val('').trigger('change');
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

// Função para carregar os oficiais de serviço atuais
function loadCurrentDutyOfficers(config, options = {}) {
    if (!config) {
        return;
    }

    const shouldPreselect = options.preselect !== undefined ? Boolean(options.preselect) : true;

    const loadingElement = $(config.loadingIndicator);
    const displayElement = $(config.displayContainer);
    const errorElement = $(config.errorContainer);
    const errorMessageElement = $(config.errorMessage);

    if (loadingElement.length) {
        loadingElement.removeClass('d-none');
    }
    if (displayElement.length) {
        displayElement.addClass('d-none');
    }
    if (errorElement.length) {
        errorElement.addClass('d-none');
    }

    // Usar o proxy PHP no mesmo domínio
    fetch(dutyOfficersApiUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(async response => {
        if (!response.ok) {
            // Tenta obter a mensagem de erro do servidor
            try {
                const errorData = await response.json();
                throw new Error(errorData.error || `Erro HTTP ${response.status}: ${response.statusText}`);
            } catch (parseError) {
                // Se não conseguir fazer parse do JSON, usa mensagem genérica
                throw new Error(`Erro HTTP ${response.status}: ${response.statusText}`);
            }
        }
        return response.json();
    })
    .then(data => {
        if (loadingElement.length) {
            loadingElement.addClass('d-none');
        }

        if (data.success) {
            if (displayElement.length) {
                displayElement.removeClass('d-none');
            }
            if (errorElement.length) {
                errorElement.addClass('d-none');
            }

            const officerName = data.officers?.officerName ?? null;
            const officerRank = data.officers?.officerRank ?? null;
            const officerDisplayName = data.officers?.officerDisplayName ?? officerName;
            const masterName = data.officers?.masterName ?? null;
            const masterRank = data.officers?.masterRank ?? null;
            const masterDisplayName = data.officers?.masterDisplayName ?? masterName;

            const currentOfficerElement = $(config.currentOfficer);
            const currentMasterElement = $(config.currentMaster);
            const lastUpdatedElement = $(config.lastUpdated);

            if (currentOfficerElement.length) {
                currentOfficerElement.text(formatDutyDisplay(officerDisplayName, officerRank));
            }

            if (currentMasterElement.length) {
                currentMasterElement.text(formatDutyDisplay(masterDisplayName, masterRank));
            }

            if (lastUpdatedElement.length) {
                const updatedDate = data.officers?.updatedAt ? new Date(data.officers.updatedAt) : null;
                lastUpdatedElement.text(updatedDate ? updatedDate.toLocaleString('pt-BR') : '-');
            }

            if (shouldPreselect && (data.officers?.officerName || data.officers?.officerRank)) {
                selectOptionByValue(config.officerSelect, data.officers.officerName, data.officers.officerRank);
            }

            if (shouldPreselect && (data.officers?.masterName || data.officers?.masterRank)) {
                selectOptionByValue(config.masterSelect, data.officers.masterName, data.officers.masterRank);
            }
        } else {
            if (displayElement.length) {
                displayElement.addClass('d-none');
            }
            if (errorElement.length) {
                errorElement.removeClass('d-none');
            }
            if (errorMessageElement.length) {
                errorMessageElement.text(data.error || 'Erro ao carregar dados dos oficiais de serviço.');
            }
            console.error('Erro ao carregar dados:', data.error);
        }
    })
    .catch(error => {
        if (loadingElement.length) {
            loadingElement.addClass('d-none');
        }
        if (displayElement.length) {
            displayElement.addClass('d-none');
        }
        if (errorElement.length) {
            errorElement.removeClass('d-none');
        }
        if (errorMessageElement.length) {
            errorMessageElement.text(error.message || 'Erro na comunicação com o servidor. Tente novamente.');
        }
        console.error('Erro na requisição:', error);
    });
}

function refreshDutyOfficerModals(options = {}) {
    activeDutyModalConfigs.forEach(config => {
        loadCurrentDutyOfficers(config, options);
    });
}

// Função para atualizar os oficiais de serviço
function updateDutyOfficers(config) {
    if (!config) {
        return;
    }

    const formError = $(config.formError);
    const updateButton = $(config.updateButton);
    const updateSpinner = $(config.updateSpinner);

    if (formError.length) {
        formError.text('');
        formError.addClass('d-none');
    }

    if (updateButton.length) {
        updateButton.prop('disabled', true);
    }
    if (updateSpinner.length) {
        updateSpinner.removeClass('d-none');
    }

    const officerSelectElement = resolveElement(config.officerSelect);
    const masterSelectElement = resolveElement(config.masterSelect);

    const officerOption = officerSelectElement?.options[officerSelectElement.selectedIndex] ?? null;
    const masterOption = masterSelectElement?.options[masterSelectElement.selectedIndex] ?? null;

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

    if (officerName === "" && masterName === "") {
        if (formError.length) {
            formError.text('Selecione pelo menos um oficial de serviço.');
            formError.removeClass('d-none');
        }
        if (updateButton.length) {
            updateButton.prop('disabled', false);
        }
        if (updateSpinner.length) {
            updateSpinner.addClass('d-none');
        }
        return;
    }

    const officerData = {
        officerName: officerName === "" ? null : officerName,
        officerRank: officerRank === null || officerRank === '' ? null : officerRank,
        officerDisplayName: officerDisplayName === '' ? null : officerDisplayName,
        masterName: masterName === "" ? null : masterName,
        masterRank: masterRank === null || masterRank === '' ? null : masterRank,
        masterDisplayName: masterDisplayName === '' ? null : masterDisplayName,
    };

    fetch(dutyOfficersApiUrl, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
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
        if (updateButton.length) {
            updateButton.prop('disabled', false);
        }
        if (updateSpinner.length) {
            updateSpinner.addClass('d-none');
        }

        if (data.success) {
            alert('Oficiais de serviço atualizados com sucesso!');
            refreshDutyOfficerModals({ preselect: false });
            clearDutyOfficerSelections(config);
        } else {
            if (formError.length) {
                formError.text(data.error || 'Erro ao atualizar oficiais de serviço.');
                formError.removeClass('d-none');
            }
        }
    })
    .catch(error => {
        if (updateButton.length) {
            updateButton.prop('disabled', false);
        }
        if (updateSpinner.length) {
            updateSpinner.addClass('d-none');
        }
        if (formError.length) {
            formError.text('Erro na comunicação com o servidor. Tente novamente.');
            formError.removeClass('d-none');
        }
        console.error('Erro na requisição:', error);
    });
}

// Função para selecionar uma opção no dropdown pelo valor
function selectOptionByValue(selectReference, value, rank = null) {
    const selectElement = resolveElement(selectReference);
    if (!selectElement) {
        return;
    }

    const normalizedValue = typeof value === 'string' ? value.trim() : '';
    const normalizedRank = typeof rank === 'string' ? rank.trim() : '';

    if (normalizedValue === '' && normalizedRank === '') {
        $(selectElement).val('').trigger('change');
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
            $(selectElement).val(option.value).trigger('change');
            return;
        }
    }
}
</script>


</body>
</html>
