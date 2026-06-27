
<main>
    <section class="hero-banner text-center py-5 d-flex align-items-center" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1920') center/cover; min-height: 70vh;">
        <div class="container text-white my-auto">
            <h1 class="display-3 fw-bold mb-3 text-uppercase tracking-wide" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.6);">
                Vite & Gourmand
            </h1>
            <p class="fs-4 mb-4 opacity-90" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.6);">
                L'excellence culinaire livrée à votre porte en un éclair.
            </p>
            <a href="index.php?page=menus" class="btn btn-cheddar btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg transform-hover">
                Commander maintenant
            </a>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-3">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <span class="badge bg-light text-cheddar fw-bold px-3 py-2 rounded-pill mb-3 text-uppercase">Notre Concept</span>
                    <h2 class="fw-bold mb-4 display-6">Une passion, deux talents</h2>
                    <p class="lead text-muted lh-base">
                        Vite & Gourmand, c'est l'alliance parfaite entre la passion de <strong>Julie</strong> pour la gastronomie et le sens du service de <strong>José</strong>.
                    </p>
                    <p class="text-secondary lh-lg">
                        Notre concept est simple : vous proposer des repas sains, cuisinés chaque matin avec des produits de saison, et vous les livrer en un temps record. Nous croyons fermement que "bien manger" ne devrait jamais être sacrifié par manque de temps.
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <div class="position-relative d-inline-block">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=600" class="img-fluid rounded-4 shadow-lg border border-5 border-white" alt="Cuisine de Julie">
                        <div class="position-absolute bottom-0 start-0 m-3 bg-cheddar text-dark p-3 rounded-3 shadow fw-bold">
                            100% Fait Maison
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center py-3">
            <h2 class="fw-bold mb-2 display-6">Notre expertise à votre service</h2>
            <p class="text-muted mb-5">Pourquoi choisir Vite & Gourmand pour vos repas ?</p>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-4 bg-white rounded-4 border-0 shadow-sm h-100 card-hover">
                        <div class="fs-1 mb-3 text-cheddar"></div>
                        <h4 class="fw-bold mb-3">Julie</h4>
                        <p class="text-muted mb-0">Chef de formation, elle imagine et prépare des menus équilibrés qui revisitent avec brio les grands classiques de la gastronomie.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 bg-white rounded-4 border-0 shadow-sm h-100 card-hover">
                        <div class="fs-1 mb-3 text-cheddar"></div>
                        <h4 class="fw-bold mb-3">José</h4>
                        <p class="text-muted mb-0">Expert en logistique urbaine, il vous garantit une livraison éclair à vélo tout en préservant la température idéale de vos plats.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 bg-white rounded-4 border-0 shadow-sm h-100 card-hover">
                        <div class="fs-1 mb-3 text-cheddar"></div>
                        <h4 class="fw-bold mb-3">Engagements</h4>
                        <p class="text-muted mb-0">Nous travaillons exclusivement avec des producteurs locaux écoresponsables pour vous garantir une fraîcheur et une traçabilité irréprochables.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white text-center">
        <div class="container py-3">
            <h2 class="mb-2 fw-bold display-6">Ce que disent nos clients</h2>
            <p class="text-muted mb-5">Les retours de notre communauté gourmande</p>
            
            <?php if (empty($avis_valides)): ?>
                <p class="text-muted fs-5">Aucun avis disponible pour le moment. Soyez le premier à en laisser un !</p>
            <?php else: ?>
                <div id="carouselAvis" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner justify-content-center">
                        <?php foreach ($avis_valides as $index => $av): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="5000">
                                <div class="row justify-content-center">
                                    <div class="col-md-8 px-4">
                                        <div class="bg-light shadow-sm p-4 p-md-5 rounded-4 my-3 border border-light position-relative">
                                            <span class="position-absolute top-0 start-0 translate-middle fs-1 text-muted opacity-25 ps-4 pt-4">“</span>
                                            
                                            <div class="text-warning mb-3 fs-4">
                                                <?= str_repeat('★', $av['note']) ?><?= str_repeat('☆', 5 - $av['note']) ?>
                                            </div>
                                            
                                            <p class="fs-5 text-dark font-italic mb-4 lh-base">
                                                " <?= htmlspecialchars($av['commentaire']) ?> "
                                            </p>
                                            
                                            <h6 class="fw-bold text-cheddar mb-0 text-uppercase tracking-wider">
                                                - <?= htmlspecialchars($av['prenom'] . ' ' . strtoupper(substr($av['nom'], 0, 1)) . '.') ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselAvis" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselAvis" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>