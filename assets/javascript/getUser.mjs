import {handleFetchRequest} from "./utils/handleFetchRequest.mjs";

if (window.location.href.match(/user\/\d+/)) {
    window.addEventListener('DOMContentLoaded', handleUserLoad);
}

async function handleUserLoad() {
    const user = await getUser();
    
    const cardContent = document.getElementById('card-content');

    Object.entries(user)
        .filter(([key, value]) => key !== "id")
        .forEach(([key, value]) =>
            createUserField(key, value, cardContent)
        )
}
export async function getUser() {
    const url = window.location.href;
    const lastSlashIndex = url.lastIndexOf('/');
    const id = url.substring(lastSlashIndex + 1);

    if (isNaN(+id)) {
        throw new Error(`Unable to get user with id ${id}`)
    }

    try {
        const response = await handleFetchRequest(`/user/show/${id}`);
        const data = await response.json();

        if (data['error']) {
            throw new Error(data.error)
        }

        return  data;
    } catch (e) {
        alert(e.message);
    }
}

function createUserField(key, value, parent) {
    const field = document.createElement("div");
    field.className = "col-lg-6";

    const title = document.createElement("h5");
    const strongTag = document.createElement("strong");
    strongTag.textContent = key.slice(0, 1).toUpperCase() + key.slice(1);

    title.appendChild(strongTag);
    field.appendChild(title);

    const textValue = document.createElement("p");
    textValue.className = "display-5 text-secondary";
    textValue.textContent = value;

    field.appendChild(textValue);

    parent.appendChild(field);
}