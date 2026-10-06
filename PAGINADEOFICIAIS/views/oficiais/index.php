<?php
$headerData = compact('officerOptions', 'masterOptions');
extract($headerData);
include 'views/partials/header.php';
include_once 'sunset_system_db.php';
SunsetSystemDB::init($pdo);
$todaySunsetTime = SunsetSystemDB::getTodaysSunsetTime();
// $statusData (status temporário bordo/terra) vem de OficialController::index()
$statusData = $statusData ?? [];



?>
<script>
$(document).ready(function() {
    <?php if (isset($_SESSION['password_change_success'])): ?>
        $('#passwordChangeSuccessModal').modal('show');
        <?php unset($_SESSION['password_change_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        $('#errorModal').modal('show');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

   

    <?php if (isset($_SESSION['login_error'])): ?>
        $('#loginModal').modal('show');
        <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>
    $(document).ready(function() {
    // Exemplo para mostrar o modal de erro ao adicionar usuário
    <?php if (isset($_SESSION['user_add_error'])): ?>
        $('#userAddErrorModal').modal('show');
        <?php unset($_SESSION['user_add_error']); ?>
    <?php endif; ?>
});
});
</script>
<script>
    // Define a variável global no JavaScript com base no PHP
    var userIsAuthenticated = <?php echo json_encode($is_logged_in); ?>;
</script>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
   
    <script src="../../js/select2.min.js"></script>

   
    <title>Quadro de Oficiais</title>
  
</head>
<body class="<?php echo $is_logged_in ? 'logged-in' : 'logged-out'; ?> <?php echo $is_portaria ? 'portaria' : ''; ?>">
<script>
// Função para atualizar a cor do toggle com base no estado do checkbox
function updateToggleColor(checkbox) {
    var slider = checkbox.nextElementSibling;
    if (checkbox.checked) {
        slider.style.backgroundColor = '#28a745'; // Verde
    } else {
        slider.style.backgroundColor = '#dc3545'; // Vermelho
    }
}

// Função para atualizar a barra de status (cor da linha)
function updateStatusBar(oficialId, isPresent) {
    console.log('Atualizando linha para oficial ID:', oficialId, 'Presente:', isPresent);
    
    // Método 1: Procurar pelo ID da linha
    var row = document.getElementById('oficial-' + oficialId);
    
    // Método 2: Se não encontrar, procurar pelo data-oficial-id
    if (!row) {
        row = document.querySelector('tr[data-oficial-id="' + oficialId + '"]');
    }
    
    // Método 3: Se ainda não encontrar, procurar pelo checkbox e subir para o TR pai
    if (!row) {
        var checkbox = document.querySelector('input[data-id="' + oficialId + '"]');
        if (checkbox) {
            row = checkbox.closest('tr');
        }
    }
    
    if (row) {
        console.log('Linha encontrada:', row);
        
        // Remove classes existentes
        row.classList.remove('present', 'absent');
        
        // Adiciona a classe apropriada baseada no status
        if (isPresent) {
            row.classList.add('present');
            console.log('Adicionada classe present');
        } else {
            row.classList.add('absent');
            console.log('Adicionada classe absent');
        }
    } else {
        console.error('Linha não encontrada para oficial ID:', oficialId);
    }
}

// Função principal para alternar presença
window.togglePresence = function(id) {
    console.log('togglePresence chamado para ID:', id);
    
    // Seleciona o checkbox baseado no ID fornecido
    var checkbox = document.querySelector('input[data-id="' + id + '"]');
    if (!checkbox) {
        console.error('Checkbox com ID ' + id + ' não encontrado.');
        return;
    }

    // Define o status com base no estado atual do checkbox
    var status = checkbox.checked ? 'bordo' : 'terra';
    console.log('Novo status:', status);

    // Envia o status atualizado para o servidor via AJAX
    $.ajax({
        url: 'views/update_status_file.php',
        type: 'POST',
        data: { id: id, status: status },
        success: function(response) {
            console.log('Resposta do servidor:', response);
            if (response !== 'Status atualizado com sucesso.') {
                alert('Falha ao atualizar o status: ' + response);
                checkbox.checked = !checkbox.checked;
                updateToggleColor(checkbox);
                updateStatusBar(id, checkbox.checked);
            } else {
                // Sucesso - atualiza os elementos visuais
                updateToggleColor(checkbox);
                updateStatusBar(id, checkbox.checked);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Erro AJAX:', textStatus, errorThrown);
            alert('Somente usuários logados podem realizar esta ação.');
            checkbox.checked = !checkbox.checked;
            updateToggleColor(checkbox);
            updateStatusBar(id, checkbox.checked);
        }
    });
};


    
    
    // Novo código para os checkboxes
    var checkboxes = document.querySelectorAll('input[type="checkbox"][data-id]');
    console.log('Encontrados', checkboxes.length, 'checkboxes');
    
    checkboxes.forEach(function(checkbox, index) {
        var oficialId = checkbox.getAttribute('data-id');
        var oficialName = checkbox.getAttribute('data-name') || 'Desconhecido';
        
        console.log('Inicializando checkbox', index + 1, '- ID:', oficialId, 'Nome:', oficialName);
        
        // Configura a cor inicial do toggle
        updateToggleColor(checkbox);
        
        // Configura a cor inicial da linha baseada no estado do checkbox
        updateStatusBar(oficialId, checkbox.checked);

        // Adiciona o event listener para mudanças
        checkbox.addEventListener('change', function(event) {
            console.log('Checkbox mudou - ID:', oficialId, 'Novo estado:', checkbox.checked);
            
            // Verifica se o usuário está autenticado antes de chamar a função
            if (typeof userIsAuthenticated !== 'undefined' && !userIsAuthenticated) {
                // Reverte a mudança do checkbox
                checkbox.checked = !checkbox.checked;
                alert('Você precisa estar logado para realizar essa ação.');
                return;
            }

            // Chama a função para alterar a presença
            togglePresence(oficialId);
        });
    });
    
    console.log('Inicialização completa');

// Função para debug
function debugRowStatus() {
    var rows = document.querySelectorAll('tr[id^="oficial-"]');
    console.log('=== DEBUG: Status das linhas ===');
    rows.forEach(function(row) {
        var id = row.id;
        var classes = row.className;
        var checkbox = row.querySelector('input[data-id]');
        var checkboxStatus = checkbox ? checkbox.checked : 'sem checkbox';
        console.log('Row:', id, 'Classes:', classes, 'Checkbox:', checkboxStatus);
    });
    console.log('=== Fim do debug ===');
}
</script>
<style>/* Mantém o bloco de hora e badges lado a lado */
/* === Badges unificados (data, pôr do sol e temperatura) === */
#datetime,
#sunset-badge,
#temperature-badge{
  display:inline-flex;
  align-items:center;
  gap:.35rem;
  padding:6px 12px;              /* menor */
  border-radius:8px;
  background: linear-gradient(180deg,rgba(255,255,255,.18),rgba(255,255,255,.12));
  border:1px solid rgba(255,255,255,.25);
  box-shadow: inset 0 1px 0 rgba(255,255,255,.15), 0 2px 10px rgba(0,0,0,.08);
  color:#fff !important;         /* branquinho */
  font-weight:600;
  font-size:15px;                /* menor */
  line-height:1;
  white-space:nowrap;
  -webkit-backdrop-filter: blur(6px);
  backdrop-filter: blur(6px);
  font-variant-numeric: tabular-nums;
}

/* ícones dos dois badges */
#sunset-badge::before{ content:"🌅"; margin-right:.35rem; opacity:.9; }
#temperature-badge::before{ content:"🌡️"; margin-right:.35rem; opacity:.9; }

/* se quiser a data sem ícone algum: */
#datetime::before{ content:"🕒"; opacity:.85; margin-right:.25rem; }
.quadro-de-oficiais {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Agrupando badges à direita */
.badges-container {
  display: flex;
  align-items: center;
  gap: 8px; /* distância entre cada badge */
}

  </style>
<div class="quadro-de-oficiais">
  QUADRO DE OFICIAIS
  <div class="badges-container">
  <div id="sunset-badge" > Pôr do sol: <?php echo $todaySunsetTime; ?></div>
<div id="temperature-badge" > Temp: --°C</div>
  
          
      
<div class="datetime-cards">
    <div class="datetime-card datetime-card--date" aria-label="Data atual">
      <span class="datetime-card__icon" aria-hidden="true">📅</span>
      <span id="datetime-date" class="datetime-card__text"></span>
    </div>
    <div class="datetime-card datetime-card--time" aria-label="Hora atual">
      <span class="datetime-card__icon" aria-hidden="true">⏰</span>
      <span id="datetime-time" class="datetime-card__text"></span>
    </div>
  </div>
</div>
</div>

<div class="container">
  <div class="row">
    
    <?php
    if (!isset($is_logged_in)) {
        $is_logged_in = false;
    }

$oficiaisRM1 = array_filter($oficiais, function($oficial) {
    return stripos($oficial['descricao'], 'RM1') !== false;
});

// Oficiais sem 'RM1' na descri��o
$oficiaisSemRM1 = array_filter($oficiais, function($oficial) {
    return stripos($oficial['descricao'], 'RM1') === false;
});

$numOficiaisSemRM1 = count($oficiaisSemRM1);

$numOficiaisSemRM1 = count($oficiaisSemRM1);

// Divisão equilibrada
$metade = (int) ceil($numOficiaisSemRM1 / 2);
$numOficiaisColuna1 = $metade;
$numOficiaisColuna2 = $numOficiaisSemRM1 - $metade;
$numOficiaisColuna3 = count($oficiaisRM1); // RM1 fixos


for ($i = 0; $i < 3; $i++): ?>
  <div class="col-md-4">
    <table class="table table-bordered">
      <thead>
        <tr>
          <?php if ($is_admin):  ?>
            <th class="location-cell">POSIÇÕES</th>
          <?php endif; ?>
          <th>POSTO/NOME</th>
          <th class="text-center status-cell">
            <span class="status-label green">A BORDO</span>
          </th>
          <?php if ($is_admin): ?>
            <th class="text-center">AÇÕES</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php
        $start = 0;
        $end = 0;
    
        if ($i == 0) {
          // Coluna 1: at� 13 oficiais sem RM1
          $start = 0;
          $end = $numOficiaisColuna1;
          $oficiaisParaExibir = array_slice($oficiaisSemRM1, $start, $end);
        } elseif ($i == 1) {
          // Coluna 2: at� 12 oficiais sem RM1
          $start = $numOficiaisColuna1;
          $end = $numOficiaisColuna1 + $numOficiaisColuna2;
          $oficiaisParaExibir = array_slice($oficiaisSemRM1, $start, $numOficiaisColuna2);
        } else {
          // Coluna 3: apenas oficiais com RM1
          $oficiaisParaExibir = $oficiaisRM1;
        }
    
        foreach ($oficiaisParaExibir as $oficial):
          $status = isset($statusData[$oficial['id']]) ?
           $statusData[$oficial['id']] : $oficial['status'];
          $checked = $status === 'bordo' ? 'checked' : '';
          ?>
    <tr id='oficial-<?php echo (int)$oficial['id']; ?>'
    data-oficial-id='<?php echo (int)$oficial['id']; ?>'
    class='<?php echo $status === 'bordo' ? "present" : "absent"; ?>'>
<?php if ($is_admin):  ?>
              <td class='location-cell'><?php echo (int)$oficial['localizacao']; ?></td>
            <?php endif; ?>
            <td class='name-column'><img src='<?php echo e($oficial['imagem']); ?>' width='80px'> <?php echo e($oficial['descricao'] . ' ' . $oficial['nome']); ?></td>
            <td class='text-center status-cell'>
              <label class='switch'>
<input type='checkbox' 
       data-id='<?php echo (int)$oficial['id']; ?>'
       data-name='<?php echo e($oficial['nome']); ?>'
       <?php echo $checked; ?>>

              <span class='slider round'></span>
              </label>
            </td>
            <?php if ($is_admin): ?>
              <td class='text-center'>
                <button class='btn btn-add' onclick='abrirModalAdicionar(<?php echo (int)$oficial['localizacao']; ?>)'>+</button>
                <button class='btn btn-danger' onclick='window.excluirOficial(<?php echo (int)$oficial['id']; ?>)'>Excluir</button>
                <button class='btn btn-primary' onclick='abrirModalEditar(<?php echo (int)$oficial['id']; ?>, <?php echo e(json_encode($oficial['nome'])); ?>,
                <?php echo (int)$oficial['posto_id']; ?>,
                <?php echo e(json_encode($status)); ?>,
                 <?php echo (int)$oficial['localizacao']; ?>)'>Editar</button>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endfor; ?>
  </div>
</div>
<?php

?>

  <!-- <?php if ($is_logged_in): ?>
    <a href="#" class="btn btn-success" onclick="abrirModalAdicionar()">Adicionar Oficial</a>
  <?php endif; ?> -->
</div>

<!-- Modal Adicionar Oficial -->
<div id="addOfficialModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="addOfficialForm" method="POST" action="views/process_add_official.php">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
          <h5 class="modal-title">Adicionar Oficial</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="addOfficialMessage" class="text-info"></p>
          <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
          </div>

          <div class="form-group">
  <label for="posto">Posto:</label>
  <select class="form-control" id="posto" name="posto" required>
    <option value="">Selecione um posto</option>
    <?php foreach ($postos as $posto): ?>
      <option value="<?php echo (int)$posto['id']; ?>" data-img="<?php echo e($posto['imagem']); ?>"><?php echo e($posto['descricao']); ?></option>    <?php endforeach; ?>
  </select>
</div>
          <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status" required>
              <option value="bordo">A Bordo</option>
              <option value="terra">Em Terra</option>
            </select>
          </div>
          <div class="form-group">
            <label for="localizacao">Localização:</label>
            <input type="number" class="form-control" id="localizacao" name="localizacao" required readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Adicionar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar Oficial -->
<div id="editOfficialModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editOfficialForm" method="POST" action="views/process_edit_official.php">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
          <h5 class="modal-title">Editar Oficial</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">
          <div class="form-group">
            <label for="edit_nome">Nome:</label>
            <input type="text" class="form-control" id="edit_nome" name="nome" required>
          </div>
          <div class="form-group">
            <label for="edit_posto">Posto:</label>
            <select class="form-control" id="edit_posto" name="posto" required>
              <option value="">Selecione um posto</option>
              <?php foreach ($postos as $posto): ?>
                <option value="<?php echo (int)$posto['id']; ?>"><?php echo e($posto['descricao']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>


          
          <div class="form-group">
            <label for="edit_status">Status:</label>
            <select class="form-control" id="edit_status" name="status" required>
              <option value="bordo">A Bordo</option>
              <option value="terra">Em Terra</option>
            </select>
          </div>

          
          <div class="form-group">



            <label for="edit_localizacao">Localização:</label>
            <input type="number" class="form-control" id="edit_localizacao" name="localizacao" required readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

 <!-- Modal de Sucesso do Oficial -->
<div class="modal fade" id="oficialSuccessModal" tabindex="-1" role="dialog" aria-labelledby="oficialSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="oficialSuccessModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Oficial adicionado com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sucesso da EdiÃ§Ã£o -->
<div class="modal fade" id="editSuccessModal" tabindex="-1" role="dialog" aria-labelledby="editSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSuccessModalLabel">Sucesso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Oficial editado com sucesso!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>




<?php
include 'views/partials/footer.php';
?>
</body>
</html>

