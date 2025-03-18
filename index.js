let apiKey = "AIzaSyBub1CuT0yj0ykXRk2YrJ7kmzdZgfoapnE"

const input = document.querySelector(".search")
const results = document.querySelector(".resultats")
const submit = document.querySelector(".searchBtn")
const favorites = document.querySelector(".favBtn")


submit.addEventListener(`click`, () => {

    fetch(`https://www.googleapis.com/books/v1/volumes?q=intitle:${input.value}&key=${apiKey}`)
        .then(res => res.json())
        .then(data => {
            console.log(data)
        })
        .catch(error => console.log(error))


})

function displayBooks(books) {
    resultsContainer.innerHTML = ""; // Réinitialisation de l'affichage

    if (books && books.length > 0) {
        books.forEach(book => {
            const bookCard = document.createElement('div');
            bookCard.classList.add('book-card');

            const title = document.createElement('h2');
            title.textContent = book.volumeInfo.title || "Titre inconnu";
            bookCard.appendChild(title);

            const img = document.createElement('img');
            img.src = book.volumeInfo.imageLinks ? book.volumeInfo.imageLinks.thumbnail : 'https://via.placeholder.com/128x192';
            img.alt = book.volumeInfo.title;
            bookCard.appendChild(img);

            // Création du bouton Favori
            const favBtn = document.createElement('button');
            favBtn.textContent = isBookInFavs(book.id) ? "Retirer des favoris" : "Ajouter aux favoris";
            favBtn.addEventListener("click", () => toggleFav(book, favBtn));
            bookCard.appendChild(favBtn);

            resultsContainer.appendChild(bookCard);
        });
    } else {
        resultsContainer.innerHTML = "<p>Aucun livre trouvé.</p>";
    }
}



