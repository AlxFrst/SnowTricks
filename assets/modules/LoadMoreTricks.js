$(document).ready(function () {
    if (document.getElementById("load-more")) {

        const loadBtn = document.getElementById("load-more");
        let offset = parseInt(loadBtn.dataset.offset, 10);

        loadBtn.addEventListener("click", function (e) {
            e.preventDefault();
            const container = $("#trick-container");
            offset = parseInt(loadBtn.dataset.offset, 10);

            let request = new XMLHttpRequest();
            request.open("GET", "/nextTricks/" + offset);
            request.responseType = "text";
            request.send();
            loadBtn.innerHTML = "Chargement...";
            request.onload = function () {
                loadBtn.innerHTML = "Charger plus";
                let $html = request.response;

                if ($html.length === 0) {
                    loadBtn.remove();
                } else {
                    container.append($html);
                    loadBtn.dataset.offset = (offset + 8).toString();
                }
            }
        });
    }
});