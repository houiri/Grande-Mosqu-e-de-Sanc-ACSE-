    </div>
<!-- Ajouter le lien vers Bootstrap JS et jQuery dans le footer de votre fichier -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
        <script>
            function toggleSidebar() {
                document.querySelector('.sidebar').classList.toggle('show');
            }

            // Afficher le bouton de basculement de la barre latérale sur mobile
            window.addEventListener('resize', function() {
                const toggleButton = document.querySelector('.toggle-sidebar');
                if (window.innerWidth <= 768) {
                    toggleButton.style.display = 'block';
                } else {
                    toggleButton.style.display = 'none';
                    document.querySelector('.sidebar').classList.remove('show');
                }
            });

            // Initialisation
            window.dispatchEvent(new Event('resize'));
        </script>
</body>
</html>