import {Modal} from "bootstrap";
import {handleFetchRequest} from "./utils/handleFetchRequest.mjs";

const deleteUserModal = new Modal(document.getElementById("modal-delete-user"));
const confirmDeleteUser = document.getElementById("confirm-delete-user");

let userForDeleteId = null;

if (confirmDeleteUser && deleteUserModal) {
    initDeletionFunctionality();
}

export function handleShowModal(id) {
    userForDeleteId = id;
    deleteUserModal.show();
}

function handleHideModal() {
    userForDeleteId = null;
    deleteUserModal.hide();
}

function handleConfirmUserDelete(userId) {
    deleteUser(userId);
}

function deleteUser(userId) {
    if (userId) {
        handleFetchRequest(`/users/${userId}`, "DELETE")
            .then(() => {
                const deletedUserCard = document.getElementById(`user-card-${userId}`);
                deletedUserCard.remove();
                handleHideModal();
            })
            .catch(e => {
                alert(`Something went wrong: ${e.message}`);
            })
    }
}

function initDeletionFunctionality() {
    confirmDeleteUser.addEventListener("click", () => handleConfirmUserDelete(userForDeleteId));
}