<?php
require_once 'includes/config.php';
require_once INCLUDE_PATH . '/functions.php';
require_once LAYOUT_PATH . '/header.php';

$uploadDir = '../img/';
$events = loadJsonData('events');
$message = '';

// Gestion des actions du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
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

            // Garde l'ancienne image si aucune nouvelle n'est uploadée
            if ($action === 'edit' && empty($_FILES['image']['name'])) {
                $imageName = $events[$index]['image'];
            }

            // Prépare les données de l'événement
            $eventData = [
                'title' => $_POST['title'],
                'date' => $_POST['date'],
                'time' => $_POST['time'],
                'description' => $_POST['description'],
                'image' => $imageName,
            ];

            if ($action === 'add') {
                $events[] = $eventData;
                $message = 'Événement ajouté avec succès !';
            } elseif ($action === 'edit') {
                $events[$index] = $eventData;
                $message = 'Événement mis à jour avec succès !';
            }
        } elseif ($action === 'delete') {
            unset($events[$index]);
            $events = array_values($events); // Réindexation
            $message = 'Événement supprimé avec succès !';
        }

        // Sauvegarde des données mises à jour
        saveJsonData('events', $events);
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<div class="container my-4 text-light">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-black"><i class="fas fa-calendar-alt"></i> Gestion des Événements</h1>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Ajouter un événement
        </button>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-light d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle me-2 text-dark"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $index => $event): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><img src="../<?= htmlspecialchars($event['image']) ?>" class="img-thumbnail" width="50"></td>
                    <td><?= htmlspecialchars($event['title']) ?></td>
                    <td><?= htmlspecialchars($event['date']) ?></td>
                    <td><?= htmlspecialchars($event['time']) ?></td>
                    <td><?= htmlspecialchars($event['description']) ?></td>
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
                                    <h5 class="modal-title text-dark">Modifier l'événement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body bg-light">
                                    <div class="mb-3">
                                        <label>Titre</label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['title']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Date</label>
                                        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($event['date']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Heure</label>
                                        <input type="time" name="time" class="form-control" value="<?= htmlspecialchars($event['time']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" required><?= htmlspecialchars($event['description']) ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-dark">Enregistrer</button>
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
                    <h5 class="modal-title text-dark">Ajouter un événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
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
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once LAYOUT_PATH . '/footer.php';
?>