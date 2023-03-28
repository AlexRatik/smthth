import {validateUserForm} from "./validateUserForm.mjs";
import {handleFetchRequest} from "./utils/handleFetchRequest.mjs";
import {getUser} from "./getUser.mjs";

const editUserForm = document.getElementById("edit-user-form");

const nameInput = document.getElementById("user-name");
const emailInput = document.getElementById("user-email");
const genderInput = document.getElementById("user-gender");
const statusInput = document.getElementById("user-status");

let id = null;

window.addEventListener('DOMContentLoaded', handleUserLoad)

async function handleUserLoad(){
    try {
        const user = await getUser();
        nameInput.value = user.name;
        emailInput.value = user.email;
        genderInput.value = user.gender;
        statusInput.value = user.status;
        id = user.id;
    } catch (e) {
        console.log(e.message);
    }
}

editUserForm.addEventListener("submit", handleEditUser);

function handleEditUser(e) {
    e.preventDefault();

    const [name, email, gender, status] = [nameInput.value, emailInput.value, genderInput.value, statusInput.value];

    if (validateUserForm(name, email, gender, status)) {
        const user = {name, email, gender, status};

        handleFetchRequest(`/users/update/${id}`,
            "PUT",
            JSON.stringify(user),
            {
                "Content-Type": "application/json"
            })
            .then((res) => {
                if (res.status === 200) {
                    window.location.href = "/"
                } else {
                    return res.json();
                }
            })
            .then(data => {
                if (data && data.error) {
                    throw new Error(data.error);
                }
            })
            .catch(e => {
                alert(e.message)
            });
    }
}