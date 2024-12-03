<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once INCLUDE_PATH . '/functions.php';
require_once LAYOUT_PATH . '/header.php';

// Dossier d'upload
$uploadDir = '../img/';
$activities = loadJsonData('activities');
$message = '';
$messageType = '';

// Gestion des actions du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $index = $_POST['index'] ?? null;
    $imageName = null;

    try {
        if ($action === 'add' || $action === 'edit') {
            // Gestion de l'upload d'image
            if (!empty($_FILES['image']['name'])) {
                $imageName = basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    throw new Exception('Erreur lors du téléchargement de l\'image.');
                }
            }

            // Conserver l'image précédente si aucune nouvelle image n'est ajoutée
            if ($action === 'edit' && empty($_FILES['image']['name'])) {
                $imageName = $activities[$index]['image'];
            } else {
                $imageName = 'img/' . $imageName;
            }

            // Préparer les données de l'activité
            $steps = array_map('trim', explode(',', $_POST['steps'] ?? ''));
            $activityData = [
                'date' => $_POST['date'],
                'time' => $_POST['time'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'details' => $_POST['details'],
                'steps' => $steps,
                'image' => $imageName,
                'button' => $_POST['button'],
                'link' => $_POST['link'],
            ];

            if ($action === 'add') {
                $activities[] = $activityData;
                $message = 'Activité ajoutée avec succès !';
                $messageType = 'success';
            } elseif ($action === 'edit') {
                $activities[$index] = $activityData;
                $message = 'Activité modifiée avec succès !';
                $messageType = 'success';
            }
        } elseif ($action === 'delete') {
            unset($activities[$index]);
            $activities = array_values($activities); // Réindexation
            $message = 'Activité supprimée avec succès !';
            $messageType = 'danger';
        }

        // Sauvegarder les données mises à jour
        saveJsonData('activities', $activities);

        // Rediriger vers la même page pour éviter la page blanche
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'danger';
    }
}
?>

<!-- Contenu HTML -->
<div class="container my-5">
    <!-- Titre -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-dark"><i class="fas fa-calendar-check"></i> Gestion des Activités</h1>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Ajouter une activité
        </button>
    </div>

    <!-- Messages -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $messageType ?> d-flex align-items-center" role="alert">
            <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> me-2"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Tableau des activités -->
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Description</th>
                <th>Étapes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activities as $index => $activity): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td>
                        <img src="../<?= htmlspecialchars($activity['image']) ?>" class="img-fluid rounded" style="width: 150px; height: auto;">
                    </td>
                    <td><?= htmlspecialchars($activity['title']) ?></td>
                    <td><?= htmlspecialchars($activity['date']) ?></td>
                    <td><?= htmlspecialchars($activity['time']) ?></td>
                    <td><?= htmlspecialchars($activity['description']) ?></td>
                    <td>
                        <ul>
                            <?php foreach ($activity['steps'] as $step): ?>
                                <li><?= htmlspecialchars($step) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-<?= $index ?>">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="index" value="<?= $index ?>">
                            <button name="action" value="delete" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Édition -->
                <div class="modal fade" id="editModal-<?= $index ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="index" value="<?= $index ?>">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier l'activité</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Titre</label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($activity['title']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Date</label>
                                        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($activity['date']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Heure</label>
                                        <input type="time" name="time" class="form-control" value="<?= htmlspecialchars($activity['time']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" required><?= htmlspecialchars($activity['description']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Étapes (séparées par des virgules)</label>
                                        <input type="text" name="steps" class="form-control" value="<?= htmlspecialchars(implode(', ', $activity['steps'])) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Ajout -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une activité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Titre</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Heure</label>
                        <input type="time" name="time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Étapes (séparées par des virgules)</label>
                        <input type="text" name="steps" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once LAYOUT_PATH . '/footer.php'; ?>