import {handleFetchRequest} from "./utils/handleFetchRequest.mjs";

const deleteBtn = document.getElementById('delete-cards-btn');

deleteBtn.addEventListener('click', handleDeleteUsers);

function handleDeleteUsers() {
    const checkboxes = document.getElementsByName('users-deletion');
    const checkboxesArray = Array.from(checkboxes).filter(checkbox => checkbox.checked);
    const checkedItems = checkboxesArray.map(checkbox => checkbox.id.replace('checkbox-', ''));

    handleFetchRequest(
        '/users/delete',
        'POST',
        JSON.stringify({ids: checkedItems}),
        {
            "Content-Type": "application/json"
        }
    ).then(res => {
        if (res.status === 204) {
            checkedItems.forEach(id => {
                const card = document.getElementById(`user-card-${id}`);
                card.remove();
            });
        } else {
            throw new Error(res.statusText);
        }
    }).catch(e => {
        alert(e.message)
    });
}