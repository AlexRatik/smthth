import {handleShowModal} from "./deleteUser.mjs";
import {createCheckbox} from "./utils/createCheckbox.mjs";

export function createUserCard(user, parentNode) {
    const {id, name, email, status, gender} = user;
    const container = document.createElement("div");
    container.className = "col mt-3";
    container.setAttribute("id", `user-card-${id}`);

    const main = document.createElement("div");
    main.className = "user-card card align-self-sm-stretch h-100";

    const mainLink = document.createElement("a");
    mainLink.className = "d-flex flex-column h-100"
    mainLink.href = `/user/${id}`;

    const header = document.createElement("div");
    header.className = "card-header";

    const title = document.createElement("h2");
    title.textContent = name;

    header.appendChild(title);
    mainLink.appendChild(header)

    const body = document.createElement("div");
    body.className = "card-body align-self-center d-flex flex-column justify-content-center";

    [email, gender, status].forEach(field => {
        const row = document.createElement("p");
        row.className = "card-text lead";
        row.textContent = field;
        body.appendChild(row);
    })

    mainLink.appendChild(body);
    main.appendChild(mainLink);

    const footer = document.createElement("div");
    footer.className = "card-footer position-relative";

    const buttonContainer = document.createElement('div');
    buttonContainer.className = "d-grid gap-2 d-md-flex justify-content-md-end";

    const editLink = document.createElement("a");
    editLink.className = "btn user-card__btn";
    editLink.href = `/users/edit/${id}`;
    editLink.setAttribute("role", "button");
    editLink.textContent = "Edit";

    buttonContainer.appendChild(editLink);

    const deleteButton = document.createElement("button");
    deleteButton.className = "btn min-w-25 user-card__btn user-card__delete-btn";
    deleteButton.setAttribute("data-user-id", id);
    deleteButton.textContent = "Delete";
    deleteButton.addEventListener("click", () => handleShowModal(id));

    buttonContainer.appendChild(deleteButton);

    const checkbox = createCheckbox("", "user-card__checkbox", id, 'users-deletion');
    const input = checkbox.querySelector('input');
    input.setAttribute('data-userId', id);

    footer.appendChild(checkbox);
    footer.appendChild(buttonContainer);

    main.appendChild(footer);

    container.appendChild(main);

    parentNode.appendChild(container);
}
