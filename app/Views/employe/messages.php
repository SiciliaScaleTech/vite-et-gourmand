<?php
/** @var array $messages */
/** @var array $messages_archives */
/** @var string $message */
?>
<main class="container py-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
        <div>
            <h2 class="mb-1 fw-bold fs-3">Boîte de réception</h2>
            <p class="text-muted mb-0">Consultez les messages et répondez directement depuis votre boîte mail.</p>
        </div>
        <a href="<?= BASE_URL ?>index.php?page=employe-dashboard" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
            ← Tableau de bord
        </a>
    </div>

    <?= $message ?>

    <!-- Navigation Onglets -->
    <ul class="nav nav-tabs mb-4 border-bottom-0" id="messageTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold rounded-pill me-2 border-0 shadow-sm" id="actifs-tab" data-bs-toggle="tab" data-bs-target="#actifs" type="button" role="tab">
                En attente (<?= count($messages) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold rounded-pill border-0 shadow-sm text-secondary" id="archives-tab" data-bs-toggle="tab" data-bs-target="#archives" type="button" role="tab">
                Traités / Archivés (<?= count($messages_archives) ?>)
            </button>
        </li>
    </ul>

    <div class="tab-content" id="messageTabsContent">
        
        <!-- ONGLET 1 : BOÎTE DE RÉCEPTION -->
        <div class="tab-pane fade show active" id="actifs" role="tabpanel">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <?php if (empty($messages)): ?>
                        <div class="p-5 text-center text-muted">
                            <p class="mb-0 fw-semibold">Aucun nouveau message en attente. </p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 25%;">Expéditeur</th>
                                        <th class="py-3" style="width: 55%;">Message</th>
                                        <th class="py-3 text-end pe-4" style="width: 20%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $msg): ?>
                                        <tr>
                                            <td class="ps-4 pt-3 align-top">
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($msg['nom'] . ' ' . ($msg['prenom'] ?? '')) ?></div>
                                                <div class="text-muted small mb-1"><?= htmlspecialchars($msg['email']) ?></div>
                                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill small">
                                                    <?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?>
                                                </span>
                                            </td>
                                            <td class="pt-3 align-top">
                                                <div class="p-3 bg-light rounded-3 border small text-secondary">
                                                    <strong class="text-dark d-block mb-1">Sujet : <?= htmlspecialchars($msg['sujet'] ?? 'Demande de contact') ?></strong>
                                                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4 pt-3 align-top">
                                                <div class="d-flex flex-column gap-2 align-items-end">
                                                    
                                                    <?php 
                                                    // Préparation du mailto intelligent
                                                    $sujetMail = "Réponse à votre message : " . ($msg['sujet'] ?? 'Contact');
                                                    $corpsMail = "\n\n--- Message d'origine de " . $msg['nom'] . " (le " . date('d/m/Y', strtotime($msg['date_envoi'])) . ") ---\n" . $msg['message'];
                                                    $mailtoUrl = "mailto:" . $msg['email'] . "?subject=" . rawurlencode($sujetMail) . "&body=" . rawurlencode($corpsMail);
                                                    ?>
                                                    
                                                    <!-- BOUTON ENVOI VERS APPLICATION MAIL -->
                                                    <a href="<?= $mailtoUrl ?>" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm">
                                                        Répondre par mail
                                                    </a>
                                                    
                                                    <!-- BOUTON ARCHIVER -->
                                                    <a href="<?= BASE_URL ?>index.php?page=employe-messages&action=archiver&id_message=<?= $msg['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                        Archiver
                                                    </a>

                                                    <!-- SUPPRIMER -->
                                                    <a href="<?= BASE_URL ?>index.php?page=employe-messages&action=supprimer&id_message=<?= $msg['id'] ?>" class="text-danger small text-decoration-none me-2 mt-1" onclick="return confirm('Supprimer ce message ?');">
                                                        Supprimer
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!--ARCHIVES -->
        <div class="tab-pane fade" id="archives" role="tabpanel">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <?php if (empty($messages_archives)): ?>
                        <div class="p-5 text-center text-muted">
                            <p class="mb-0 fw-semibold">Aucun message archivé.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3">Expéditeur</th>
                                        <th class="py-3">Message Historique</th>
                                        <th class="py-3 text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages_archives as $msg): ?>
                                        <tr class="table-secondary-subtle text-muted">
                                            <td class="ps-4" style="width: 25%;">
                                                <div class="fw-bold"><?= htmlspecialchars($msg['nom']) ?></div>
                                                <span class="badge bg-dark-subtle text-dark-emphasis rounded-pill small">Archivé</span>
                                            </td>
                                            <td style="width: 55%;">
                                                <div class="p-3 bg-white rounded-3 border small">
                                                    <strong>Sujet : <?= htmlspecialchars($msg['sujet'] ?? 'Sans sujet') ?></strong><br>
                                                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4" style="width: 20%;">
                                                <a href="<?= BASE_URL ?>index.php?page=employe-messages&action=supprimer&id_message=<?= $msg['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Supprimer définitivement de l\'historique ?');">
                                                    Supprimer
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</main>