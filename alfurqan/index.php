<?php
require_once 'includes/config.php';
require_once INCLUDE_PATH . '/functions.php';
require_once LAYOUT_PATH . '/header.php';

$sermons = loadJsonData('sermons');
$sermonCount = count(loadJsonData('sermons'));
$activityCount = count(loadJsonData('activities'));
$eventCount = count(loadJsonData('events'));

?>

<!-- Section Bannière -->
<div class="banner" style="background: url('https://images.pexels.com/photos/26319679/pexels-photo-26319679/free-photo-of-homme-religion-lire-islam.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2') center/cover no-repeat; padding: 50px 20px; color: white; text-align: center;">
    <div class="banner-content" style="max-width: 600px; margin: auto;">
        <h2 class="display-4">Tableau de Bord</h2>
        <p class="lead">Suivez vos progrès et gérez vos ressources.</p>
    </div>
</div>

<!-- Section Statistiques -->
<div class="container my-5">
    <h3 class="text-center">Statistiques de Performance</h3>
    <br>
    <br>
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card border-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Sérmons Créés</h5>
                    <p class="card-text display-4"><?= $sermonCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Activités Créées</h5>
                    <p class="card-text display-4"><?= $activityCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Événements Créés</h5>
                    <p class="card-text display-4"><?= $eventCount ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pied de page -->
<?php
require_once LAYOUT_PATH . '/footer.php';
?>