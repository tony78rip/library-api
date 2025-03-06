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

function displayMovies(books) {
    if (books.length) {
        
        books.forEach(book => {
    
            // Je crée les éléments HTML pour afficher chaque livre
            let container = document.createElement("div")
            let title = document.createElement("h2")
            let image = document.createElement("img")


    
            // Je donne du contenu texte a mes éléménts HTML (et une source à mon image)
            title.textContent = book.volumeinfo.Title
            image.src = book.volumeinfo.imageLinks

            // Je regroupe mes éléments HTML pour chaque film dans un container (ou un wrapper / card)
            container.append(image, title)
    
            // J'insère le container dans ma zone de resultats (dans le HTLM)
            results.appendChild(container)
        })  

    } else {
        results.innerHTML = "Aucun films à afficher"
    }
}

