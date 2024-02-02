document.addEventListener('DOMContentLoaded', function () {
    const loadMoreBtn = document.getElementById('load-more');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            const slug = this.dataset.slug;
            let offsetPost = parseInt(this.dataset.offsetpost, 10);
            const container = document.getElementById('post-container');
            this.innerHTML = 'Chargement...';

            fetch(`/trick/${slug}/nextposts/${offsetPost}`)
                .then(response => response.text())
                .then(html => {
                    if (html.length === 0) {
                        loadMoreBtn.remove();
                    } else {
                        container.insertAdjacentHTML('beforeend', html);
                        loadMoreBtn.dataset.offsetpost = (offsetPost + 5).toString();
                        loadMoreBtn.innerHTML = 'Charger plus';
                    }
                })
                .catch(error => {
                    console.error('Error loading more posts:', error);
                    loadMoreBtn.innerHTML = 'Error, try again';
                });
        });
    }
});
