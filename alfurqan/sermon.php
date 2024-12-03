<?php
require_once 'includes/config.php';
require_once INCLUDE_PATH . '/functions.php';
require_once LAYOUT_PATH . '/header.php';

$uploadDir = '../img/';
$sermons = loadJsonData('sermons');
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
                $imageName = $sermons[$index]['image'];
            }

            // Prépare les données du sermon
            $steps = array_map('trim', explode(',', $_POST['etape'] ?? ''));
            $sermonData = [
                'title' => $_POST['title'],
                'date' => $_POST['date'],
                'author' => $_POST['author'],
                'image' => $imageName,
                'shortDescription' => $_POST['shortDescription'],
                'description' => $_POST['description'],
                'etape' => $steps,
            ];

            if ($action === 'add') {
                $sermons[] = $sermonData;
                $message = 'Sermon ajouté avec succès !';
            } elseif ($action === 'edit') {
                $sermons[$index] = $sermonData;
                $message = 'Sermon mis à jour avec succès !';
            }
        } elseif ($action === 'delete') {
            unset($sermons[$index]);
            $sermons = array_values($sermons); // Réindexation
            $message = 'Sermon supprimé avec succès !';
        }

        // Sauvegarde des données mises à jour
        saveJsonData('sermons', $sermons);
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<div class="container my-4 text-light">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-black"><i class="fas fa-book"></i> Gestion des Sermons</h1>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Ajouter un sermon
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
                <th>Auteur</th>
                <th>Description Courte</th>
                <th>Etapes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sermons as $index => $sermon): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><img src="../img/<?= htmlspecialchars($sermon['image']) ?>" class="img-thumbnail" width="50"></td>
                    <td><?= htmlspecialchars($sermon['title']) ?></td>
                    <td><?= htmlspecialchars($sermon['date']) ?></td>
                    <td><?= htmlspecialchars($sermon['author']) ?></td>
                    <td><?= htmlspecialchars($sermon['shortDescription']) ?></td>
                    <td>
                        <ul>
                            <?php foreach ($sermon['etape'] as $step): ?>
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
                                    <h5 class="modal-title text-dark">Modifier le sermon</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body bg-light">
                                    <div class="mb-3">
                                        <label>Titre</label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($sermon['title']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Date</label>
                                        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($sermon['date']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Auteur</label>
                                        <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($sermon['author']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Description Courte</label>
                                        <textarea name="shortDescription" class="form-control" required><?= htmlspecialchars($sermon['shortDescription']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" required><?= htmlspecialchars($sermon['description']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Etapes (séparées par des virgules)</label>
                                        <textarea name="etape" class="form-control" required><?= implode(', ', $sermon['etape']) ?></textarea>
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
                    <h5 class="modal-title text-dark">Ajouter un sermon</h5>
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
                        <label>Auteur</label>
                        <input type="text" name="author" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description Courte</label>
                        <textarea name="shortDescription" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Etapes (séparées par des virgules)</label>
                        <textarea name="etape" class="form-control" required></textarea>
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