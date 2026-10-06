<?php
// Incluir os dados do pôr do sol
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/sunset_data.php';
$sunsetTime = getTodaySunset();
?>

<!-- Componente visual do pôr do sol -->
<div class="sunset-component" id="sunset-display">
    <span class="sunset-icon">🌅</span>
    <span class="sunset-text">Pôr do sol: <?php echo $sunsetTime; ?></span>
</div>

<style>
/* CSS do componente do pôr do sol */
.sunset-component {
    display: inline-flex;
    align-items: center;
    background: rgba(255, 165, 0, 0.1);
    border: 1px solid rgba(255, 165, 0, 0.3);
    border-radius: 20px;
    padding: 6px 12px;
    margin-left: 15px;
    font-size: 14px;
    color: #ff8c00;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(255, 140, 0, 0.2);
    transition: all 0.3s ease;
}

.sunset-component:hover {
    background: rgba(255, 165, 0, 0.15);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 140, 0, 0.3);
}

.sunset-icon {
    margin-right: 6px;
    font-size: 16px;
    animation: sunset-pulse 3s infinite;
}

.sunset-text {
    white-space: nowrap;
}

/* Animação sutil para o ícone */
@keyframes sunset-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Responsividade */
@media (max-width: 768px) {
    .sunset-component {
        font-size: 12px;
        padding: 4px 8px;
        margin-left: 8px;
    }
    
    .sunset-icon {
        font-size: 14px;
        margin-right: 4px;
    }
}
</style>