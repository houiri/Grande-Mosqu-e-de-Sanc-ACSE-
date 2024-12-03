$(document).ready(function() {
    // Surah pill navigation
    $('.surah-pill').click(function() {
        $('.surah-pill').removeClass('active');
        $(this).addClass('active');
    });

    // Search functionality
    $('.search-bar input').on('input', function() {
        var searchTerm = $(this).val().toLowerCase();
        $('.course-card').each(function() {
            var cardText = $(this).text().toLowerCase();
            $(this).toggle(cardText.indexOf(searchTerm) > -1);
        });
    });

    // Progress tracking
    $('.course-card').click(function() {
        var contentId = $(this).data('id');
        var contentType = $(this).data('type');
        $.post('update_progress.php', {
            content_id: contentId,
            content_type: contentType,
            progress: 100
        }, function(response) {
            console.log('Progress updated:', response);
        });
    });

    $(document).ready(function() {
        // Surah pill navigation
        $('.surah-pill').click(function() {
            $('.surah-pill').removeClass('active');
            $(this).addClass('active');
        });

        // Search functionality
        $('.search-bar input').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            $('.course-card').each(function() {
                var cardText = $(this).text().toLowerCase();
                $(this).toggle(cardText.indexOf(searchTerm) > -1);
            });
        });

        // Progress tracking
        $('.course-card').click(function() {
            var contentId = $(this).data('id');
            var contentType = $(this).data('type');
            $.post('update_progress.php', {
                content_id: contentId,
                content_type: contentType,
                progress: 100
            }, function(response) {
                console.log('Progress updated:', response);
            });
        });

        // Memorization progress
        $('.start-memorization').click(function(e) {
            e.preventDefault();
            var surahNumber = $(this).data('surah');
            var progressBar = $(this).siblings('.progress').find('.progress-bar');
            var currentProgress = parseInt(progressBar.attr('aria-valuenow'));
            var newProgress = Math.min(currentProgress + 10, 100);

            progressBar.css('width', newProgress + '%').attr('aria-valuenow', newProgress).text(newProgress + '%');

            $.post('update_progress.php', {
                content_id: surahNumber,
                content_type: 'memorization',
                progress: newProgress
            }, function(response) {
                console.log('Memorization progress updated:', response);
            });
        });

        // Q&A submission
        $('#question-form').submit(function(e) {
            e.preventDefault();
            var question = $('#question').val();

            // Here you would typically send this to a server to be stored
            // For now, we'll just add it to the list
            $('#qa-list').append('<div class="card mb-3"><div class="card-body"><h5 class="card-title">Q: ' + question + '</h5><p class="card-text">A: Cette question sera répondue prochainement.</p></div></div>');

            $('#question').val('');
        });

        // Load podcasts (simulated)
        function loadPodcasts() {
            var podcasts = [
                { title: "L'importance de la patience en Islam", duration: "25:30" },
                { title: "Comprendre la Zakat", duration: "18:45" },
                { title: "Les bienfaits du jeûne", duration: "22:15" }
            ];

            var podcastHtml = '';
            podcasts.forEach(function(podcast) {
                podcastHtml += `<div class="col-md-4 mb-4"><div class="card"><div class="card-body">
                    <h5 class="card-title">' + podcast.title + '</h5>
                <p class="card-text">Durée: ' + podcast.duration + '</p>
                <a href="#" class="btn btn-primary">Écouter</a>
            </div></div></div>`;
            });

            $('#podcast-list').html(podcastHtml);
        }

        // Load learning history (simulated)
        function loadLearningHistory() {
            var history = [
                { title: "Surah Al-Baqarah", progress: 75 },
                { title: "Cours d'arabe - Leçon 3", progress: 100 },
                { title: "Hadith du jour", progress: 50 }
            ];

            var historyHtml = '';
            history.forEach(function(item) {
                historyHtml += '<div class="col-md-4 mb-4"><div class="card"><div class="card-body">' +
                    '<h5 class="card-title">' + item.title + '</h5>' +
                    '<div class="progress mb-3">' +
                    '<div class="progress-bar" role="progressbar" style="width: ' + item.progress + '%;" aria-valuenow="' + item.progress + '" aria-valuemin="0" aria-valuemax="100">' + item.progress + '%</div>' +
                    '</div>' +
                    '<a href="#" class="btn btn-primary">Continuer</a>' +
                    '</div></div></div>';
            });

            $('#learning-history').html(historyHtml);
        }

        // Load liked content (simulated)
        function loadLikedContent() {
            var likedContent = [
                { title: "L'importance de la charité en Islam", type: "Article" },
                { title: "Récitation de Surah Yasin", type: "Audio" },
                { title: "Les piliers de l'Islam", type: "Vidéo" }
            ];

            var likedHtml = '';
            likedContent.forEach(function(content) {
                likedHtml += '<div class="col-md-4 mb-4"><div class="card"><div class="card-body">' +
                    '<h5 class="card-title">' + content.title + '</h5>' +
                    '<p class="card-text">Type: ' + content.type + '</p>' +
                    '<a href="#" class="btn btn-primary">Voir</a>' +
                    '</div></div></div>';
            });

            $('#liked-content').html(likedHtml);
        }

        // Load favorite content (simulated)
        function loadFavoriteContent() {
            var favoriteContent = [
                { title: "Les noms d'Allah", type: "Liste" },
                { title: "Dua pour le succès", type: "Audio" },
                { title: "Histoire des prophètes", type: "Série" }
            ];

            var favoriteHtml = '';
            favoriteContent.forEach(function(content) {
                favoriteHtml += '<div class="col-md-4 mb-4"><div class="card"><div class="card-body">' +
                    '<h5 class="card-title">' + content.title + '</h5>' +
                    '<p class="card-text">Type: ' + content.type + '</p>' +
                    '<a href="#" class="btn btn-primary">Accéder</a>' +
                    '</div></div></div>';
            });

            $('#favorite-content').html(favoriteHtml);
        }

        // Call functions to load content
        if ($('#podcast-list').length) loadPodcasts();
        if ($('#learning-history').length) loadLearningHistory();
        if ($('#liked-content').length) loadLikedContent();
        if ($('#favorite-content').length) loadFavoriteContent();
    });
});