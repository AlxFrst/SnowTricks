// Generator of new form to add a trick's picture or video
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed');

    const addNewItem = (e) => {
        const collectionHolder = document.querySelector(e.currentTarget.dataset.collection);
        const item = document.createElement("div");
        item.classList.add("w-1/3", "p-2", "existing-item");

        item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);

        // Ajouter le nouvel élément
        collectionHolder.appendChild(item);
        collectionHolder.dataset.index++;

        // Ajouter l'écouteur sur le bouton supprimer du nouvel élément
        item.querySelector('.btn-remove').addEventListener("click", function () { item.remove(); });
    };

    const addClickEventToRemoveButtons = () => {
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.removeEventListener("click", removeItem); // Éviter les écouteurs multiples
            btn.addEventListener("click", removeItem);
        });
    };

    const removeItem = (e) => {
        e.currentTarget.closest('.existing-item').remove();
    };

    // Ajouter des écouteurs aux boutons d'ajout existants
    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', addNewItem);
    });

    // Ajouter des écouteurs aux boutons de suppression existants
    addClickEventToRemoveButtons();

    // Fonction pour ré-appliquer les écouteurs sur les nouveaux éléments
    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', function () {
            addClickEventToRemoveButtons();
        });
    });
});
